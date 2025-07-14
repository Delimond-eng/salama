import { get, postJson } from "../modules/http.js";
new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isDataLoading: false,
            isLoading: false,
            pristine: null,
            reports: [],
            tasks: [],
            filter_site: "",
            filter_date: "",
            delete_id: "",
            sites: [],
            search: "",
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }
        if ($(".form-task").length) {
            this.pristine = new Pristine(document.querySelector(".form-task"), {
                classTo: "input-form",
                errorClass: "has-error",
                errorTextParent: "input-form",
                errorTextClass: "text-danger mt-2",
            });
        }

        this.viewAllSites();
    },

    methods: {
        viewAllSites() {
            this.isDataLoading = true;
            get("/sites")
                .then((res) => {
                    this.isDataLoading = false;
                    this.sites = res.data.sites;
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },
    },

    computed: {
        allSites() {
            return this.sites;
        },

        allTasks() {
            return this.tasks;
        },
        allTasksReports() {
            return this.reports;
        },
    },
});
