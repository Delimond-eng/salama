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
            requests: [],
            selectedRequest: null,
            signalements: [],
            selectedSignalement: null,
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            sites: [],
            filter_date: "",
            delete_id: "",
            site_id: "",
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
            this.isDataLoading = true;
            get(
                `/signalements.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&siteId=${this.site_id}&date=${this.filter_date}`
            )
                .then(({ data, status }) => {
                    this.isDataLoading = false;
                    this.signalements = data.signalements.data;
                    this.pagination = {
                        current_page: data.signalements.current_page,
                        last_page: data.signalements.last_page,
                        total: data.signalements.total,
                        per_page: data.signalements.per_page,
                    };
                })
                .catch((err) => console.log("error"));
        },

        deleteSignt(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement ce signalement ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "signalements",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllSignalements();
                    });
                }
            });
        },
        changePage(page) {
            this.pagination.current_page = page;
            this.viewAllSignalements();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewAllSignalements();
        },
        isVideo(mediaUrl) {
            const videoExtensions = ["mp4", "webm", "ogg", "mov"];
            const extension = mediaUrl.split(".").pop().toLowerCase();
            return videoExtensions.includes(extension);
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
