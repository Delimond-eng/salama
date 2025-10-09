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
            sites: [],
            reports: [],
            filter_site: "",
            filter_date: "",
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
        };
    },

    watch: {
        allSites() {
            const options = [
                { value: "", text: "Tous les agents" },
                ...this.allSites.map((site) => ({
                    value: String(site.id),
                    text: site.name,
                })),
            ];
            if ($(".select-site").length) {
                const tom = new TomSelect(".select-site", {
                    plugins: {
                        dropdown_input: {},
                    },
                    create: false,
                    placeholder: "Filtrez par site",
                    options: options,
                });

                tom.on("change", (value) => {
                    this.filter_date = "";
                    this.pagination.current_page = 1;
                    this.filter_site = value;
                    this.viewAllReports();
                });
            }
        },
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
            get(
                `/ronde.reports?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&site=${this.filter_site}&date=${this.filter_date}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.reports = res.data.rondes.data;

                    this.pagination = {
                        current_page: res.data.rondes.current_page,
                        last_page: res.data.rondes.last_page,
                        total: res.data.rondes.total,
                        per_page: res.data.rondes.per_page,
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
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allReports() {
            /* if (this.filter_site || this.filter_date) {
                return this.reports.filter((el) => {
                    if (this.filter_site) {
                        return el.site_id === this.filter_site;
                    } else if (this.filter_date) {
                        const [year, month, day] = this.filter_date.split("-");
                        const formattedDate = `${day}/${month}/${year}`;
                        return el.started_at.includes(formattedDate);
                    }
                });
            } else {
               
            } */
            return this.reports;
        },
    },
});
