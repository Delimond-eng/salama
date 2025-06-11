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
            schedules: [],
            delete_id: "",

            selectedPlanning: null,

            form: {
                id: "",
                site_id: "",
                libelle: "",
                date: "",
                start_time: "",
                end_time: "",
            },
            formSup: {
                title: "",
                date: "",
                agent_id: "",
                sites: [
                    {
                        site_id: "",
                        started_at: "",
                        ended_at: "",
                        order: 1,
                    },
                ],
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
        if ($(".form-planning").length) {
            this.pristine = new Pristine(
                document.querySelector(".form-planning"),
                {
                    classTo: "input-form",
                    errorClass: "has-error",
                    errorTextParent: "input-form",
                    errorTextClass: "text-danger mt-2",
                }
            );
        }

        if ($(".tom-select").length) {
            const self = this;
            const tom = new TomSelect(".tom-select", {
                plugins: {
                    dropdown_input: {},
                },
                create: false,
                placeholder: "Séléctionnez un superviseur",
            });
            self.tom = tom;
            tom.on("change", function (value) {
                self.formSup.agent_id = value;
            });
        }
        if (location.pathname === "/schedules.supervisor") {
            this.viewAllSupervisorSchedules();
        } else {
            this.viewAllSchedules();
        }
        this.viewAllSites();
    },

    methods: {
        addSupField() {
            const lastIndex = this.formSup.sites.length;
            this.formSup.sites.push({
                site_id: "",
                started_at: "",
                ended_at: "",
                order: lastIndex + 1,
            });
        },

        deleteSupField(field) {
            const index = this.formSup.sites.indexOf(field);
            this.formSup.sites.splice(index, 1);
        },

        editSupSchedule(data) {
            const supData = JSON.parse(JSON.stringify(data)); // clone profond
            const [day, month, year] = supData.date.split("/");
            supData.date = `${year}-${month}-${day}`;
            this.formSup = supData;

            $(".tom-select")[0].tomselect.setValue(supData.agent_id);
        },

        createSupervisorSchedule(event) {
            this.isLoading = true;
            postJson("schedules.supervisor.create", this.formSup)
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
                        this.viewAllSupervisorSchedules();
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

        viewAllSupervisorSchedules() {
            this.isDataLoading = true;
            get(
                `/schedules.supervisor.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&date=${this.filter_date}`
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
            this.formSup = {
                title: "",
                date: "",
                agent_id: "",
                sites: [
                    {
                        site_id: "",
                        started_at: "",
                        ended_at: "",
                        order: 1,
                    },
                ],
            };
            $(".tom-select")[0].tomselect.clear();
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
        deleteSupPlanning(data) {
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
                        table: "schedule_supervisors",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllSupervisorSchedules();
                    });
                }
            });
        },

        changePage(page) {
            this.pagination.current_page = page;
            if (location.pathname === "/schedules.supervisor") {
                this.viewAllSupervisorSchedules();
            } else {
                this.viewAllSchedules();
            }
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            if (location.pathname === "/schedules.supervisor") {
                this.viewAllSupervisorSchedules();
            } else {
                this.viewAllSchedules();
            }
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

        status() {
            return (st) => {
                if (st === "pending") {
                    return "En attente";
                } else if (st === "partial") {
                    return "Non complet";
                } else {
                    return "Effectué";
                }
            };
        },
    },
});
