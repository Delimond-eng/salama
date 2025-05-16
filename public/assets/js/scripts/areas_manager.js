import {get, postJson } from "../modules/http.js";
new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            pristine: null,
            sites: [],
            selectedAreas: [],
            search: "",
            presencesOriginal: [], // données brutes chargées depuis l'API
            search2: "",
            load_id: "",
            delete_id: "",
            openAccordion: null,
            presences: [],
            presenceDate: new Date().toISOString().slice(0, 10),
            selectedSiteId: null,
            isPresenceLoading: false,
            form: {
                id: "",
                name: "",
                code: "",
                adresse: "",
                phone: "",

                areas: [{
                    libelle: "",
                }, ],
            },
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }

        //init pristine
        this.viewAllSites();
    },

    methods: {
        createSite() {
            this.isLoading = true;
            const form = this.form;
            postJson("site.create", form)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    // Gestion des erreurs
                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
                        return;
                    }
                    if (data.result) {
                        this.error = null;
                        this.result = data.result;
                        new Toastify({
                            node: $("#success-notification-content")
                                .clone()
                                .removeClass("hidden")[0],
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                        }).showToast();
                        if (this.form.id !== "") {
                            this.viewAllSites();
                        }
                        // clean fields
                        this.reset();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                    console.log(err);
                });
        },
        toggleAccordion(index) {
            this.openAccordion = this.openAccordion === index ? null : index;
        },

        downloadQRCode(id) {
            location.href = `/loadpdf/${id}`;
        },

        reset() {
            this.form = {
                name: "",
                code: "",
                adresse: "",
                phone: "",
                areas: [{
                    libelle: "",
                }, ],
            };
            if ($("#btn-reset").length) {
                document.getElementById("btn-reset").click();
            }
            /* const myModal = tailwind.Modal.getInstance(document.querySelector("#modal-add-on"));
            myModal.hide(); */
        },

        viewAllSites() {
            this.isDataLoading = true;
            get("/sites")
                .then((res) => {
                    this.isDataLoading = false;
                    this.sites = res.data.sites;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },
        deleteArea(id) {
            let self = this;
            this.load_id = id;
            postJson("/delete", {
                    table: "areas",
                    id: id,
                })
                .then((res) => {
                    const index = this.selectedAreas.findIndex(
                        (objet) => objet.id === id
                    );
                    if (index !== -1) {
                        this.selectedAreas.splice(index, 1);
                    }
                    self.load_id = "";
                    self.viewAllSites();
                })
                .catch((err) => {
                    self.load_id = "";
                });
        },
        viewPresenceBySite(site_id, togle_id) {
            this.selectedSiteId = site_id;
            this.isPresenceLoading = true;
            this.presences = [];
            // console.log("site" + site_id + "to" + togle_id);
            get(`/presences?site_id=${site_id}&date=${this.presenceDate}`)
                .then((res) => {
                    if (res.data.status === "success") {
                        this.presences = res.data.presences;
                        // console.log(res.data.presences);
                        this.toggleAccordion(togle_id);
                    }
                    this.isPresenceLoading = false;
                })
                .catch((err) => {
                    console.error("Erreur lors du chargement des présences :", err);
                    this.isPresenceLoading = false;
                });
        },
        exportToExcel() {
            const data = this.filteredPresences.map(p => ({
                "Nom complet": p.agent.fullname || '',
                "Horaire": p.horaire.libelle || '',
                "Heure d'entrée": p.started_at || '',
                "Heure de sortie": p.ended_at || '',
                "Durée": p.duree || '',
                "Retard": p.retard || '',
                "Statut photo début": p.status_photo_debut || '',
                "Statut photo fin": p.status_photo_fin || '',
                "Date": p.created_at.substring(0, 10) || '',
                "Commentaires": p.commentaires || ''
            }));

            const worksheet = XLSX.utils.json_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Présences");

            XLSX.writeFile(workbook, "presences.xlsx");
        },
        deleteSite(id) {
            let self = this;
            this.delete_id = id;
            postJson("/delete", {
                    table: "sites",
                    id: id,
                })
                .then((res) => {
                    self.delete_id = "";
                    self.viewAllSites();
                })
                .catch((err) => {
                    self.delete_id = "";
                });
        },
    },

    computed: {
        filteredPresences() {
            if (!this.search2.trim()) return this.presences;

            const searchTerm = this.search2.toLowerCase();

            return this.presences.filter(p => {
                return (
                    (p.agent.fullname || '').toLowerCase().includes(searchTerm) ||
                    (p.horaire.libelle || '').toLowerCase().includes(searchTerm) ||
                    (p.started_at || '').toLowerCase().includes(searchTerm) ||
                    (p.ended_at || '').toLowerCase().includes(searchTerm) ||
                    (p.duree || '').toLowerCase().includes(searchTerm) ||
                    (p.retard || '').toLowerCase().includes(searchTerm) ||
                    (p.status_photo_debut || '').toLowerCase().includes(searchTerm) ||
                    (p.status_photo_fin || '').toLowerCase().includes(searchTerm) ||
                    (p.created_at || '').toLowerCase().includes(searchTerm) ||
                    (p.commentaires || '').toLowerCase().includes(searchTerm)
                );
            });
        },
        allSites() {
            if (this.search && this.search.trim()) {
                return this.sites.filter(
                    (el) =>
                    el.name
                    .toLowerCase()
                    .includes(this.search.toLowerCase()) ||
                    el.code
                    .toLowerCase()
                    .includes(this.search.toLowerCase())
                );
            } else {
                return this.sites;
            }

        },
    },
});