<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voicerecognize</title>
    <style>
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Reconnaissance vocale</h1>
    <button id="start-btn">Commencer</button>
    <p id="status">Appuyez sur "Commencer" et commencez à parler.</p>
    <h3>Texte reconnu :</h3>
    <p id="transcript"></p>
    
    <script>
        var recognition = null;

        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.lang = 'en-EN'; 
            recognition.continuous = false;  
            recognition.interimResults = false;  

            recognition.onstart = function() {
                document.getElementById('status').innerHTML = "Parlez maintenant...";
            };

            recognition.onend = function() {
                document.getElementById('status').innerHTML = "Reconnaissance terminée.";
            };

            recognition.onresult = function(event) {
                var transcript = event.results[0][0].transcript;
                document.getElementById('transcript').innerText = transcript;

                fetch('process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'text=' + encodeURIComponent(transcript)
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('status').innerText = 'Réponse du serveur: ' + data;
                });
            };

            document.getElementById('start-btn').onclick = function() {
                recognition.start();
            };
        } else {
            alert("Votre navigateur ne supporte pas la reconnaissance vocale.");
        }
    </script>
</body>
</html>

<?php
if (isset($_POST['text'])) {
    $text = $_POST['text'];
    

    echo "Vous avez dit: " . htmlspecialchars($text);
} else {
     echo "Aucun texte reçu.";
}
?>
