import { get, post } from "../modules/http.js";
new Vue({
    el: "#Auth",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            pristine: null,
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        document.getElementById("loader").style.display = "none";

        //init pristine
        this.pristine = new Pristine(document.querySelector(".login-form"), {
            classTo: "input-form",
            errorClass: "border-red-500",
            errorTextParent: "input-form",
            errorTextClass: "text-danger mt-2",
        });
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
    },
});
