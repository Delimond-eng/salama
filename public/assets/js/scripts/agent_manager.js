import { get, post, postJson } from "../modules/http.js";
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
            preview: null,
            isLoading: false,
            isDataLoading: false,
            pristine: null,
            agents: [],
            histories: [],
            groups: [],
            sites: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            search: "",
            showComment: null,
            delete_id: "",
            filter_status: "",
            filter_date: "",
            filter_site: "",
            form: {
                id: "",
                matricule: "",
                fullname: "",
                password: "",
                site_id: "",
                groupe_id: "",
                role: "",
                status: "permenant",
            },
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }
        //init pristine script
        if (document.querySelector(".form-agent") !== null) {
            this.pristine = new Pristine(
                document.querySelector(".form-agent"),
                {
                    classTo: "input-form",
                    errorClass: "has-error",
                    errorTextParent: "input-form",
                    errorTextClass: "text-danger mt-2",
                }
            );
        }
        this.viewAllSites();
        if (location.pathname === "/agents.history") {
            this.viewAllHistories();
        } else {
            this.viewAllAgents();
            this.viewAllGroups();
        }
    },

    methods: {
        pickExcelFile() {
            this.$refs.excelInput.click();
        },
        handleExcelFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            console.log("Fichier sélectionné :", file.name);

            const formData = new FormData();
            formData.append("file", file);

            this.isLoading = true;
            post("/agents.import.excel", formData).then(({ status, data }) => {
                this.isLoading = false;
                if (data.status === "success") {
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
                    this.viewAllAgents();
                }
            });
            // Facultatif : Lire le fichier avec FileReader (en binaire pour le traitement Excel)
            /* const reader = new FileReader();
            reader.onload = (e) => {
                const data = e.target.result;

                // Exemple avec SheetJS si tu veux lire les données :
                // const workbook = XLSX.read(data, { type: 'binary' });
                // const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                // const jsonData = XLSX.utils.sheet_to_json(firstSheet);
                // console.log(jsonData);
            };
            reader.readAsBinaryString(file); */
        },
        createAgent(event) {
            const isValid = this.pristine.validate();
            if (isValid) {
                const url = event.target.getAttribute("action");
                this.isLoading = true;
                if (this.form.role === "supervisor") {
                    this.form.site_id = "";
                }

                const formData = new FormData();
                formData.append("id", this.form.id);
                formData.append("matricule", this.form.matricule);
                formData.append("fullname", this.form.fullname);
                formData.append("password", this.form.password);
                formData.append("site_id", this.form.site_id);
                formData.append("groupe_id", this.form.groupe_id);
                formData.append("role", this.form.role);
                formData.append("photo", this.$refs.photoInput.files[0]);
                post(url, formData)
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
                            console.log(data.result);
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
                            this.viewAllAgents();
                            if (document.querySelector("#btn-reset") !== null) {
                                document.querySelector("#btn-reset").click();
                            }
                            // clean fields
                            this.reset();
                        }
                    })
                    .catch((err) => {
                        this.isLoading = false;
                        this.error = err;
                    });
            }
        },

        exportToExcel() {
            const data = this.allAgents.map((p) => ({
                "Nom complet": p.fullname || "",
                Matricule: p.matricule || "",
                Role: p.role || "",
                Site: p.site.name || "",
                Password: p.password || "",
                Date: p.created_at.substring(0, 10) || "",
            }));

            const worksheet = XLSX.utils.json_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Agents");

            XLSX.writeFile(workbook, "agents.xlsx");
        },

        reset() {
            this.form = {
                matricule: "",
                fullname: "",
                password: "",
                site_id: "",
                groupe_id: "",
                role: "",
            };
            this.preview = null;
        },

        editAgent(data) {
            this.form = {
                id: data.id,
                matricule: data.matricule,
                fullname: data.fullname,
                password: data.password,
                site_id: data.site_id,
                groupe_id: data.groupe_id,
                role: data.role,
                status: data.status,
            };
            this.preview = data.photo ?? null;
        },

        deleteAgent(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer l'agent ${data.matricule}`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "agents",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllAgents();
                    });
                }
            });
        },

        viewAllAgents() {
            this.isDataLoading = true;
            get(
                `/agents?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&status=${this.filter_status}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.agents = res.data.agents.data;
                    this.pagination = {
                        current_page: res.data.agents.current_page,
                        last_page: res.data.agents.last_page,
                        total: res.data.agents.total,
                        per_page: res.data.agents.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },

        viewAllHistories() {
            this.isDataLoading = true;
            get(
                `/agents.histories?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&date=${this.filter_date}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.histories = res.data.histories.data;
                    this.pagination = {
                        current_page: res.data.histories.current_page,
                        last_page: res.data.histories.last_page,
                        total: res.data.histories.total,
                        per_page: res.data.histories.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },

        viewAllSites() {
            this.isDataLoading = true;
            get(`/sites`)
                .then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => {
                    console.log("error");
                });
        },

        changePage(page) {
            this.pagination.current_page = page;
            if (location.pathname === "/agents.history") {
                this.viewAllHistories();
            } else {
                this.viewAllAgents();
            }
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            if (location.pathname === "/agents.history") {
                this.viewAllHistories();
            } else {
                this.viewAllAgents();
            }
        },

        viewAllGroups() {
            this.isDataLoading = true;
            get(`/groups?all=1`)
                .then((res) => {
                    this.isDataLoading = false;
                    this.groups = res.data.groups;
                })
                .catch((err) => console.log("error"));
        },

        onFileChange(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                alert("Veuillez sélectionner une image valide.");
            }
        },

        removePhoto() {
            this.preview = null;
            this.$refs.photoInput.value = null;
        },
    },

    computed: {
        allAgents() {
            if (this.search && this.search.trim()) {
                return this.agents.filter(
                    (el) =>
                        el.fullname
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        el.matricule
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                );
            } else {
                return this.agents;
            }
        },

        allHistories() {
            if (this.search && this.search.trim()) {
                return this.histories.filter(
                    (el) =>
                        el.agent.fullname
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        el.agent.matricule
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                );
            } else if (this.filter_site) {
                return this.histories.filter(
                    (el) =>
                        el.site_id === this.filter_site ||
                        el.site_provenance_id === this.filter_site
                );
            } else {
                return this.histories;
            }
        },

        getRole() {
            return (role) => {
                if (role === "guard") {
                    return "Gardien";
                } else {
                    return "Superviseur";
                }
            };
        },
        allGroups() {
            return this.groups;
        },
        allSites() {
            return this.sites;
        },
    },
});
