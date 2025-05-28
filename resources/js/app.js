import "./bootstrap";

verifySchedule();

const oneHourInMs = 3600000; // 1 heure = 60*60*1000 ms
const intervalle = setInterval(() => {
    verifySchedule();
}, oneHourInMs);

function verifySchedule() {
    fetch("/schedules.verify")
        .then((response) => response.json())
        .then((data) => console.log("Vérification planning effectuée:", data))
        .catch((error) =>
            console.error("Erreur lors de la vérification:", error)
        );
}
