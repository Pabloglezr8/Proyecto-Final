<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/login.js"></script>
</head>
<body >
<div class="logo"><a href="/FerreteriaVegagrande2/index.php"><img src="../assets/img/LogoCompleto.png" alt="Ferretería Vegagrande"></a></div>
    <div class="container">
        <div class="title-container">
            <a class="home-button" href="../index.php"><img src="../assets/img/icons/goBack.png" alt="home"></a>
            <h1 class="title">LogIn</h1>
        </div>
        <form id="loginForm" class="login-form">
            <input type="email" name="email" id="email" placeholder="E-mail">
            <input type="password" name="password" id="password" placeholder="Contraseña">
            <div id="message"></div>
            <button class='btn insertar' type='submit'>Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>