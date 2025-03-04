<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reconnaissance et Synthèse Vocale</title>
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
        textarea {
            width: 100%;
            height: 150px;
            font-size: 16px;
            padding: 10px;
            margin-top: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #status {
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
        select {
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1>Reconnaissance et Synthèse Vocale</h1>
    
    <textarea id="inputText" placeholder="Écrivez quelque chose ici..."></textarea>
    
    <div>
        <label for="languageSelect">Choisissez la langue :</label>
        <select id="languageSelect">
            <option value="fr-FR">Français</option>
            <option value="en-US">Anglais</option>
        </select>
    </div>
    
    <div>
        <label for="voiceSelect">Choisissez la voix :</label>
        <select id="voiceSelect">
        </select>
    </div>

    <button id="readTextBtn">Lire le texte</button>
    
    <p id="status">Écrivez du texte dans la zone ci-dessus et appuyez sur "Lire le texte" pour l'entendre.</p>
    
    <script>
        function readText() {
            var text = document.getElementById('inputText').value;
            if (text !== "") {
                var utterance = new SpeechSynthesisUtterance(text);
                var selectedLang = document.getElementById('languageSelect').value;
                var selectedVoice = document.getElementById('voiceSelect').value;
                
                utterance.lang = selectedLang;
                
                var voices = speechSynthesis.getVoices();
                utterance.voice = voices.find(voice => voice.name === selectedVoice);
                
                utterance.rate = 1;       
                utterance.pitch = 1;      
                
                speechSynthesis.speak(utterance);
                document.getElementById('status').innerText = "Lecture en cours...";
            } else {
                document.getElementById('status').innerText = "Veuillez écrire du texte à lire.";
            }
        }

        function loadVoices() {
            var voices = speechSynthesis.getVoices();
            var voiceSelect = document.getElementById('voiceSelect');
            
            voiceSelect.innerHTML = "";

            voices.forEach(voice => {
                var option = document.createElement('option');
                option.value = voice.name;
                option.textContent = voice.name + ' (' + voice.lang + ')';
                voiceSelect.appendChild(option);
            });
        }

        window.speechSynthesis.onvoiceschanged = loadVoices;
        loadVoices(); 
        document.getElementById('readTextBtn').onclick = readText;
    </script>

</body>
</html>
