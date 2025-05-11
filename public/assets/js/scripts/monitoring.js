import { get, postJson } from "../modules/http.js";

new Vue({
    el: "#App",
    data() {
        return {
            error: null,
            result: null,
            isLoading: false,
            patrolPendings: [],
            previousPatrolCount: 0, // Stocker la longueur précédente de la liste
            selectedPatrol: null,
            intervalId: null,
            hasInitialized: false, // Flag pour détecter le premier appel
            audio: new Audio("/assets/audios/audio-1.wav"), // Charger le fichier audio
        };
    },

    mounted() {
        // Cacher le loader après le chargement de Vue.js
        if ($("#loader").length) {
            document.getElementById("loader").style.display = "none";
        }
        // Lancer la récupération périodique des patrouilles en attente
        this.viewAllPendingScans();
    },

    beforeDestroy() {
        // Nettoyer l'intervalle lors de la destruction du composant
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    },

    methods: {
        // Méthode pour récupérer les patrouilles en attente toutes les 5 secondes
        viewAllPendingScans() {
            this.intervalId = setInterval(() => {
                get("/patrols.pending")
                    .then((res) => {
                        const newPatrols = res.data.pending_patrols;

                        // Vérification si une nouvelle patrouille a été ajoutée,
                        // mais seulement après l'initialisation
                        if (
                            this.hasInitialized &&
                            newPatrols.length > this.previousPatrolCount
                        ) {
                            this.audio.play(); // Jouer le son
                        }

                        // Marquer comme initialisé après le premier appel
                        this.hasInitialized = true;

                        // Mettre à jour le nombre de patrouilles
                        this.previousPatrolCount = newPatrols.length;
                        this.patrolPendings = newPatrols;

                        // Vérification que la patrouille sélectionnée existe toujours
                        if (this.selectedPatrol) {
                            const updatedPatrol = this.patrolPendings.find(
                                (patrol) => patrol.id === this.selectedPatrol.id
                            );

                            // Si la patrouille sélectionnée a été modifiée ou n'existe plus
                            if (updatedPatrol) {
                                this.selectedPatrol = updatedPatrol;
                            } else {
                                this.selectedPatrol = null;
                                if (
                                    document.querySelector("#btn-reset") !==
                                    null
                                ) {
                                    document
                                        .getElementById("btn-reset")
                                        .click();
                                }
                            }
                        }
                    })
                    .catch((err) => {
                        console.log("Erreur :", err);
                        this.error =
                            "Erreur lors de la récupération des patrouilles en attente.";
                    });
            }, 5000); // Rafraîchir toutes les 5 secondes
        },

        // Méthode pour sélectionner une patrouille
        onSelectItem(item) {
            this.selectedPatrol = item;
        },
    },

    computed: {
        // Renvoyer toutes les patrouilles en attente
        allPendingPatrols() {
            return this.patrolPendings;
        },
    },
});
