<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reconnaissance de Voix - Eric</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        #status {
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>

    <h1>Reconnaissance de Voix - Eric</h1>
    
    <button id="startBtn">Enregistrer ou Comparer la Voix</button>
    <p id="status">Cliquez sur "Enregistrer ou Comparer la Voix" pour commencer.</p>
    
    <script>
        const startBtn = document.getElementById('startBtn');
        const status = document.getElementById('status');

        let recognizer = null;
        let ericVoiceFingerprint = null; 

        function startRecognition() {
            if (!('webkitSpeechRecognition' in window)) {
                alert('Votre navigateur ne supporte pas la reconnaissance vocale.');
                return;
            }

            recognizer = new webkitSpeechRecognition();
            recognizer.lang = 'fr-FR'; 
            recognizer.continuous = true; 
            recognizer.interimResults = true; 

            recognizer.onstart = function() {
                status.innerText = "Reconnaissance vocale activée... Parlez maintenant.";
            };

            recognizer.onresult = function(event) {
                const transcript = event.results[event.resultIndex][0].transcript.trim();
                status.innerText = `Vous avez dit : ${transcript}`;

                if (ericVoiceFingerprint) {
                    const isEricVoice = compareVoices(transcript, ericVoiceFingerprint);
                    if (isEricVoice) {
                        status.innerText = `C'est Eric qui parle !`;
                    } else {
                        status.innerText = `Ce n'est pas Eric !`;
                    }
                }
            };

            recognizer.onerror = function(event) {
                status.innerText = `Erreur : ${event.error}`;
            };

            recognizer.onend = function() {
                status.innerText = "Reconnaissance vocale terminée.";
            };

            recognizer.start();
        }

        // Fonction pour enregistrer la voix d'Eric
        function recordEricVoice() {
            if (!recognizer) {
                // Si la reconnaissance vocale n'est pas encore initialisée, il faut la démarrer
                startRecognition();
            }

            status.innerText = "Enregistrement de la voix d'Eric... Parlez maintenant.";
            recognizer.onresult = function(event) {
                const transcript = event.results[event.resultIndex][0].transcript.trim();

                // Enregistrer la voix d'Eric seulement une fois
                if (!ericVoiceFingerprint) {
                    ericVoiceFingerprint = generateVoiceFingerprint(transcript);  // Cette fonction génère un "empreinte vocale"
                    status.innerText = "Voix d'Eric enregistrée ! Vous pouvez maintenant comparer les voix.";
                }
            };
        }

        // Générer une "empreinte vocale" (exemple basique basé sur le texte)
        function generateVoiceFingerprint(text) {
            // Cette approche basique génère une empreinte basée sur le texte. (En réalité, ce devrait être une empreinte vocale complexe.)
            return text.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
        }

        // Comparer la voix actuelle avec l'empreinte d'Eric
        function compareVoices(currentText, storedFingerprint) {
            const currentFingerprint = generateVoiceFingerprint(currentText);
            return currentFingerprint === storedFingerprint;
        }

        // Actions sur le bouton
        startBtn.onclick = function() {
            if (!ericVoiceFingerprint) {
                // Si la voix d'Eric n'est pas encore enregistrée, enregistrer la voix
                recordEricVoice();
            } else {
                // Démarrer la reconnaissance vocale pour détecter les voix et comparer
                startRecognition();
            }
        };

    </script>

</body>
</html>
