import { get, postJson } from "../modules/http.js";
import Pagination from "../components/pagination.js";
new Vue({
    el: "#App",
    components: {
        Pagination,
    },
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            pristine: null,
            tom: null,
            plannings: [],
            today: new Date(),

            form: {
                site_id: "",
                start_hour: "",
                pause: "",
                interval: "",
                number_of_plannings: "",
            },
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            sites: [],
            search: "",
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }
        this.viewAllSites();
        this.viewAllPlanningConfigs();
    },

    methods: {
        viewAllSites() {
            get("/sites")
                .then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => console.log("error"));
        },

        viewAllPlanningConfigs() {
            this.isDataLoading = true;
            get(
                `/config.planning.get?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&search=${this.search}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.pagination = {
                        current_page: res.data.plannings.current_page,
                        last_page: res.data.plannings.last_page,
                        total: res.data.plannings.total,
                        per_page: res.data.plannings.per_page,
                    };
                    this.plannings = res.data.plannings.data;
                })
                .catch((err) => console.log("error"));
        },

        setFormData(data) {
            let parsedData = JSON.parse(JSON.stringify(data));
            this.form = parsedData;
            // Si TomSelect est déjà initialisé, appliquer la valeur
            if (this.tom && this.form.site_id) {
                this.tom.setValue(String(this.form.site_id));
            }
        },

        reset() {
            this.form = {
                site_id: "",
                start_hour: "",
                pause: "",
                interval: "",
                number_of_plannings: "",
            };
            this.tom.setValue("");
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.viewAllPlanningConfigs();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewAllPlanningConfigs();
        },

        onSearchInput() {
            this.pagination.current_page = 1;
            this.viewAllPlanningConfigs();
        },

        createPlanningConfiguration(event) {
            this.isLoading = true;
            postJson("/config.planning.create", this.form)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    // Gestion des erreurs
                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
                        setTimeout(() => {
                            new Toastify({
                                node: $("#error-notification-content")
                                    .clone()
                                    .removeClass("hidden")[0],
                                duration: 3000,
                                newWindow: true,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true,
                            }).showToast();
                        }, 100);
                        return;
                    }
                    if (data.result) {
                        this.viewAllPlanningConfigs();
                        this.result = data.result;
                        setTimeout(() => {
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
                            // clean fields
                        }, 100);
                        this.reset();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        activateAutoPlanning(event, siteId) {
            const checked = event.target.checked;
            let val = 0;
            if (checked) {
                val = 1;
            } else {
                val = 0;
            }
            postJson("/config.planning.activate", {
                site_id: siteId,
                value: val,
            }).then(({ data, status }) => {
                this.plannings = [];
                if (data.errors !== undefined) {
                    this.error = data.errors.toString();
                    setTimeout(() => {
                        new Toastify({
                            node: $("#error-notification-content")
                                .clone()
                                .removeClass("hidden")[0],
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                        }).showToast();
                    }, 100);
                }
                this.viewAllPlanningConfigs();
            });
        },
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allSitePlannings() {
            return this.plannings;
        },
    },

    watch: {
        allSites() {
            const options = this.allSites.map((site) => ({
                value: String(site.id),
                text: site.name,
            }));

            // Détruire l'instance existante si elle existe
            if (this.tom) {
                this.tom.destroy();
            }

            this.tom = new TomSelect(".select-site", {
                plugins: {
                    dropdown_input: {},
                },
                create: false,
                placeholder: "Sélectionnez un site",
                options: options,
            });

            // Appliquer la valeur sélectionnée si elle existe déjà
            if (this.form.site_id) {
                this.tom.setValue(String(this.form.site_id));
            }

            this.tom.on("change", (value) => {
                this.form.site_id = value;
            });
        },
    },
});
