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
            schedules: [],
            delete_id: "",

            form: {
                id: "",
                site_id: "",
                libelle: "",
                date: "",
                start_time: "",
                end_time: "",
            },
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            sites: [],
            search: "",
            filter_date: "",
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }
        this.pristine = new Pristine(document.querySelector(".form-planning"), {
            classTo: "input-form",
            errorClass: "has-error",
            errorTextParent: "input-form",
            errorTextClass: "text-danger mt-2",
        });
        this.viewAllSchedules();
        this.viewAllSites();
    },

    methods: {
        viewAllSites() {
            get("/sites")
                .then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => console.log("error"));
        },
        viewAllSchedules() {
            this.isDataLoading = true;
            get(
                `/schedules.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&date=${this.filter_date}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.schedules = res.data.schedules.data;
                    this.pagination = {
                        current_page: res.data.schedules.current_page,
                        last_page: res.data.schedules.last_page,
                        total: res.data.schedules.total,
                        per_page: res.data.schedules.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },
        reset() {
            this.form = {
                id: "",
                site_id: "",
                libelle: "",
                start_time: "",
                end_time: "",
            };
        },

        createSchedules(event) {
            const isValid = this.pristine.validate();
            if (isValid) {
                const forms = [];
                const url = "schedules.create";
                this.isLoading = true;
                postJson(url, { schedule: this.form, id: this.form.id })
                    .then(({ data, status }) => {
                        this.isLoading = false;
                        // Gestion des erreurs
                        if (data.errors !== undefined) {
                            this.error = data.errors.toString();
                            setTimeout(() => {
                                new Toastify({
                                    node: $("#failed-notification-content")
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
                            this.viewAllSchedules();
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

        deletePlanning(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement ce planning ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "schedules",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllSchedules();
                    });
                }
            });
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.viewAllSchedules();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewAllSchedules();
        },
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allSchedules() {
            if (this.search) {
                return this.schedules.filter((el) => {
                    return el.site_id === this.search;
                });
            } else {
                return this.schedules;
            }
        },
    },
});
