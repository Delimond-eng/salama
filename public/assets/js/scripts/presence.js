import { get, postJson } from "../modules/http.js";
new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            pristine: null,
            horaires: [],
            delete_id: "",
            sites: [],
            form: {
                libelle: "",
                started_at: "",
                ended_at: "",
                tolerence: "",
            },
            search: "",
            site_id: "",
            filter_date: "",
            filter_site: "",
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }

        if ($(".form-horaire").length) {
            this.pristine = new Pristine(
                document.querySelector(".form-horaire"),
                {
                    classTo: "input-form",
                    errorClass: "has-error",
                    errorTextParent: "input-form",
                    errorTextClass: "text-danger mt-2",
                }
            );
        }

        this.viewAllHoraires();
    },

    methods: {
        viewAllHoraires() {
            get("/horaires")
                .then((res) => {
                    this.horaires = res.data.horaires;
                })
                .catch((err) => console.log("error"));
        },

        reset() {
            this.form = {
                libelle: "",
                started_at: "",
                ended_at: "",
                tolerence: "",
            };
        },

        createHoraire(event) {
            const isValid = this.pristine.validate();
            if (isValid) {
                const url = "/horaire.create";

                this.isLoading = true;
                postJson(url, this.form)
                    .then(({ data, status }) => {
                        this.isLoading = false;
                        // Gestion des erreurs
                        if (data.errors !== undefined) {
                            this.error = data.errors.toString();
                        }
                        if (data.result) {
                            this.error = null;
                            console.log(data.result);
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

                            this.viewAllHoraires();
                            // clean fields
                            setTimeout(() => {
                                this.reset();
                            }, 100);
                        }
                    })
                    .catch((err) => {
                        this.isLoading = false;
                        this.error = err;
                        console.log(err);
                    });
            }
        },
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allHoraires() {
            if (this.search) {
                return this.horaires.filter((el) => {
                    return el.libelle
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            } else {
                return this.horaires;
            }
        },

        allPresenceReports() {
            return [];
        },
    },
});
