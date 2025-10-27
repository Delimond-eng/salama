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
            isLoading: false,
            isDataLoading: false,
            pristine: null,
            users: [],
            search: "",
            form: {
                name: "",
                email: "",
                password: "",
                role: "",
                permissions: [],
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
                    console.log(value);
                });
            });
        }

        this.viewAllUsers();
    },

    methods: {
        login(event) {
            const isValid = this.pristine.validate();
            console.log(isValid);
            if (isValid) {
                const formData = new FormData(event.target);
                const url = event.target.getAttribute("action");
                this.isLoading = true;

                post(url, formData)
                    .then(({ data, status }) => {
                        console.log(data, status);
                        this.isLoading = false;
                        // Gestion des erreurs
                        if (data.errors !== undefined) {
                            this.error = data.errors;
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
                        }
                        if (data.result) {
                            console.log(data.result);
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

                            // Rediriger l'utilisateur
                            window.location.href = data.result.redirect;
                        }
                    })
                    .catch((err) => {
                        this.isLoading = false;
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
                    });
            }
        },

        togglePermission(event) {
            const checkbox = event.target;
            const permissionId = parseInt(checkbox.value);

            if (checkbox.checked) {
                // Ajouter la permission si elle n'existe pas
                if (!this.form.permissions.some((p) => p.id === permissionId)) {
                    this.form.permissions.push({ id: permissionId });
                }
            } else {
                // Retirer la permission si décochée
                this.form.permissions = this.form.permissions.filter(
                    (p) => p.id !== permissionId
                );
            }
            console.log(JSON.stringify(this.form.permissions, null, 2));
        },

        createUser(event) {
            const formData = this.form;
            this.isLoading = true;
            postJson("/user.create", formData)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    // Gestion des erreurs
                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
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

                        // clean fields
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
                name: "",
                email: "",
                password: "",
                role: "",
                permissions: [],
            };
        },

        viewAllUsers() {
            this.isDataLoading = true;
            get(
                `/users.all?page=${this.pagination.current_page}&per_page=${this.pagination.per_page}`
            )
                .then((res) => {
                    this.isDataLoading = false;
                    this.users = res.data.users.data;
                    this.pagination = {
                        current_page: res.data.users.current_page,
                        last_page: res.data.users.last_page,
                        total: res.data.users.total,
                        per_page: res.data.users.per_page,
                    };
                })
                .catch((err) => {
                    console.log("error");
                });
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.viewAllUsers();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewAllUsers();
        },
    },

    computed: {
        allUsers() {
            if (this.search && this.search.trim()) {
                return this.users.filter(
                    (el) =>
                        el.name
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        el.email
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                );
            } else {
                return this.users;
            }
        },
    },
});
