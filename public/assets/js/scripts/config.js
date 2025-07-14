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
            sectors: [],
            elements: [],
            delete_id: "",
            form: {
                id: "",
                libelle: "",
            },
            search: "",
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
        let path = location.pathname;
        switch (path) {
            case "/secteurs":
                this.viewAllSectors();
                break;
            case "/elements":
                this.viewAllElements();
                break;
            default:
                break;
        }
    },
    methods: {
        viewAllSectors() {
            this.isDataLoading = true;
            let url = `/secteurs.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`;
            get(url)
                .then((res) => {
                    this.isDataLoading = false;
                    this.sectors = res.data.sectors.data;
                    this.pagination = {
                        current_page: res.data.sectors.current_page,
                        last_page: res.data.sectors.last_page,
                        total: res.data.sectors.total,
                        per_page: res.data.sectors.per_page,
                    };
                })
                .catch((err) => console.log("error"));
        },

        viewAllElements() {
            this.isDataLoading = true;
            let url = `/elements.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`;
            get(url)
                .then((res) => {
                    this.isDataLoading = false;
                    this.elements = res.data.elements.data;
                    this.pagination = {
                        current_page: res.data.elements.current_page,
                        last_page: res.data.elements.last_page,
                        total: res.data.elements.total,
                        per_page: res.data.elements.per_page,
                    };
                })
                .catch((err) => console.log("error"));
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.paginate();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.paginate();
        },

        paginate() {
            let path = location.pathname;
            switch (path) {
                case "/secteurs":
                    this.viewAllSectors();
                    break;
                case "/elements":
                    this.viewAllElements();
                    break;
                default:
                    break;
            }
        },

        reset() {
            this.form = {
                id: "",
                libelle: "",
                description: "",
            };
        },

        createSector(event) {
            const url = "/secteur.create";
            this.isLoading = true;
            delete this.form.description;
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

                        this.viewAllSectors();
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
        },

        createElement(event) {
            const url = "/element.create";
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

                        this.viewAllElements();
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
        },

        deleteSector(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement ce secteur ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "secteurs",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllSectors();
                    });
                }
            });
        },

        deleteElement(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement cet élément de la supervision ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "supervision_control_elements",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllElements();
                    });
                }
            });
        },
    },

    computed: {
        allSectors() {
            return this.sectors;
        },
        allElements() {
            return this.elements;
        },
    },
});
