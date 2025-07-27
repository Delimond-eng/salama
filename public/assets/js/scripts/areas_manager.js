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
            sites: [],
            filter_datep: "",
            selectedAreas: [],
            selectedPresence: null,
            search: "",
            presencesOriginal: [], // données brutes chargées depuis l'API
            search2: "",
            load_id: "",
            delete_id: "",
            openAccordion: null,
            presences: [],
            presenceDate: new Date().toISOString().slice(0, 10),
            selectedSiteId: null,
            selectedToggleId: null,
            isPresenceLoading: false,
            form: {
                id: "",
                name: "",
                code: "",
                adresse: "",
                phone: "",
                presence: "",
                emails: "",
                secteur_id: "",
                client_email: "",

                areas: [
                    {
                        libelle: "",
                    },
                ],
            },
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0,
                per_page: 10,
            },
            pagination1: {
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

        //init pristine
        this.viewAllSites();
    },

    methods: {
        createSite() {
            this.isLoading = true;
            const form = this.form;
            postJson("site.create", form)
                .then(({ data, status }) => {
                    this.isLoading = false;
                    // Gestion des erreurs
                    if (data.errors !== undefined) {
                        this.error = data.errors.toString();
                        return;
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
                        if (this.form.id !== "") {
                            this.viewAllSites();
                        }
                        // clean fields
                        this.reset();
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.error = err;
                    console.log(err);
                });
        },

        toggleAccordion(index) {
            this.openAccordion = this.openAccordion === index ? null : index;
        },

        triggerData(data) {
            this.selectedAreas = data.areas;
            this.form.id = data.id;
            this.form.code = data.code;
            this.form.name = data.name;
            this.form.secteur_id = data.secteur_id;
            this.form.client_email = data.client_email;
            this.form.adresse = data.adresse;
            this.form.phone = data.phone;
            this.form.emails = data.emails;
            this.form.presence = data.presence;
            this.error = null;
        },

        downloadQRCode(id) {
            location.href = `/loadpdf/${id}`;
        },

        openPhoto(url) {
            window.open(url, "_blank", "height=400,width=600");
        },

        reset() {
            this.form = {
                name: "",
                code: "",
                adresse: "",
                phone: "",
                secteur_id: "",
                client_email: "",
                areas: [
                    {
                        libelle: "",
                    },
                ],
            };
            if ($("#btn-reset").length) {
                document.getElementById("btn-reset").click();
            }
            /* const myModal = tailwind.Modal.getInstance(document.querySelector("#modal-add-on"));
            myModal.hide(); */
        },

        viewAllSites() {
            this.isDataLoading = true;
            get(
                `/sites?page=${this.pagination1.current_page}&per_page=${this.pagination1.per_page}&search=${this.search}`
            )
                .then(({ data, status }) => {
                    this.isDataLoading = false;
                    this.sites = data.sites.data;
                    this.pagination1 = {
                        current_page: data.sites.current_page,
                        last_page: data.sites.last_page,
                        total: data.sites.total,
                        per_page: data.sites.per_page,
                    };
                })
                .catch((err) => {
                    this.isDataLoading = false;
                    console.log("error");
                });
        },

        deleteArea(id) {
            let self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement cette zone de patrouille ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.load_id = id;
                    postJson("/table.delete", {
                        table: "areas",
                        id: id,
                    })
                        .then((res) => {
                            const index = this.selectedAreas.findIndex(
                                (objet) => objet.id === id
                            );
                            if (index !== -1) {
                                this.selectedAreas.splice(index, 1);
                            }
                            self.load_id = "";
                            self.viewAllSites();
                        })
                        .catch((err) => {
                            self.load_id = "";
                        });
                }
            });
        },

        deleteSite(data) {
            const self = this;
            new Swal({
                text: `Etes-vous sûr de vouloir supprimer définitivement ce site ??`,
                icon: "warning",
                showConfirmButton: 1,
                showCancelButton: 1,
                confirmButtonText: "Confirmer",
                denyButtonText: `Annuler`,
            }).then((result) => {
                if (result.isConfirmed) {
                    self.delete_id = data.id;
                    postJson("/table.delete", {
                        table: "sites",
                        id: data.id,
                    }).then(() => {
                        self.delete_id = "";
                        self.viewAllSites();
                    });
                }
            });
        },

        viewPresenceBySite() {
            this.isPresenceLoading = true;
            this.presences = [];
            const selectedDate = this.filter_datep || this.presenceDate;
            get(
                `/presences?site_id=${this.selectedSiteId}&date=${selectedDate}&page=${this.pagination.current_page}&per_page=${this.pagination.per_page}search=${this.search2}`
            )
                .then(({ data, status }) => {
                    this.isPresenceLoading = false;
                    this.pagination = {
                        current_page: data.presences.current_page,
                        last_page: data.presences.last_page,
                        total: data.presences.total,
                        per_page: data.presences.per_page,
                    };
                    if (data.status === "success") {
                        this.presences = data.presences.data;
                    }
                })
                .catch((err) => {
                    console.error(
                        "Erreur lors du chargement des présences :",
                        err
                    );
                    this.isPresenceLoading = false;
                });
        },

        exportToExcel() {
            const data = this.filteredPresences.map((p) => ({
                "Nom complet": p.agent.fullname || "",
                Horaire: p.agent.groupe.horaire.libelle || "",
                "Heure d'entrée": p.started_at || "",
                "Heure de sortie": p.ended_at || "",
                Durée: p.duree || "",
                Retard: p.retard || "",
                "Statut photo début": p.status_photo_debut || "",
                "Statut photo fin": p.status_photo_fin || "",
                Date: p.created_at.substring(0, 10) || "",
                Commentaires: p.commentaires || "",
            }));

            const worksheet = XLSX.utils.json_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Présences");

            XLSX.writeFile(workbook, "presences.xlsx");
        },

        changePage(page) {
            this.pagination.current_page = page;
            this.viewPresenceBySite();
        },

        onPerPageChange(perPage) {
            this.pagination.per_page = perPage;
            this.pagination.current_page = 1;
            this.viewPresenceBySite();
        },

        changePage1(page) {
            this.pagination1.current_page = page;
            this.viewAllSites();
        },

        onPerPageChange1(perPage) {
            this.pagination1.per_page = perPage;
            this.pagination1.current_page = 1;
            this.viewAllSites();
        },
    },

    computed: {
        filteredPresences() {
            return this.presences;
            /* if (!this.search2.trim())  */

            /* const searchTerm = this.search2.toLowerCase();

            return this.presences.filter((p) => {
                return (
                    (p.agent.fullname || "")
                        .toLowerCase()
                        .includes(searchTerm) ||
                    (p.horaire.libelle || "")
                        .toLowerCase()
                        .includes(searchTerm) ||
                    (p.started_at || "").toLowerCase().includes(searchTerm) ||
                    (p.ended_at || "").toLowerCase().includes(searchTerm) ||
                    (p.duree || "").toLowerCase().includes(searchTerm) ||
                    (p.retard || "").toLowerCase().includes(searchTerm) ||
                    (p.status_photo_debut || "")
                        .toLowerCase()
                        .includes(searchTerm) ||
                    (p.status_photo_fin || "")
                        .toLowerCase()
                        .includes(searchTerm) ||
                    (p.created_at || "").toLowerCase().includes(searchTerm) ||
                    (p.commentaires || "").toLowerCase().includes(searchTerm)
                );
            }); */
        },
        allSites() {
            /* if (this.search && this.search.trim()) {
                return this.sites.filter(
                    (el) =>
                        el.name
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        el.code
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                );
            } else {
                
            } */
            return this.sites;
        },
    },
});
