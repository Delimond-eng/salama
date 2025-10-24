import { get, postJson } from "../modules/http.js"; // Assuming postJson might be used elsewhere
import Pagination from "../components/pagination.js";

// Assuming getColor is a globally available function or defined elsewhere in the project
// function getColor(name, opacity = 1) { /* ... implementation ... */ }

new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            patrolPendings: [],
            previousPatrolCount: 0,
            selectedPatrol: null,
            intervalId: null,
            selectedPatrol: null, // patrouille active
            map: null, // instance Leaflet
            animatedMarker: null, // marqueur animé (point bleu)
            sites: [], // This will be watched
            hasInitialized: false,
            audio: new Audio("/assets/audios/audio-1.wav"),
            presences: [],
            selectedPresence: null,
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            filter_date: "",
            search: "",
            count: {
                sites: 0,
                presences: 0,
                agents: 0,
                holidays: 0,
                patrols: 0,
            },
        };
    },

    components: {
        Pagination,
    },

    watch: {
        sites: {
            handler(newSites, oldSites) {
                if (!this.areSitesEqual(newSites, oldSites)) {
                    this.refreshMap();
                }
            },
            deep: true,
        },

        selectedPatrol: {
            handler(newVal, oldVal) {
                if (JSON.stringify(newVal) !== JSON.stringify(oldVal)) {
                    this.$nextTick(() => {
                        this.getPatrolDetailMap();
                    });
                }
            },
            immediate: true,
            deep: false,
        },
        allSites() {
            this.refreshSelectSite();
        },
    },

    mounted() {
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }

        this.viewAllSites();
        if (location.pathname === "/") {
            setInterval(() => {
                this.viewAllSites();
            }, 5000);
            this.viewAllPendingScans();
        } // Starts fetching pending patrols
        this.loadPresencesData();
    },

    beforeDestroy() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
        // Clean up maps if the component is destroyed
        $(".leaflet").each(function () {
            if (this._leaflet_map) {
                this._leaflet_map.remove();
                delete this._leaflet_map;
            }
        });
    },

    methods: {
        onSelectedPresence(presence) {
            this.selectedPresence = presence;
        },

        loadPresencesData() {
            if (location.pathname === "/global.view") {
                this.isDataLoading = true;
                get(
                    `/global.view.req?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&site=${this.search}`
                ).then((res) => {
                    this.isDataLoading = false;
                    this.presences = res.data.dash_presences.data;
                    this.count = res.data.count;
                    this.pagination = {
                        current_page: res.data.dash_presences.current_page,
                        last_page: res.data.dash_presences.last_page,
                        total: res.data.dash_presences.total,
                        per_page: res.data.dash_presences.per_page,
                    };
                });
            }
        },

        refreshSelectSite() {
            if ($(".select-site").length) {
                const options = [
                    { value: "", text: "Tous les agents" },
                    ...this.allSites.map((site) => ({
                        value: String(site.id),
                        text: site.name,
                    })),
                ];
                const tom = new TomSelect(".select-site", {
                    plugins: {
                        dropdown_input: {},
                    },
                    create: false,
                    placeholder: "Filtrez par site",
                    options: options,
                });

                tom.on("change", (value) => {
                    this.pagination.current_page = 1;
                    this.search = value;
                    this.loadPresencesData();
                });
            }
        },

        exportToPdf() {
            window.open(
                "/global.view.export",
                "_blank",
                "width=500,height=500"
            );
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.loadPresencesData();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.loadPresencesData();
        },

        refreshMap() {
            this.$nextTick(() => {
                // Ensures DOM is updated before map operations
                this.getMap();
            });
        },

        getMap() {
            let sitesData = this.allSites;
            // Filtrer les sites pour s'assurer qu'ils ont des coordonnées valides
            sitesData = sitesData.filter((site) => {
                if (!site.latlng) return false;
                const parts = site.latlng.split(",");
                return (
                    parts.length === 2 &&
                    !isNaN(parseFloat(parts[0])) &&
                    !isNaN(parseFloat(parts[1]))
                );
            });

            // Itérer sur tous les éléments désignés comme conteneurs de carte principaux
            $(".main-leaflet").each(function () {
                // 'this' à l'intérieur de this.each est l'élément DOM
                const mapElement = this;

                // Si une instance de carte existe déjà sur cet élément, la supprimer d'abord
                if (mapElement._leaflet_map) {
                    mapElement._leaflet_map.remove();
                    delete mapElement._leaflet_map; // Important pour supprimer la référence
                }

                // Continuer seulement s'il y a des sites à afficher
                if (sitesData && sitesData.length > 0) {
                    const hasPendingSites = sitesData.some(
                        (s) => s.status === "pending"
                    );
                    let avgLat, avgLng;

                    if (hasPendingSites) {
                        // Si un site avec le statut "pending" existe, centrer la carte sur les sites "pending"
                        const pendingSites = sitesData.filter(
                            (s) => s.status === "pending"
                        );
                        avgLat =
                            pendingSites.reduce(
                                (acc, s) =>
                                    acc + parseFloat(s.latlng.split(",")[0]),
                                0
                            ) / pendingSites.length;
                        avgLng =
                            pendingSites.reduce(
                                (acc, s) =>
                                    acc + parseFloat(s.latlng.split(",")[1]),
                                0
                            ) / pendingSites.length;
                    } else {
                        // Si aucun site "pending", calculer la moyenne de tous les sites
                        avgLat =
                            sitesData.reduce(
                                (acc, s) =>
                                    acc + parseFloat(s.latlng.split(",")[0]),
                                0
                            ) / sitesData.length;
                        avgLng =
                            sitesData.reduce(
                                (acc, s) =>
                                    acc + parseFloat(s.latlng.split(",")[1]),
                                0
                            ) / sitesData.length;
                    }
                    const map = L.map(mapElement).setView([avgLat, avgLng], 13);
                    mapElement._leaflet_map = map; // Stocker la nouvelle instance de carte sur l'élément

                    L.tileLayer(
                        "https://b.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png",
                        {
                            attribution:
                                "Salama plateforme, cartographie des sites",
                        }
                    ).addTo(map);

                    const color = $("html").hasClass("dark")
                        ? getColor("darkmode.100")
                        : getColor("primary");
                    const colorOpacity = $("html").hasClass("dark")
                        ? getColor("darkmode.100", 0.6)
                        : getColor("primary", 0.8);

                    const clusterSvgBase64 = window.btoa(`
                <svg xmlns="http://www.w3.org/2000/svg" width="55.066" height="47.691" viewBox="0 0 55.066 47.691">
                <g transform="translate(-319.467 -83.991)">
                    <g>
                    <path d="M12.789,17.143a15,15,0,0,1,20.7,0l-1.6,2.141-.018-.018a12.352,12.352,0,0,0-17.469,0l-.018.018Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.845"/>
                    <path d="M10.384,13.919a19,19,0,0,1,25.511,0l-2.016,2.7a15.647,15.647,0,0,0-21.479,0Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.652"/>
                    <path d="M7.982,10.7a22.978,22.978,0,0,1,30.313,0l-2,2.679a19.652,19.652,0,0,0-26.316,0Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.453"/>
                    </g>
                    <g transform="translate(427.806 461.061) rotate(-120)">
                    <path d="M12.789,17.143a15,15,0,0,1,20.7,0l-1.6,2.141-.018-.018a12.352,12.352,0,0,0-17.469,0l-.018.018Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.845"/>
                    <path d="M10.384,13.919a19,19,0,0,1,25.511,0l-2.016,2.7a15.647,15.647,0,0,0-21.479,0Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.652"/>
                    <path d="M7.982,10.7a22.978,22.978,0,0,1,30.313,0l-2,2.679a19.652,19.652,0,0,0-26.316,0Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.453"/>
                    </g>
                    <circle cx="11" cy="11" r="11" transform="translate(336 96)" fill="${colorOpacity}"/>
                    <g transform="translate(613.194 -139.96) rotate(120)">
                    <path d="M12.789,17.143a15,15,0,0,1,20.7,0l-1.6,2.141-.018-.018a12.352,12.352,0,0,0-17.469,0l-.018.018Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.845"/>
                    <path d="M10.384,13.919a19,19,0,0,1,25.511,0l-2.016,2.7a15.647,15.647,0,0,0-21.479,0Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.652"/>
                    <path d="M7.982,10.7a22.978,22.978,0,0,1,30.313,0l-2,2.679a19.652,19.652,0,0,0-26.316,0Z" transform="translate(323.861 78.999)" fill="${colorOpacity}" opacity="0.453"/>
                    </g>
                </g>
                </svg>`);

                    const markerCluster = L.markerClusterGroup({
                        maxClusterRadius: 30, // Réduit si tu veux qu’ils se séparent plus vite
                        spiderfyOnMaxZoom: true, // Déplie les points superposés au zoom max
                        showCoverageOnHover: false,
                        zoomToBoundsOnClick: true, // Active le zoom automatique au clic
                        iconCreateFunction: (cluster) =>
                            L.divIcon({
                                html: `
                        <div class="relative w-full h-full">
                            <div class="absolute inset-0 flex items-center justify-center ml-1.5 mb-0.5 font-medium text-white">
                                ${cluster.getChildCount()}
                            </div>
                            <img class="w-full h-full" src="data:image/svg+xml;base64,${clusterSvgBase64}" />
                        </div>`,
                                className: "",
                                iconSize: L.point(30, 30),
                                iconAnchor: L.point(10, 20),
                            }),
                    });

                    markerCluster.on("clusterclick", function (a) {
                        // Calculer la zone des marqueurs du cluster
                        const bounds = a.layer.getBounds();
                        const map = a.target._map;

                        // Zoomer un peu plus que le zoom automatique
                        map.fitBounds(bounds, {
                            padding: [50, 50],
                            maxZoom: 18, // force un zoom plus fort si nécessaire
                        });
                    });

                    map.addLayer(markerCluster);

                    for (const site of sitesData) {
                        const [lat, lng] = site.latlng
                            .split(",")
                            .map(parseFloat);
                        const markerColor =
                            site.status === "pending"
                                ? getColor("danger")
                                : color;
                        const markerSvgBase64 = window.btoa(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 31.063">
                    <g id="Group_16" data-name="Group 16" transform="translate(-408 -150.001)">
                        <path id="Subtraction_21" data-name="Subtraction 21" d="M10,31.064h0L1.462,15.208A10,10,0,1,1,20,10a9.9,9.9,0,0,1-1.078,4.522l-.056.108c-.037.071-.077.146-.121.223L10,31.062ZM10,2a8,8,0,1,0,8,8,8,8,0,0,0-8-8Z" transform="translate(408 150)" fill="${markerColor}"/>
                        <circle id="Ellipse_26" data-name="Ellipse 26" cx="6" cy="6" r="6" transform="translate(412 154)" fill="${markerColor}"/>
                    </g>
                    </svg>`);

                        const marker = L.marker([lat, lng], {
                            title: site.name,
                            icon: L.icon({
                                iconUrl: `data:image/svg+xml;base64,${markerSvgBase64}`,
                                iconSize: [40, 55],
                                iconAnchor: [30, 40],
                            }),
                        });

                        // --- DÉBUT : Logique pour le Popup des zones (areas) ---
                        let popupContentHtml = `<div class="p-1 bg-white rounded-lg max-w-sm min-w-[200px]">`; // Conteneur du popup
                        popupContentHtml += `<h3 class="font-extrabold text-base text-blue-500 uppercase mb-2 pb-2">Zones de patrouille</h3>`; // Titre du popup

                        if (
                            site.areas &&
                            Array.isArray(site.areas) &&
                            site.areas.length > 0
                        ) {
                            popupContentHtml +=
                                '<ul class="list-none space-y-1  text-sm text-gray-600">';
                            site.areas.forEach((area) => {
                                // Adapter 'area.name' si la propriété contenant le nom de la zone est différente
                                // ou si 'area' est directement une chaîne de caractères.
                                const areaName = area.libelle;
                                popupContentHtml += `<li class="py-1.5 px-1 hover:bg-gray-100 border-b border-slate-200 flex justify-start"><svg class="mr-2" width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13.29 14.667L11 16.097V3.81l3-1.5v5.968a6.182 6.182 0 0 1 1-1.104V2.307l3.024 1.503-.003 1.974A6.275 6.275 0 0 1 19 5.7l.02.001.005-2.51L14.5.94l-4 2-4-2L2 3.191V17.9l4.5-2.811 4 2.5 3.15-1.968q-.202-.485-.36-.955zM6 14.223l-3.001 1.876-.023-12.29L6 2.308zm4 1.875l-3-1.875V2.309l3 1.5zM19 7a4.96 4.96 0 0 0-4.9 5.086c0 2.807 2.678 6.606 4.9 10.914 2.222-4.308 4.9-8.107 4.9-10.914A4.96 4.96 0 0 0 19 7zm0 13.877c-.298-.543-.598-1.077-.89-1.6-1.548-2.762-3.01-5.37-3.01-7.191a3.905 3.905 0 1 1 7.8 0c0 1.82-1.462 4.429-3.01 7.19-.292.524-.592 1.058-.89 1.601zm0-11.043A2.166 2.166 0 1 0 21.13 12 2.147 2.147 0 0 0 19 9.834zm0 3.332A1.167 1.167 0 1 1 20.13 12 1.15 1.15 0 0 1 19 13.166z"/><path fill="none" d="M0 0h24v24H0z"/></svg> ${areaName}</li>`;
                            });
                            popupContentHtml += "</ul>";
                        } else {
                            popupContentHtml +=
                                '<p class="text-sm text-gray-500 italic">Aucune zone de patrouille définie pour ce site.</p>';
                        }
                        popupContentHtml += "</div>"; // Fin du conteneur du popup

                        marker.bindPopup(popupContentHtml, {
                            direction: "bottom",
                        });
                        // --- FIN : Logique pour le Popup des zones (areas) ---

                        const labelContent = `
                    <div class="label-content">
                        <h3 class="font-bold">${site.name}</h3>
                        <p>${site.code || "Aucune description disponible."}</p>
                        <small class="text-xs mt-1">${
                            site.status === "pending"
                                ? "Patrouille en cours"
                                : "Pas de patrouille"
                        }</small>
                    </div>`;

                        const siteStatus = site.status;
                        const tooltipColor =
                            siteStatus === "pending"
                                ? "bg-danger tooltip-red border-none text-white z-50"
                                : "primary";

                        marker
                            .bindTooltip(labelContent, {
                                permanent: true,
                                direction: "top",
                                className: `custom-tooltip px-2 py-1 rounded-lg shadow-lg text-sm font-medium ${tooltipColor}`,
                                offset: [-8, -35],
                                opacity: 0.95,
                            })
                            .openTooltip();

                        marker.on("mouseover", function () {
                            const el = this.getTooltip().getElement();
                            el.style.zIndex = "9999";
                            if (siteStatus !== "pending") {
                                el.style.backgroundColor = "#1e3faa";
                                el.style.color = "#FFFFFF";
                                if (this._icon)
                                    this._icon.style.zIndex = "9999";
                            }
                        });

                        marker.on("mouseout", function () {
                            const el = this.getTooltip().getElement();
                            el.style.zIndex = "";
                            if (siteStatus !== "pending") {
                                el.style.backgroundColor = "#FFFFFF";
                                el.style.color = "#000000";
                                if (this._icon) this._icon.style.zIndex = "";
                            }
                        });

                        markerCluster.addLayer(marker);
                    }
                } else {
                    console.log(
                        "No sites to display on map element:",
                        mapElement
                    );
                }
            }); // Fin de $(".main-leaflet").each
        },

        getPatrolDetailMap() {
            const patrol = this.selectedPatrol;
            const mapContainer = document.querySelector(`.detail-leaflet`);

            if (!mapContainer) {
                console.warn(
                    "Aucun conteneur de carte trouvé pour cette patrouille"
                );
                return;
            }

            // Supprimer ancienne carte si existante
            if (mapContainer._leaflet_map) {
                mapContainer._leaflet_map.remove();
            }

            if (patrol) {
                const [lat, lng] = patrol.site.latlng
                    .split(",")
                    .map(parseFloat);
                this.map = L.map(mapContainer).setView([lat, lng], 17);
                mapContainer._leaflet_map = this.map;

                L.tileLayer(
                    "https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png",
                    {
                        attribution:
                            "Salama plateforme, cartographie des sites",
                    }
                ).addTo(this.map);

                // 🟦 Polygone du site
                const areaCoords = patrol.site.areas.map((area) =>
                    area.latlng.split(",").map(parseFloat)
                );
                const sitePolygon = L.polygon(areaCoords, {
                    color: "blue",
                    fillColor: "#cce5ff",
                    fillOpacity: 0.2,
                    weight: 4,
                }).addTo(this.map);

                // 🟧 Tracé du parcours
                const pathCoords = patrol.map_datas.map((item) =>
                    item.latlng.split(",").map(parseFloat)
                );
                L.polyline(pathCoords, {
                    color: "orange",
                    weight: 4,
                    opacity: 0.9,
                }).addTo(this.map);

                // 📍 Marqueurs
                patrol.map_datas.forEach((item) => {
                    const [itemLat, itemLng] = item.latlng
                        .split(",")
                        .map(parseFloat);
                    const color =
                        item.scan_status === "scanned" ? "green" : "red";

                    const customIcon = L.divIcon({
                        className: "",
                        html: `
                        <svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.5 3H16C15.7239 3 15.5 3.22386 15.5 3.5V3.55891L19 6.35891V3.5C19 3.22386 18.7762 3 18.5 3Z" fill="${color}"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.75 9.5C10.75 8.80964 11.3097 8.25 12 8.25C12.6904 8.25 13.25 8.80964 13.25 9.5C13.25 10.1904 12.6904 10.75 12 10.75C11.3097 10.75 10.75 10.1904 10.75 9.5Z" fill="${color}"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.75 10.9605L21.5315 11.5857C21.855 11.8444 22.3269 11.792 22.5857 11.4685C22.8444 11.1451 22.792 10.6731 22.4685 10.4143L14.3426 3.91362C12.9731 2.81796 11.027 2.81796 9.65742 3.91362L1.53151 10.4143C1.20806 10.6731 1.15562 11.1451 1.41438 11.4685C1.67313 11.792 2.1451 11.8444 2.46855 11.5857L3.25003 10.9605V21.25H2.00003C1.58581 21.25 1.25003 21.5858 1.25003 22C1.25003 22.4142 1.58581 22.75 2.00003 22.75H22C22.4142 22.75 22.75 22.4142 22.75 22C22.75 21.5858 22.4142 21.25 22 21.25H20.75V10.9605ZM9.25003 9.5C9.25003 7.98122 10.4812 6.75 12 6.75C13.5188 6.75 14.75 7.98122 14.75 9.5C14.75 11.0188 13.5188 12.25 12 12.25C10.4812 12.25 9.25003 11.0188 9.25003 9.5ZM12.0494 13.25C12.7143 13.25 13.2871 13.2499 13.7459 13.3116C14.2375 13.3777 14.7088 13.5268 15.091 13.909C15.4733 14.2913 15.6223 14.7625 15.6884 15.2542C15.7462 15.6842 15.7498 16.2146 15.75 16.827C15.75 16.8679 15.75 16.9091 15.75 16.9506L15.75 21.25H14.25V17C14.25 16.2717 14.2484 15.8009 14.2018 15.454C14.1581 15.1287 14.0875 15.0268 14.0304 14.9697C13.9733 14.9126 13.8713 14.842 13.546 14.7982C13.1991 14.7516 12.7283 14.75 12 14.75C11.2717 14.75 10.8009 14.7516 10.4541 14.7982C10.1288 14.842 10.0268 14.9126 9.9697 14.9697C9.9126 15.0268 9.84199 15.1287 9.79826 15.454C9.75162 15.8009 9.75003 16.2717 9.75003 17V21.25H8.25003L8.25003 16.9506C8.24999 16.2858 8.24996 15.7129 8.31163 15.2542C8.37773 14.7625 8.52679 14.2913 8.90904 13.909C9.29128 13.5268 9.76255 13.3777 10.2542 13.3116C10.7129 13.2499 11.2858 13.25 11.9507 13.25H12.0494Z" fill="${color}"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.75 9.5C10.75 8.80964 11.3097 8.25 12 8.25C12.6904 8.25 13.25 8.80964 13.25 9.5C13.25 10.1904 12.6904 10.75 12 10.75C11.3097 10.75 10.75 10.1904 10.75 9.5Z" fill="${color}"/>
                        </svg>
                    `,
                        iconSize: [40, 40],
                        iconAnchor: [20, 40],
                        popupAnchor: [0, -40],
                    });

                    const marker = L.marker([itemLat, itemLng], {
                        icon: customIcon,
                    }).addTo(this.map);
                    marker.bindPopup(
                        `<strong>${item.libelle}</strong><br>Status : ${item.scan_status}`
                    );
                });
                this.map.fitBounds(sitePolygon.getBounds(), {
                    padding: [20, 20],
                });
            }
        },

        viewAllPendingScans() {
            this.intervalId = setInterval(() => {
                get("/patrols.pending")
                    .then((res) => {
                        const newPatrols = res.data.pending_patrols;
                        if (
                            this.hasInitialized &&
                            newPatrols.length > this.previousPatrolCount
                        ) {
                            this.audio.play();
                        }
                        this.hasInitialized = true;
                        this.previousPatrolCount = newPatrols.length;
                        this.patrolPendings = newPatrols;

                        if (this.selectedPatrol) {
                            const updatedPatrol = this.patrolPendings.find(
                                (patrol) => patrol.id === this.selectedPatrol.id
                            );
                            if (updatedPatrol) {
                                this.selectedPatrol = updatedPatrol;
                            } else {
                                this.selectedPatrol = null;
                                if (
                                    document.querySelector("#btn-reset") !==
                                    null
                                ) {
                                    document
                                        .getElementById("btn-reset")
                                        .click();
                                }
                            }
                        }
                    })
                    .catch((err) => {
                        console.log("Erreur :", err);
                        this.error =
                            "Erreur lors de la récupération des patrouilles en attente.";
                    });
            }, 5000); // Original comment said 5 seconds, but code is 3000ms
        },

        areSitesEqual(newSites, oldSites) {
            if (newSites.length !== oldSites.length) return false;

            for (let i = 0; i < newSites.length; i++) {
                const newSite = newSites[i];
                const oldSite = oldSites[i];

                if (
                    newSite.id !== oldSite.id ||
                    newSite.latlng !== oldSite.latlng ||
                    newSite.status !== oldSite.status ||
                    JSON.stringify(newSite.areas) !==
                        JSON.stringify(oldSite.areas)
                ) {
                    return false;
                }
            }

            return true;
        },

        viewAllSites() {
            this.isLoading = true; // Good to indicate loading
            get("/sites")
                .then((res) => {
                    this.sites = res.data.sites;
                    this.error = null; // Clear previous errors
                })
                .catch((err) => {
                    console.log("Erreur :", err);
                    this.error = "Erreur lors de la récupération des sites."; // More specific error message
                    this.sites = []; // Clear sites or keep old ones, depending on desired behavior on error
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        onSelectItem(item) {
            this.selectedPatrol = item;
        },
    },

    computed: {
        allPendingPatrols() {
            return this.patrolPendings;
        },
        allSites() {
            return this.sites;
        },

        allPresences() {
            /* if (this.search) {
                return this.presences.filter((el) =>
                    el.name.toLowerCase().includes(this.search.toLowerCase())
                );
            } else {
                
            } */
            return this.presences;
        },

        agentsAbsents() {
            if (this.selectedPresence) {
                const agents = this.selectedPresence.agents || [];
                const presences = this.selectedPresence.presences || [];

                // Liste des ID d'agents qui ont une présence enregistrée
                const agentsPresentsIds = presences.map((p) => p.agent_id);
                // On filtre les agents dont l'ID ne figure pas dans les présences
                return agents.filter(
                    (agent) => !agentsPresentsIds.includes(agent.id)
                );
            } else {
                return [];
            }
        },
    },
});
