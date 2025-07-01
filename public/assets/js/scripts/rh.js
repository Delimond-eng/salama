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
            errorA: null,
            result: null,
            isLoading: false,
            isDataLoading: false,
            pristine: null,
            conges: [],
            cessations: [],
            search: "",
            form: {
                agent_id: "",
                type: "",
                date: "",
                cause: "",
                type_conge: "",
                date_debut: "",
                date_fin: "",
                motif: "",
            },
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

        if ($(".login-form").length) {
            //init pristine
            this.pristine = new Pristine(
                document.querySelector(".login-form"),
                {
                    classTo: "input-form",
                    errorClass: "border-red-500",
                    errorTextParent: "input-form",
                    errorTextClass: "text-danger mt-2",
                }
            );
        }

        if ($(".tom-select").length) {
            const self = this;
            $(".tom-select").each(function () {
                const tom = new TomSelect(this, {
                    plugins: {
                        dropdown_input: {},
                    },
                    create: false,
                    placeholder: "Sélectionnez un agent",
                });

                tom.on("change", function (value) {
                    this.form.agent_id = value;
                });
            });
        }

        this.refreshDatas();
    },

    methods: {
        createConge(event) {
            this.isLoading = true;
            const formData = new FormData();
            formData.append("agent_id", this.form.agent_id);
            formData.append("type", this.form.type_conge);
            formData.append("date_debut", this.form.date_debut);
            formData.append("date_fin", this.form.date_fin);
            formData.append("motif", this.form.motif);

            post("/conge.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;

                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
                        this.errorA = data.errors.toString();
                        setTimeout(() => {
                            new Toastify({
                                node: $("#failed-notification-content")
                                    .clone()
                                    .removeClass("hidden")[0],
                                duration: 3000,
                                newWindow: true,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true,
                            }).showToast();
                        }, 100);
                    }
                    if (data.result) {
                        this.error = null;
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
                        this.viewAllConges();
                        this.reset();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        viewAllCessations() {
            this.isDataLoading = true;
            get(
                `/cessations?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&status=${this.filter_status}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.cessations = res.data.cessations.data;
                    // console.log(this.cessations);
                    this.pagination = {
                        current_page: res.data.cessations.current_page,
                        last_page: res.data.cessations.last_page,
                        total: res.data.cessations.total,
                        per_page: res.data.cessations.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log(err);
                });
        },

        viewAllConges() {
            this.isDataLoading = true;
            get(
                `/conges?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}&status=${this.filter_status}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.conges = res.data.conges.data;
                    this.pagination = {
                        current_page: res.data.conges.current_page,
                        last_page: res.data.conges.last_page,
                        total: res.data.conges.total,
                        per_page: res.data.conges.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log(err);
                });
        },

        createCessation(event) {
            this.isLoading = true;
            const formData = new FormData();
            formData.append("agent_id", this.form.agent_id);
            formData.append("type", this.form.type);
            formData.append("date", this.form.date);
            formData.append("cause", this.form.cause);

            post("/cessation.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;

                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
                        this.errorA = data.errors.toString();
                        setTimeout(() => {
                            new Toastify({
                                node: $("#failed-notification-content")
                                    .clone()
                                    .removeClass("hidden")[0],
                                duration: 3000,
                                newWindow: true,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true,
                            }).showToast();
                        }, 100);
                    }

                    if (data.result) {
                        this.error = null;
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
                        this.viewAllCessations();
                        this.reset();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                });
        },

        reset() {
            this.form = {
                agent_id: "",
                type: "",
                date: "",
                cause: "",
                type_conge: "",
                date_debut: "",
                date_fin: "",
                motif: "",
            };
            if ($(".tom-select").length) {
                $(".tom-select").each(function () {
                    const tom = $(this).data("tom");
                    if (tom) {
                        tom.clear();
                    }
                });
            }
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.refreshDatas();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.refreshDatas();
        },

        refreshDatas() {
            if (location.pathname === "/ldd.management") {
                this.viewAllCessations();
            } else {
                this.viewAllConges();
            }
        },
    },

    computed: {
        allCessations() {
            if (this.search && this.search.trim()) {
                return this.cessations.filter(
                    (el) =>
                        el.type
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        el.fullname
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                );
            } else {
                return this.cessations;
            }
        },
        allConges() {
            if (this.search && this.search.trim()) {
                return this.conges.filter(
                    (el) =>
                        el.type
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        el.fullname
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                );
            } else {
                return this.conges;
            }
        },
    },
});
