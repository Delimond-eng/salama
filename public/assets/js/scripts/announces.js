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
            delete_id: "",
            form: {
                title: "",
                content: "",
                site_id: "",
            },
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
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

        deleteAnnounce(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement ce communiqué ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "announces",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllAnnounces();
                    });
                }
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
            get(
                `/announces.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.announces = res.data.announces.data;
                    this.pagination = {
                        current_page: res.data.announces.current_page,
                        last_page: res.data.announces.last_page,
                        total: res.data.announces.total,
                        per_page: res.data.announces.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },
        changePage(page) {
            this.pagination.current_page = page;
            this.viewAllAnnounces();
        },
        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewAllAnnounces();
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
