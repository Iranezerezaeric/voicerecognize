<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détection d'Objets et Scan de Visages</title>
    <style>
        #webcam {
            border: 1px solid black;
            margin-top: 20px;
            width: 100%;
            height: auto;
        }
        #status {
            font-size: 18px;
            font-weight: bold;
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Détection d'Objets et Scan de Visages</h1>

    <video id="webcam" autoplay></video>
    <p id="status">Initialisation de la caméra...</p>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js"></script>

    <script>
        const video = document.getElementById('webcam');
        const status = document.getElementById('status');

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
                video.srcObject = stream;
                video.play();
                status.innerText = "Caméra prête. Chargement des modèles...";
                loadModels();
            } catch (error) {
                console.error("Erreur lors de l'accès à la caméra : ", error);
                status.innerText = "Impossible d'accéder à la caméra.";
            }
        }

        async function loadModels() {
            try {
                await faceapi.nets.ssdMobilenetv1.loadFromUri('/models');
                await cocoSsd.load();
                console.log("Modèles chargés avec succès!");
                status.innerText = "Modèles chargés, en attente de la détection...";
                detectObjectsAndFaces();
            } catch (error) {
                console.error("Erreur lors du chargement des modèles : ", error);
                status.innerText = "Erreur de chargement des modèles.";
            }
        }

        async function detectObjectsAndFaces() {
            const model = await cocoSsd.load();
            setInterval(async () => {
                const predictions = await model.detect(video);
                drawPredictions(predictions);

                const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();
                drawFaceBoxes(detections);
            }, 100);
        }

        function drawPredictions(predictions) {
            const canvas = document.getElementById('overlayCanvas');
            const ctx = canvas.getContext('2d');
            canvas.width = video.width;
            canvas.height = video.height;
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            predictions.forEach(prediction => {
                const [x, y, width, height] = prediction.bbox;
                ctx.beginPath();
                ctx.rect(x, y, width, height);
                ctx.lineWidth = 2;
                ctx.strokeStyle = 'green';
                ctx.fillStyle = 'green';
                ctx.stroke();
                ctx.fillText(
                    `${prediction.class} (${Math.round(prediction.score * 100)}%)`,
                    x + 5, y + 15
                );
            });
        }

        function drawFaceBoxes(detections) {
            const canvas = document.getElementById('overlayCanvas');
            const ctx = canvas.getContext('2d');
            ctx.lineWidth = 2;
            ctx.strokeStyle = 'blue';

            detections.forEach(detection => {
                const { x, y, width, height } = detection.detection.box;
                ctx.beginPath();
                ctx.rect(x, y, width, height);
                ctx.stroke();
                ctx.fillStyle = 'blue';
                ctx.fillText('Visage détecté', x + 5, y + 15);
            });
        }

        startCamera();
    </script>

    <canvas id="overlayCanvas"></canvas>
</body>
</html>
