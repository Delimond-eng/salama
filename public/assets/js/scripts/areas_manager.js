import { get, postJson } from "../modules/http.js";
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
            load_id: "",
            delete_id: "",
            openAccordion: null,
            form: {
                id: "",
                name: "",
                code: "",
                adresse: "",
                phone: "",

                areas: [
                    {
                        libelle: "",
                    },
                ],
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
                areas: [
                    {
                        libelle: "",
                    },
                ],
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
