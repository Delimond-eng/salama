<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notify handler</title>
</head>
<body>
<h1 id="message">Hello</h1>

</body>
@vite('resources/js/app.js')
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const message = document.querySelector('#message');
        window.Echo.channel('notification')
            .listen('.PatrolNotificationEvent', (e) => {
                speakText(`${e.data.title}, ${e.data.content}`);
                message.textContent = JSON.stringify(e);
            });

        function speakText(text) {
            // Vérifier si le navigateur supporte l'API de synthèse vocale
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                // Choisir la langue (ici le français)
                utterance.lang = 'fr-FR';
                // Lire le texte
                window.speechSynthesis.speak(utterance);
            } else {
                alert("Votre navigateur ne supporte pas la synthèse vocale.");
            }
        }


    })
</script>
</html>
