<?php 
    // Incluir el archivo para la conexión a la base de datos
    include("./connectDB.php");

    // Iniciar la sesión
    session_start();

    // Variable para mensajes
    $message = null;

    // Verificar si se ha enviado un formulario por el método POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Obtener el nombre de usuario o el correo electrónico y la contraseña del formulario
        $usernameOrEmail = $_POST["username"];
        $password = $_POST["password"];

        // Conexión a la base de datos
        $conn = connectDB();

        // Verificar si la conexión fue exitosa
        if($conn){
            // Consultar la base de datos para obtener el usuario por su nombre de usuario o correo electrónico
            $query = "SELECT id, username, password, rol FROM usuarios WHERE username = :username OR email = :email";
            $statement = $conn->prepare($query);
            $statement->bindParam(":username", $usernameOrEmail);
            $statement->bindParam(":email", $usernameOrEmail);
            $statement->execute(); 

            // Obtener los datos del usuario de la base de datos
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Verificar si se encontró el usuario y si la contraseña es correcta
            if($user && password_verify($password, $user["password"])){
                // Establecer las variables de sesión para el usuario
                $_SESSION["id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                 
                // Redireccionar según el rol del usuario (admin o usuario normal)
                if($user["rol"] == 0){
                    $message = "Inicio de sesión exitoso";
                    echo "<p class='message success'>" . $message . "</p>";
                    header("Refresh: 3; url=./admin.php"); 

                }else{
                    $message = "Inicio de sesión exitoso";
                    echo "<p class='message success'>" . $message . "</p>";
                    header("Refresh: 3; url=./user.php");
                }
            } else {
                // Si no se encontró el usuario o la contraseña es incorrecta, mostrar un mensaje de error
                $message = "Error al iniciar sesión";
                echo "<p class='message error'>" . $message . "</p>";
                header("Refresh: 3; URL=" . $_SERVER['PHP_SELF']);
            } 
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="body-login">
    <!-- Formulario para iniciar sesión -->
    <form method="post" class="enter">
        <div>
            <label for="username">Nombre de usuario o Correo electrónico</label>
            <input type="text" name="username" id="username">  
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password">
        </div>

        <div class='btn-container'>
            <button class='btn insertar' type='submit' id='insert-btn'>Iniciar Sesión</button>
        </div>
    </form>
    
    <!-- Enlace para registrarse -->
    <a href="./register.php" class="a-registrarse">Registrarse</a>
</body>
</html>
