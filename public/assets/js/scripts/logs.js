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
            phone_logs: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            filter_date: "",
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }

        this.viewPhoneLogs();
    },

    methods: {
        viewPhoneLogs() {
            this.isDataLoading = true;
            get(
                `/logs.phones?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&date=${this.filter_date}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.phone_logs = res.data.logs.data;
                    this.pagination = {
                        current_page: res.data.logs.current_page,
                        last_page: res.data.logs.last_page,
                        total: res.data.logs.total,
                        per_page: res.data.logs.per_page,
                    };
                })
                .catch((err) => {
                    console.log("error");
                });
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.viewPhoneLogs();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewPhoneLogs();
        },
    },

    computed: {
        phoneLogs() {
            return this.phone_logs;
        },
    },
});
