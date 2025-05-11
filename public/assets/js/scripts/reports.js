import {get, postJson } from "../modules/http.js";
new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            pristine: null,
            sites: [],
            reports:[],
            filter_site:'',
            filter_date:'',
            selectedPatrol:null
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        document.getElementById('loader').style.display = 'none';
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
            get("/patrols.reports")
                .then((res) => {
                    this.reports = res.data.patrols;
                })
                .catch((err) => console.log("error"));
        },
    },


    computed: {
        allSites() {
            return this.sites;
        },

        allReports() {
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
                        const [year, month, day] = this.filter_date.split('-');
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
        }

    }
});
