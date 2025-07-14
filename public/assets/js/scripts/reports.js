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
            selectedPatrol: null,
            sites: [],
            reports: [],
            filter_site: "",
            filter_date: "",
            closed_id: "",
            selectedPatrol: null,
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
        this.viewAllSites();
        this.viewAllReports();
    },

    methods: {
        closePatrol(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir clôturer cette patrouille ??`,
                icon: "question",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.closed_id = data.id;
                    postJson("/patrol.close", {
                        patrol_id: data.id,
                    }).then(() => {
                        self.closed_id = "";
                        self.viewAllReports();
                    });
                }
            });
        },

        viewAllSites() {
            get("/sites")
                .then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => console.log("error"));
        },

        viewAllReports() {
            this.isDataLoading = true;
            get(
                `/patrols.reports?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.reports = res.data.patrols.data;

                    this.pagination = {
                        current_page: res.data.patrols.current_page,
                        last_page: res.data.patrols.last_page,
                        total: res.data.patrols.total,
                        per_page: res.data.patrols.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                })
                .finally(() => {
                    this.isDataLoading = false;
                });
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.viewAllReports();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewAllReports();
        },

        loadChart(item) {
            this.selectedPatrol = item;

            if ($("#report-donut-chart").length) {
                console.log("chart");

                let colors = () => [
                    getColor("primary", 0.9), // Scanned
                    getColor("warning", 0.9), // Not scanned
                ];

                const scanned = item.zones_scanned;
                const notScanned = item.zones_expected - scanned;
                const efficiency = Math.round(item.efficiency_score); // arrondi

                let ctx = $("#report-donut-chart")[0].getContext("2d");

                // Détruire l'ancien graphique si nécessaire
                if (window.patrolChart) {
                    window.patrolChart.destroy();
                }

                window.patrolChart = new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        labels: ["Zones scannées", "Non scannées"],
                        datasets: [
                            {
                                data: [
                                    scanned,
                                    notScanned > 0 ? notScanned : 0,
                                ],
                                backgroundColor: colors,
                                hoverBackgroundColor: colors,
                                borderWidth: 5,
                                borderColor: () =>
                                    $("html").hasClass("dark")
                                        ? getColor("darkmode.700")
                                        : getColor("white"),
                            },
                        ],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return (
                                            context.label +
                                            ": " +
                                            context.parsed +
                                            " zones"
                                        );
                                    },
                                },
                            },
                        },
                        cutout: "80%",
                    },
                });

                // Mise à jour du texte au centre du donut
                $(".donut-score").text(`${efficiency}%`);
            }
        },

        downloadPatrolPDF() {
            location.href = "/pdf.patrols.reports";
        },
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allPatrolReports() {
            if (this.filter_site || this.filter_date) {
                return this.reports.filter((el) => {
                    /**
                        if (this.filter_site && this.filter_date) {
                            return el.site_id === this.filter_site && el.started_at.includes(this.filter_date);
                        }
                     * */
                    if (this.filter_site) {
                        // Si seul le site est défini, filtrer par site
                        return el.site_id === this.filter_site;
                    } else if (this.filter_date) {
                        const [year, month, day] = this.filter_date.split("-");
                        // Formater en JJ/MM/AAAA
                        const formattedDate = `${day}/${month}/${year}`;
                        // Si seule la date est définie, filtrer par date
                        return el.started_at.includes(formattedDate);
                    }
                });
            } else {
                // Si aucun filtre n'est défini, retourner tous les rapports
                return this.reports;
            }
        },
    },
});
