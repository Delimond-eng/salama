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
            form: {
                title: "",
                content: "",
                site_id: "",
            },
            filter_date: "",
            announces: [],
            sites: [],
            delete_id: "",
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }

        this.pristine = new Pristine(document.querySelector(".form-announce"), {
            classTo: "input-form",
            errorClass: "has-error",
            errorTextParent: "input-form",
            errorTextClass: "text-danger mt-2",
        });

        this.viewAllSites();
        this.viewAllAnnounces();
    },

    methods: {
        createAnnounce(event) {
            const isValid = this.pristine.validate();
            if (isValid) {
                const url = "announce.create";
                this.isLoading = true;
                postJson(url, this.form)
                    .then(({ data, status }) => {
                        this.isLoading = false;
                        // Gestion des erreurs
                        if (data.errors !== undefined) {
                            this.error = data.errors.toString();
                            return;
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
                            this.viewAllAnnounces();
                            this.reset();
                        }
                    })
                    .catch((err) => {
                        this.isLoading = false;
                        this.error = err;
                        console.log(err);
                    });
            }
        },

        deleteAnnounce(id) {
            let self = this;
            this.delete_id = id;
            postJson("/delete", {
                table: "announces",
                id: id,
            })
                .then((res) => {
                    this.viewAllAnnounces();
                    self.delete_id = "";
                })
                .catch((err) => {
                    self.delete_id = "";
                });
        },

        reset() {
            this.form = {
                title: "",
                content: "",
                site_id: "",
            };
        },
        viewAllAnnounces() {
            this.isDataLoading = true;
            get("/announces.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.announces = res.data.announces;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },

        viewAllSites() {
            get("/sites")
                .then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => console.log("error"));
        },
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allAnnounces() {
            if (this.filter_date) {
                const [year, month, day] = this.filter_date.split("-");
                // Formater en JJ/MM/AAAA
                const formattedDate = `${day}/${month}/${year}`;
                return this.announces.filter((el) =>
                    el.created_at.includes(formattedDate)
                );
            } else {
                return this.announces;
            }
        },
    },
});
