import {get, postJson } from "../modules/http.js";
new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            pristine: null,
            agents: [],
            search: "",
            showComment: null,
            form: {
                id: "",
                matricule: "",
                fullname: "",
                password: "",
                site_id: "",
                role: "",
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
                document.querySelector(".form-agent"), {
                    classTo: "input-form",
                    errorClass: "has-error",
                    errorTextParent: "input-form",
                    errorTextClass: "text-danger mt-2",
                }
            );
        }
        this.viewAllAgents();
        this.viewAllHoraires()
    },

    methods: {
        createAgent(event) {
            const isValid = this.pristine.validate();
            if (isValid) {
                const url = event.target.getAttribute("action");
                this.isLoading = true;
                if (this.form.role === "supervisor") {
                    this.form.site_id = "";
                }
                postJson(url, this.form)
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
            const data = this.allAgents.map(p => ({
                "Nom complet": p.fullname || '',
                "Matricule": p.matricule || '',
                "Role": p.role || '',
                "Site": p.site.name || '',
                "Password": p.password || '',
                "Date": p.created_at.substring(0, 10) || ''
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
                horaire_id: "",
                role: "",
            };
        },

        viewAllAgents() {
            this.isDataLoading = true;
            get("/agents")
                .then((res) => {
                    this.isDataLoading = false;
                    this.agents = res.data.agents;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },
        viewAllHoraires() {
            this.isDataLoading = true;
            get("/horaires")
                .then((res) => {
                    this.isDataLoading = false;
                    this.horaires = res.data.horaires;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
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
        getRole() {
            return (role) => {
                if (role === "guard") {
                    return "Agent gardien";
                } else {
                    return "Superviseur";
                }
            };
        },
    },
});
