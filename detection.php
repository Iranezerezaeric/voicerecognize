<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détection d'Objets avec la Caméra</title>
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
        canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>

    <h1>Détection d'Objets avec la Caméra</h1>

    <video id="webcam" autoplay></video>
    <p id="status">Initialisation de la caméra...</p>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd"></script>

    <script>
        const video = document.getElementById('webcam');
        const status = document.getElementById('status');
        let model;

        const translations = {
            "person": "personne",
            "bicycle": "bicyclette",
            "car": "voiture",
            "motorcycle": "moto",
            "airplane": "avion",
            "bus": "bus",
            "train": "train",
            "truck": "camion",
            "boat": "bateau",
            "traffic light": "feu de signalisation",
            "fire hydrant": "borne d'incendie",
            "stop sign": "panneau stop",
            "parking meter": "parcmètre",
            "bench": "banc",
            "bird": "oiseau",
            "cat": "chat",
            "dog": "chien",
            "horse": "cheval",
            "sheep": "mouton",
            "cow": "vache",
            "elephant": "éléphant",
            "bear": "ours",
            "zebra": "zèbre",
            "giraffe": "girafe",
            "backpack": "sac à dos",
            "umbrella": "parapluie",
            "handbag": "sac à main",
            "tie": "cravate",
            "suitcase": "valise",
            "frisbee": "frisbee",
            "skis": "skis",
            "snowboard": "planche à neige",
            "sports ball": "ballon de sport",
            "kite": "cerf-volant",
            "baseball bat": "batte de baseball",
            "baseball glove": "gant de baseball",
            "skateboard": "skateboard",
            "surfboard": "planche de surf",
            "tennis racket": "raquette de tennis",
            "bottle": "bouteille",
            "wine glass": "verre à vin",
            "cup": "tasse",
            "fork": "fourchette",
            "knife": "couteau",
            "spoon": "cuillère",
            "bowl": "bol",
            "banana": "banane",
            "apple": "pomme",
            "sandwich": "sandwich",
            "orange": "orange",
            "broccoli": "brocoli",
            "carrot": "carotte",
            "hot dog": "hot dog",
            "pizza": "pizza",
            "donut": "beignet",
            "cake": "gâteau",
            "chair": "chaise",
            "couch": "canapé",
            "potted plant": "plante en pot",
            "bed": "lit",
            "dining table": "table à manger",
            "toilet": "toilettes",
            "tv": "télévision",
            "laptop": "ordinateur portable",
            "mouse": "souris",
            "remote": "télécommande",
            "keyboard": "clavier",
            "cell phone": "téléphone portable",
            "microwave": "micro-ondes",
            "oven": "four",
            "toaster": "grille-pain",
            "sink": "évier",
            "refrigerator": "réfrigérateur",
            "book": "livre",
            "clock": "horloge",
            "vase": "vase",
            "scissors": "ciseaux",
            "teddy bear": "ours en peluche",
            "hair drier": "sèche-cheveux",
            "toothbrush": "brosse à dents",
            "pen":"stylo"
        };

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    video.play();
                    status.innerText = "Caméra initialisée, modèle en cours de chargement...";
                    loadModel();
                };
            } catch (error) {
                console.error("Erreur lors de l'initialisation de la caméra :", error);
                status.innerText = "Erreur lors de l'initialisation de la caméra.";
            }
        }

        async function loadModel() {
            try {
                model = await cocoSsd.load();
                status.innerText = "Modèle chargé, en attente de la détection...";
                console.log("Modèle chargé :", model);
                detectObjects();
            } catch (error) {
                console.error("Erreur lors du chargement du modèle :", error);
                status.innerText = "Erreur lors du chargement du modèle.";
            }
        }

        async function detectObjects() {
            setInterval(async () => {
                try {
                    const predictions = await model.detect(video);
                    console.log("Prédictions :", predictions);  

                    status.innerHTML = predictions.length ? `Objets détectés : ${predictions.length}` : "Aucun objet détecté";
                    drawPredictions(predictions);
                } catch (error) {
                    console.error("Erreur lors de la détection des objets :", error);
                    status.innerText = "Erreur lors de la détection des objets.";
                }
            }, 100);
        }

        function drawPredictions(predictions) {
            let canvas = document.querySelector('canvas');
            if (!canvas) {
                canvas = document.createElement('canvas');
                document.body.appendChild(canvas);
            }

            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.style.position = 'absolute';
            canvas.style.top = video.offsetTop + 'px';
            canvas.style.left = video.offsetLeft + 'px';

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            predictions.forEach(prediction => {
                const [x, y, width, height] = prediction.bbox;
                ctx.beginPath();
                ctx.rect(x, y, width, height);
                ctx.lineWidth = 2;
                ctx.strokeStyle = 'green';
                ctx.fillStyle = 'green';
                ctx.stroke();

                const objectName = translations[prediction.class] || prediction.class;
                ctx.fillText(
                    `${objectName} (${Math.round(prediction.score * 100)}%)`,
                    x + 5, y + 15
                );
            });
        }

        startCamera();
    </script>

</body>
</html>
