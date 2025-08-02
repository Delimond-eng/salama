import { get, postJson, post } from "../modules/http.js";
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
            presenceReports: [],
            daysInMonth: 30,
            currentMonth: new Date().getMonth() + 1,
            currentYear: new Date().getFullYear(),
            horaires: [],
            weeklyPlannings: [],
            jours: [
                "lundi",
                "mardi",
                "mercredi",
                "jeudi",
                "vendredi",
                "samedi",
                "dimanche",
            ],
            offset: 0,
            sites: [],
            groups: [],
            delete_id: "",
            agentsData: [],
            filteredAgents: [],
            searchMatricule: "",
            searchName: "",
            filterStatus: "",
            filterRetards: "",
            years: [2023, 2024, 2025],
            activeRowIndex: null,
            filter_site_id: "",
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
        if (location.pathname == "/presence.plannings") {
            this.viewAllSites();
            this.viewWeeklyPlannings();
        } else {
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

            this.loadPresenceReports();
            this.refreshDatas();
        }
    },
    watch: {
        searchMatricule: "applyFilters",
        searchName: "applyFilters",
        filterStatus: "applyFilters",
        filterRetards: "applyFilters",
        allSites() {
            const options = [
                { value: "", text: "Tous les agents" },
                ...this.allSites.map((site) => ({
                    value: String(site.id),
                    text: site.name,
                })),
            ];
            const tom = new TomSelect(".select-site", {
                plugins: {
                    dropdown_input: {},
                },
                create: false,
                placeholder: "Filtrez par site",
                options: options,
            });

            tom.on("change", (value) => {
                this.filter_site_id = value;
                this.viewWeeklyPlannings();
            });
        },
    },
    methods: {
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

        viewWeeklyPlannings() {
            this.isDataLoading = true;
            get(
                `/weekly.plannings?offset=${this.offset}&site=${this.filter_site_id}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.weeklyPlannings = res.data;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },

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

        refreshDatas() {
            this.viewAllHoraires();
            this.viewAllGroups();
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
        loadPresenceReports() {
            this.isDataLoading = true;

            get(
                `/presences.report?month=${this.currentMonth}&year=${this.currentYear}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.agentsData = res.data.data;
                    this.daysInMonth = res.data.daysInMonth;
                    this.applyFilters();
                })
                .catch(console.error);
        },
        applyFilters() {
            this.filteredAgents = this.agentsData.filter((agent) => {
                const matchMatricule = agent.matricule
                    .toLowerCase()
                    .includes(this.searchMatricule.toLowerCase());
                const matchName = agent.fullname
                    .toLowerCase()
                    .includes(this.searchName.toLowerCase());

                let matchStatus = true;
                switch (this.filterStatus) {
                    case "deces":
                        matchStatus = agent.stats.d > 0;
                        break;
                    case "demission":
                        matchStatus = agent.stats.dm > 0;
                        break;
                    case "licenciement":
                        matchStatus = agent.stats.l > 0;
                        break;
                    case "conge":
                        matchStatus = agent.stats.c > 0 || agent.stats.m > 0;
                        break;
                    case "mise_a_pied":
                        matchStatus = agent.stats.mp > 0;
                        break;
                    case "absence_autorisee":
                        matchStatus = agent.stats.au > 0;
                        break;
                    case "deserteur":
                        matchStatus = agent.stats.ds > 0;
                        break;
                    case "absences":
                        matchStatus = agent.stats.a > 0;
                        break;
                }

                let matchRetards = true;
                if (this.filterRetards === "moins3") {
                    matchRetards = agent.stats.c1 < 3;
                } else if (this.filterRetards === "plus3") {
                    matchRetards = agent.stats.c1 >= 3;
                }

                return (
                    matchMatricule && matchName && matchStatus && matchRetards
                );
            });
        },

        separerLettresEtChiffres(chaine) {
            return chaine.replace(/([A-Za-z]+)(\d+)/, "$1 $2");
        },
        exportToExcel() {
            const moisNom = this.currentMonthName || this.currentMonth;
            const titre = `POINTAGES MENSUELS DES AGENTS - ${moisNom} ${this.currentYear}`;
            const legende1 = [
                "PP = Présences",
                "A = Absences",
                "M = Maladies",
                "C = Congés",
                "MP = Mises à pied",
                "AU = Absences autorisées",
            ];
            const legende2 = [
                "C1 = Retards",
                "A1/A2/A3 = Appels",
                "CA1/2/3 = Retards + Appels",
            ];

            const headers = ["#", "Matricule", "Nom", "Poste"];
            for (let day = 1; day <= this.daysInMonth; day++)
                headers.push(day.toString());
            headers.push("PP", "A", "M", "C", "MP", "AU", "C1", "A1", "CA1");

            const data = [];

            // Ajout du contenu des agents
            this.filteredAgents.forEach((agent, index) => {
                const row = [
                    index + 1,
                    this.separerLettresEtChiffres(agent.matricule),
                    agent.fullname,
                    agent.poste,
                ];
                for (let day = 1; day <= this.daysInMonth; day++) {
                    row.push(agent.days[day] || "");
                }

                const stats = agent.stats;
                const keys = [
                    "pp",
                    "a",
                    "m",
                    "c",
                    "mp",
                    "au",
                    "c1",
                    "a1",
                    "ca1",
                ];
                keys.forEach((k) => row.push(stats[k]));
                data.push(row);
            });

            // Structure complète de la feuille
            const sheetData = [
                [titre],
                [], // Ligne vide
                legende1,
                legende2,
                [], // Ligne vide
                headers,
                ...data,
            ];

            const ws = XLSX.utils.aoa_to_sheet(sheetData);

            // Définir la largeur des colonnes
            const colWidths = headers.map((h, i) => {
                if (i <= 3) return { wch: 20 };
                if (i > 3 && i <= 3 + this.daysInMonth) return { wpx: 34 }; // Colonnes jour très fines
                return { wch: 10 };
            });
            ws["!cols"] = colWidths;

            // Fusionner la cellule de titre sur toute la largeur
            const totalCols = headers.length;
            ws["!merges"] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: totalCols - 1 } },
            ];

            // Appliquer des styles basiques (nécessite SheetJS Pro pour les styles avancés)
            const headerRowIdx = 5;
            headers.forEach((_, i) => {
                const cell =
                    ws[XLSX.utils.encode_cell({ r: headerRowIdx, c: i })];
                if (cell) {
                    cell.s = {
                        fill: { fgColor: { rgb: "8E1926" } }, // Rouge bordeaux
                        font: { bold: true, color: { rgb: "FFFFFF" } },
                        alignment: { horizontal: "center" },
                    };
                }
            });

            // Création du fichier
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Pointages");
            XLSX.writeFile(wb, `Pointages_${moisNom}_${this.currentYear}.xlsx`);
        },

        onRowClick(index) {
            this.activeRowIndex = index;
        },
        changeMonthYear(month, year) {
            this.currentMonth = month;
            this.currentYear = year;
            this.loadPresenceReports();
        },
        renderTable() {
            const tbody = document.querySelector("table tbody");
            tbody.innerHTML = "";

            this.presenceReports.forEach((agent, index) => {
                const tr = document.createElement("tr");

                // Colonnes fixes
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${agent.matricule}</td>
                    <td>${agent.fullname}</td>
                    <td>${agent.poste}</td>
                `;

                // Colonnes jour par jour
                for (let day = 1; day <= this.daysInMonth; day++) {
                    const code = agent.days[day] || "";
                    tr.innerHTML += `<td>${code}</td>`;
                }

                // Colonnes totaux
                const stats = agent.stats;
                const keys = [
                    "pp",
                    "a",
                    "m",
                    "c",
                    "mp",
                    "au",
                    "c1",
                    "a1",
                    "ca1",
                    "l",
                    "d",
                    "dm",
                    "ds",
                ];
                keys.forEach((key) => {
                    tr.innerHTML += `<td>${stats[key]}</td>`;
                });

                tbody.appendChild(tr);
            });
        },

        // Pour usage futur : filtrer par mois/année via input ou sélecteur
        setMonthYear(mois, annee) {
            this.currentMonth = mois;
            this.currentYear = annee;
            this.loadPresenceReports();
        },

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
            post("/import.planning.excel", formData).then(
                ({ status, data }) => {
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
                        this.viewWeeklyPlannings();
                    }
                }
            );
        },

        getJourName(dateStr) {
            const date = new Date(dateStr);
            return date
                .toLocaleDateString("fr-FR", { weekday: "long" })
                .toLowerCase();
        },

        mapPlanningsByDay(plannings) {
            const map = {};
            for (let p of plannings) {
                const jour = this.getJourName(p.date);
                map[jour] = p;
            }
            return map;
        },

        formatHoraire(planning) {
            if (!planning) return "-";
            if (planning.is_rest_day || !planning.horaire) return "OFF";
            return `${planning.horaire.started_at} - ${planning.horaire.ended_at}`;
        },
    },

    computed: {
        months() {
            return Array.from({ length: 12 }, (v, i) => {
                const date = new Date(this.currentYear, i, 1); // Une année quelconque
                const monthName = date.toLocaleString("fr-FR", {
                    month: "long",
                }); // Nom du mois en français
                return {
                    id: i + 1,
                    libelle:
                        monthName.charAt(0).toUpperCase() + monthName.slice(1), // Majuscule initiale
                };
            });
        },
        allSites() {
            return this.sites;
        },
        displayAgents() {
            return this.filteredAgents;
        },
        currentMonthName() {
            const months = [
                "Janvier",
                "Février",
                "Mars",
                "Avril",
                "Mai",
                "Juin",
                "Juillet",
                "Août",
                "Septembre",
                "Octobre",
                "Novembre",
                "Décembre",
            ];
            return months[this.currentMonth - 1];
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

        plannings() {
            return this.weeklyPlannings;
        },
    },
});
