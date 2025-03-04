<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande Vocale pour Ouvrir YouTube</title>
</head>
<body>

    <h1>Commande Vocale pour Ouvrir YouTube</h1>
    <button id="start-button">Démarrer la Commande Vocale</button>
    <p id="status">Cliquez sur le bouton pour commencer</p>

    <script>
        const startButton = document.getElementById('start-button');
        const status = document.getElementById('status');

        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.lang = 'fr-FR';
            recognition.continuous = false;
            recognition.interimResults = false;

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript.toLowerCase();
                status.innerText = `Vous avez dit : ${transcript}`;

                if (transcript.includes('open youtube') || transcript.includes('ouvrir youtube')) {
                    window.open('https://www.youtube.com', '_blank');
                }
            };

            recognition.onerror = (event) => {
                status.innerText = `Erreur de reconnaissance vocale : ${event.error}`;
            };

            startButton.addEventListener('click', () => {
                recognition.start();
                status.innerText = 'Parlez maintenant...';
            });
        } else {
            status.innerText = 'La reconnaissance vocale n\'est pas supportée dans ce navigateur.';
        }
    </script>

</body>
</html>
