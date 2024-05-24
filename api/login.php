<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/login.js"></script>
</head>
<body class="body-login">
    <form id="loginForm" class="enter">
        <div>
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class='btn-container'>
            <button class='btn insertar' type='submit'>Iniciar Sesión</button>
        </div>
    </form>
    <div id="message"></div>
    <a href="./register.php" class="a-registrarse">Registrarse</a>
</body>
</html>
