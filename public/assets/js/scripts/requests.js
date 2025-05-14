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
            requests: [],
            selectedRequest: null,
            signalements: [],
            selectedSignalement: null,
            sites: [],
            filter_date: "",
            search: "",
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }
        this.viewAllSites();
        this.viewAllRequests();
        this.viewAllSignalements();
    },

    methods: {
        viewAllSites() {
            get("/sites")
                .then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => console.log("error"));
        },
        viewAllRequests() {
            this.isDataLoading = true;
            get("/requests.all")
                .then((res) => {
                    this.isDataLoading = false;
                    this.requests = res.data.requests;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },

        viewAllSignalements() {
            get("/signalements.all")
                .then((res) => {
                    this.signalements = res.data.signalements;
                })
                .catch((err) => console.log("error"));
        },
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allRequests() {
            if (this.filter_date || this.search) {
                if (this.filter_date) {
                    return this.requests.filter((el) => {
                        const [year, month, day] = this.filter_date.split("-");
                        const formattedDate = `${day}/${month}/${year}`;
                        return el.created_at.includes(formattedDate);
                    });
                } else if (this.search) {
                    return this.requests.filter((el) => {
                        return (
                            el.agent.fullname
                                .toLowerCase()
                                .includes(this.search.toLowerCase()) ||
                            el.agent.matricule
                                .toLowerCase()
                                .includes(this.search.toLowerCase())
                        );
                    });
                }
            } else {
                return this.requests;
            }
        },

        allSignalements() {
            return this.signalements;
        },
    },
});
