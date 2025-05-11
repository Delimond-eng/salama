import {get, postJson } from "../modules/http.js";
new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            pristine: null,
            schedules:[],

            form:{
                site_id:'',
                schedules:[
                    {
                        libelle:'',
                        start_time:'',
                        end_time:'',
                        site_id:'',
                    }
                ]
            },
            sites:[],
            search:""
        };
    },

    mounted() {
        // Une fois que Vue.js est chargé, on cache le loader
        document.getElementById('loader').style.display = 'none';
        this.pristine = new Pristine(document.querySelector(".form-planning"), {
            classTo: "input-form",
            errorClass: "has-error",
            errorTextParent: "input-form",
            errorTextClass: "text-danger mt-2"
        });
        this.viewAllSchedules()
        this.viewAllSites();
    },

    methods: {
        viewAllSites() {
            get("/sites").then((res) => {
                    this.sites = res.data.sites;
                })
                .catch((err) => console.log("error"));
        },
        viewAllSchedules() {
            get("/schedules.all").then((res) => {
                    this.schedules = res.data.schedules;
                })
                .catch((err) => console.log("error"));
        },

        addField(){
            this.form.schedules.push( {
                libelle:'',
                start_time:'',
                end_time:'',
                site_id:'',
            });
        },

        removeField(item){
            let index = this.form.schedules.indexOf(item);
            this.form.schedules.splice(index, 1);
        },

        reset(){
            this.form = {
                site_id:'',
                schedules:[
                    {
                        libelle:'',
                        start_time:'',
                        end_time:'',
                        site_id:'',
                    }
                ]
            }
            document.getElementById('btn-reset').click();
        },

        createSchedules(event){
            const isValid = this.pristine.validate();
            if (isValid) {
                const forms = [];
                const url = event.target.getAttribute("action");
                for(let field of this.form.schedules){
                    field.site_id = this.form.site_id;
                    forms.push(field);
                }
                this.isLoading = true;
                postJson(url, {schedules:forms})
                    .then(({ data, status }) => {
                        this.isLoading = false;
                        // Gestion des erreurs
                        if (data.errors !== undefined) {
                            this.error = data.errors.toString();
                            setTimeout(() => {
                                new Toastify({
                                    node: $("#failed-notification-content").clone().removeClass("hidden")[0],
                                    duration: 3000,
                                    newWindow: true,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    stopOnFocus: true
                                }).showToast();
                            }, 100)
                        }
                        if (data.result) {
                            this.error = null;
                            console.log(data.result);
                            this.result = data.result;
                            new Toastify({
                                node: $("#success-notification-content").clone().removeClass("hidden")[0],
                                duration: 3000,
                                newWindow: true,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true
                            }).showToast();
                            this.viewAllSchedules();
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
            }
        }
    },


    computed: {
        allSites() {
            return this.sites;
        },

        allSchedules(){
            if (this.search){
                return this.schedules.filter((el) => {
                    return el.site_id === this.search;
                });
            }
            else{
                return this.schedules;
            }

        }
    }
});
