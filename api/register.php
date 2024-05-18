<?php 
    // Mostrar todos los errores
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Incluir el archivo para la conexión a la base de datos
    include("./connectDB.php");

    // Iniciar la sesión
    session_start();

    // Variable para mensajes
    $message = null;

    // Verificar si se ha enviado un formulario por el método POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Obtener el nombre de usuario, la contraseña y el correo electrónico del formulario
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $rol = null;

        // Verificar el tipo de usuario (admin o no admin)
        if($username == "admin"){
            $rol = 0; // Si el nombre de usuario es "admin", asignar rol 0
        }else{
            $rol = 1; // Si no, asignar rol 1
        }

        // Verificar si los campos de nombre de usuario, contraseña y correo electrónico están vacíos
        if(empty($username) || empty($password) || empty($email)){
            $message = "Nombre de usuario, contraseña y correo electrónico son obligatorios";
            echo "<p class='message error'>" . $message . "</p>";
        } else {
            // Hashear la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Conexión a la base de datos
            $conn = connectDB();

            // Verificar si la conexión fue exitosa
            if($conn){
                // Preparar la consulta para insertar un nuevo usuario en la base de datos
                $query = "INSERT INTO usuarios (username, password, email, rol) VALUES (:username, :password, :email, :rol)";
                $statement = $conn->prepare($query);

                $statement->bindParam(":username", $username);
                $statement->bindParam(":password", $hashed_password); // Utilizar la contraseña hasheada
                $statement->bindParam(":email", $email);
                $statement->bindParam(":rol", $rol);

                // Ejecutar la consulta para insertar el nuevo usuario
                if($statement->execute()){
                    $message = "Usuario registrado correctamente";
                    echo "<p class='message success'>" . $message . "</p>";
                    header("Refresh: 3; url=./login.php");
                    exit();
                }else{
                    $message = "Error al registrar al usuario";
                    echo "<p class='message error'>" . $message . "</p>";
                    header("Refresh: 3; url=" . $_SERVER['PHP_SELF']); 
                }
            }else{
                echo "<p class='message error'>Error al conectar a la Base de Datos </p>";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="body-register">
    <!-- Formulario para el registro de usuarios -->
    <form method="post" class="enter">
        <div>
            <label for="username">Nombre de usuario</label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email">
        </div>
        
       
        
        <div class='btn-container'>
            <button class='btn insertar' type='submit' id='insert-btn'>Registrarse</button>
        </div>
    </form>
    
    <!-- Enlace para iniciar sesión -->
    <a href="./login.php" class="a-login">Iniciar sesión</a>
</body>
</html>
