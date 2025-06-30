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
            horaires: [],
            sites: [],
            groups: [],
            delete_id: "",
            form: {
                id: "",
                libelle: "",
                started_at: "",
                ended_at: "",
                tolerence: "",
            },
            formGroup: {
                id: "",
                libelle: "",
                horaire_id: "",
            },
            search: "",
            site_id: "",
            filter_date: "",
            filter_site: "",
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
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

        if ($(".tom-select").length) {
            const self = this;
            const tom = new TomSelect(".tom-select", {
                plugins: {
                    dropdown_input: {},
                },
                create: false,
                placeholder: "Séléctionnez un agent",
            });
            tom.on("change", function (value) {
                console.log("Agent selected : ", value);
            });
        }

        this.viewAllHoraires();
        this.viewAllGroups();
    },
    methods: {
        viewAllHoraires() {
            this.isDataLoading = true;
            let isAll = location.pathname === "/agent.groupe";
            let url = isAll
                ? "/horaires?all=1"
                : `/horaires?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`;
            get(url)
                .then((res) => {
                    this.isDataLoading = false;
                    if (isAll) {
                        this.horaires = res.data.horaires;
                    } else {
                        this.horaires = res.data.horaires.data;
                        if (location.pathname === "/presence.horaires") {
                            this.pagination = {
                                current_page: res.data.horaires.current_page,
                                last_page: res.data.horaires.last_page,
                                total: res.data.horaires.total,
                                per_page: res.data.horaires.per_page,
                            };
                        }
                    }
                })
                .catch((err) => console.log("error"));
        },

        viewAllGroups() {
            this.isDataLoading = true;
            get(
                `/groups?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.groups = res.data.groups.data;
                    if (location.pathname === "/agent.groupe") {
                        this.pagination = {
                            current_page: res.data.groups.current_page,
                            last_page: res.data.groups.last_page,
                            total: res.data.groups.total,
                            per_page: res.data.groups.per_page,
                        };
                    }
                })
                .catch((err) => console.log("error"));
        },

        changePage(page) {
            this.pagination.current_page = page;
            if (location.pathname === "/agent.groupe") {
                this.viewAllGroups();
            } else {
                this.viewAllHoraires();
            }
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            if (location.pathname === "/agent.groupe") {
                this.viewAllGroups();
            } else {
                this.viewAllHoraires();
            }
        },

        reset() {
            this.form = {
                id: "",
                libelle: "",
                started_at: "",
                ended_at: "",
                tolerence: "",
            };
            this.formGroup = {
                id: "",
                libelle: "",
                horaire_id: "",
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

        createGroup(event) {
            const isValid = this.pristine.validate();
            if (isValid) {
                const url = "/group.create";
                this.isLoading = true;
                postJson(url, this.formGroup)
                    .then(({ data, status }) => {
                        this.isLoading = false;
                        // Gestion des erreurs
                        if (data.errors !== undefined) {
                            this.error = data.errors.toString();
                        }
                        if (data.result) {
                            this.error = null;
                            this.result = data.result;
                            new Toastify({
                                node: $("#success-notification-content-group")
                                    .clone()
                                    .removeClass("hidden")[0],
                                duration: 3000,
                                newWindow: true,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true,
                            }).showToast();

                            this.viewAllGroups();
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

        deleteHoraire(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement cet horaire ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "presence_horaires",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllHoraires();
                    });
                }
            });
        },
        deleteGroup(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement ce groupe d'agent ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "agent_groups",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllGroups();
                    });
                }
            });
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

        allGroups() {
            return this.groups;
        },

        allPresenceReports() {
            return [];
        },
    },
});
