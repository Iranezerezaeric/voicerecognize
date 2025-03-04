<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Attrayante</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="border-animation"></div>
            <h2>Connexion</h2>
            <form id="loginForm">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" id="username" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="btn">Se connecter</button>
            </form>
            <p class="forgot-password"><a href="#">Mot de passe oublié ?</a></p>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
