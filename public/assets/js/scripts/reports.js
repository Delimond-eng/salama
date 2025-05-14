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
            selectedPatrol: null,
            sites: [],
            reports: [],
            filter_site: "",
            filter_date: "",
            selectedPatrol: null,
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
        viewAllSites() {
            get("/sites")
                .then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => console.log("error"));
        },

        viewAllReports() {
            this.isDataLoading = true;
            get("/patrols.reports")
                .then((res) => {
                    this.isDataLoading = false;
                    this.reports = res.data.patrols.data;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
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
