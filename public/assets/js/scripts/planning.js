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
            reports: [],
            delete_id: "",
            selectedPlanning: null,
            selectedSchedule: null,
            selectedReport: null,
            today: new Date(),

            form: {
                id: "",
                site_id: "",
                libelle: "",
                date: "",
                start_time: "",
                end_time: "",
                sites: [
                    {
                        site_id: "",
                    },
                ],
            },
            formSup: {
                title: "",
                date: "",
                agent_id: "",
                sites: [
                    {
                        site_id: "",
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
            sectors: [],
            sites: [],
            search: "",
            searchSite: "",
            searchStatus: "",
            filter_date: "",
            tomInstances: [],
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

        if ($(".tom-select-agent").length) {
            const self = this;
            $(".tom-select").each(function () {
                const tom = new TomSelect(this, {
                    plugins: {
                        dropdown_input: {},
                    },
                    create: false,
                    placeholder: "Sélectionnez un agent",
                });

                tom.on("change", function (value) {
                    self.formSup.agent_id = value;
                });
            });
        }
        this.refreshData();
        this.viewAllSectors();
        this.viewAllSites();

        this.$nextTick(() => {
            this.initTomSelects();
        });
    },

    methods: {
        viewPhoto(url) {
            window.open(
                url,
                "PhotoPopup",
                "width=800,height=600,resizable=yes,scrollbars=yes"
            );
        },
        selectSupSchedule(data) {
            this.selectedSchedule = data;
        },
        onSectorChanged(event) {
            var data = JSON.parse(JSON.stringify(event.target.value));
            console.log(data);
        },
        addSupField() {
            const lastIndex = this.formSup.sites.length;
            this.formSup.sites.push({
                site_id: "",
                order: lastIndex + 1,
            });
        },

        onChangeSite(event, index) {
            const selectedSiteId = event.target.value;
            const isDuplicate = this.formSup.sites.some((site, i) => {
                return i !== index && site.site_id == selectedSiteId;
            });
            if (isDuplicate) {
                // Réinitialise la sélection
                this.formSup.sites[index].site_id = "";

                // Message d'erreur avec Swal
                alert(
                    "Ce site est déjà sélectionné. Veuillez en choisir un autre."
                );
            } else {
                // Mise à jour normale
                this.formSup.sites[index].site_id = selectedSiteId;
            }
        },

        async deleteSupField(field) {
            const index = this.formSup.sites.indexOf(field);
            if (this.formSup.sites[index].id !== undefined) {
                postJson("/table.delete", {
                    table: "schedule_supervisor_sites",
                    id: this.formSup.sites[index].id,
                }).then(() => {
                    this.viewAllSupervisorSchedules();
                });
            }
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
        viewAllSectors() {
            get("/secteurs.all?key=all")
                .then((res) => {
                    this.sectors = res.data.sectors;
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

        onSearchInput() {
            this.pagination.current_page = 1;
            this.viewAllSchedules();
        },

        viewAllSupervisorSchedules() {
            this.isDataLoading = true;
            get(
                `/schedules.supervisor.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&date=${this.filter_date}&search=${this.search}`
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

        getSupervisorReports() {
            this.isDataLoading = true;
            get(
                `/supervisors.reports?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.reports = res.data.reports.data;
                    this.pagination = {
                        current_page: res.data.reports.current_page,
                        last_page: res.data.reports.last_page,
                        total: res.data.reports.total,
                        per_page: res.data.reports.per_page,
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
                sites: [{ site_id: "" }],
            };
            this.formSup = {
                title: "",
                date: "",
                agent_id: "",
                sites: [
                    {
                        site_id: "",
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
            this.refreshData();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.refreshData();
        },

        refreshData() {
            let path = location.pathname;
            switch (path) {
                case "/schedules":
                    this.viewAllSchedules();
                    break;
                case "/schedules.supervisor":
                    this.viewAllSupervisorSchedules();
                    break;
                case "/schedules.supervisor":
                    this.getSupervisorReports();
                    break;
                default:
                    break;
            }
        },

        parseDateFR(str) {
            const [day, month, year] = str.split("/");
            return new Date(`${year}-${month}-${day}`);
        },

        addSite() {
            this.form.sites.push({ site_id: "" });
            this.$nextTick(() => {
                this.initTomSelects();
            });
        },

        removeSite(index) {
            this.form.sites.splice(index, 1);
            const instance = this.tomInstances[index];
            if (instance) {
                instance.destroy();
                this.tomInstances.splice(index, 1);
            }

            this.$nextTick(() => {
                this.initTomSelects();
            });
        },

        initTomSelects() {
            const options = [
                { value: "", text: "--Sélectionnez un site--" },
                ...this.allSites.map((site) => ({
                    value: String(site.id),
                    text: site.name,
                })),
            ];

            this.$refs.siteSelect.forEach((select, index) => {
                if (!select) return;
                // Détruire si déjà instancié
                if (select.tomselect) {
                    select.tomselect.destroy();
                }

                const tom = new TomSelect(select, {
                    options,
                    placeholder: "Sélectionnez un site",
                    create: false,
                    plugins: {
                        dropdown_input: {},
                    },
                });

                // Mise à jour de la valeur sélectionnée
                tom.on("change", (value) => {
                    // Vérifie si value existe déjà dans un autre index
                    const isDuplicate = this.form.sites.some(
                        (site, i) => site.site_id === value && i !== index
                    );

                    if (isDuplicate) {
                        this.removeSite(index);
                    } else {
                        // Sinon, mets à jour normalement
                        this.form.sites[index].site_id = value;
                        console.log(JSON.stringify(this.form.sites));
                    }
                });

                // Pré-remplir si la valeur est valide
                const selected = this.form.sites[index].site_id;
                if (
                    selected &&
                    options.some((opt) => opt.value === String(selected))
                ) {
                    tom.setValue(String(selected));
                }
                this.tomInstances[index] = tom;
            });
        },
    },

    computed: {
        allSectors() {
            return this.sectors;
        },
        allSites() {
            return this.sites;
        },

        allSchedules() {
            if (this.searchStatus) {
                return this.schedules.filter((el) => {
                    return this.status(el) === this.searchStatus;
                });
            }
            if (this.search) {
                return this.schedules.filter((el) => {
                    return el.agent.fullname
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            }
            return this.schedules;
        },

        allReports() {
            return this.reports;
        },

        status() {
            return (st) => {
                const scheduleDate = this.parseDateFR(st.date); // st.date au format "DD/MM/YYYY"
                if (this.today >= scheduleDate && st.presences.length === 0) {
                    return "Non effectuée";
                } else if (
                    st.presences.length === 0 &&
                    this.today <= scheduleDate
                ) {
                    return "En attente";
                } else if (st.sites.length !== st.presences.length) {
                    return "Partielle";
                } else {
                    return "Effectuée";
                }
            };
        },

        totalElements() {
            return this.selectedReport.presences.reduce((total, presence) => {
                return (
                    total +
                    (presence.elements.length > 0
                        ? presence.elements.length
                        : 1)
                );
            }, 0);
        },

        colored() {
            return (note) => {
                if (note === "B") {
                    return "text-success";
                } else if (note === "P") {
                    return "text-pending";
                } else {
                    return "text-danger";
                }
            };
        },
    },

    watch: {
        form: {
            handler() {
                this.$nextTick(() => {
                    this.initTomSelects();
                });
            },
            deep: true,
        },
        allSites() {
            this.$nextTick(() => {
                this.initTomSelects();
            });
        },
    },
});
