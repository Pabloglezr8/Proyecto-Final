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
    $message_class = ""; // Definir la clase de mensaje

    // Verificar si se ha enviado un formulario por el método POST
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Obtener los datos del formulario
        $name = trim($_POST["name"]);
        $surname = trim($_POST["surname"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $role = null;

        // Verificar el código del administrador
        $admin_code = "admin"; // Aquí debes reemplazar 'tu_codigo_de_administrador_predefinido' con el código de administrador real
        $code = $_POST["code"];

        // Si el campo del código está vacío, asignar rol 1
        if(empty($code)){
            $role = 1;
        } else {
            // Si el campo del código no está vacío, pero no coincide con el código de administrador, mostrar error
            if($code != $admin_code){
                $message = "El código no es válido";
                $message_class = "error"; // Asignar la clase de mensaje de error
            } else {
                // Si el código de administrador es correcto, asignar rol 0
                $role = 0;
            }
        }

        // Verificar si hay un rol asignado antes de continuar
        if (is_null($role)) {
            $message = "Error al asignar rol de usuario";
            $message_class = "error"; // Asignar la clase de mensaje de error
        } else {
            // Conexión a la base de datos
            $conn = connectDB();

            // Verificar si la conexión fue exitosa
            if($conn){
                // Verificar si el correo electrónico ya está registrado
                $query_check_email = "SELECT email FROM usuarios WHERE email = :email";
                $statement_check_email = $conn->prepare($query_check_email);
                $statement_check_email->bindParam(":email", $email, PDO::PARAM_STR);
                $statement_check_email->execute();
                $result_check_email = $statement_check_email->fetch(PDO::FETCH_ASSOC);

                if($result_check_email){
                    $message = "El correo electrónico ya existe";
                    $message_class = "error"; // Asignar la clase de mensaje de error
                } else {
                    // Hashear la contraseña
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Preparar la consulta para insertar un nuevo usuario en la base de datos
                    $query = "INSERT INTO usuarios (name, surname, email, password, role) VALUES (:name, :surname, :email, :password, :role)";
                    $statement = $conn->prepare($query);

                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":surname", $surname, PDO::PARAM_STR);
                    $statement->bindParam(":email", $email, PDO::PARAM_STR);
                    $statement->bindParam(":password", $hashed_password, PDO::PARAM_STR); // Almacenar la contraseña hasheada
                    $statement->bindParam(":role", $role, PDO::PARAM_INT);

                    // Ejecutar la consulta para insertar el nuevo usuario
                    if($statement->execute() && !empty($name) && !empty($surname) && !empty($email) && !empty($password)){
                        $message = "Usuario registrado correctamente";
                        $message_class = "success"; // Asignar la clase de mensaje de éxito
                        header("Refresh: 0; url=login.php"); // Redirigir inmediatamente
                        exit();
                    } else {
                        $message = "Error al registrar al usuario";
                        $message_class = "error"; // Asignar la clase de mensaje de error
                    }
                }
            } else {
                echo "Error al conectar a la Base de Datos";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/register.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    </style>
</head>
<body>
<div class="logo"><a href="/FerreteriaVegagrande/index.php"><img src="../assets/img/LogoCompleto.png" alt="Ferretería Vegagrande"></a></div>
<div class="container">
    <div class="title-container">
        <a class="home-button" href="../index.php"><img src="../assets/img/icons/goBack.png" alt="home"></a>
        <h1 class="title">Registrate</h1>
    </div>
    <div class="message parragraf <?=$message_class?>"><p><?= $message ?></p></div>
    <div class="register-form-container">
        <form id="register-form" method="post" action="">
            <input type="text" id="name" name="name" placeholder="Nombre" required>
            <input type="text" id="surname" name="surname" placeholder="Apellidos" required>
            <input type="email" id="email" name="email" placeholder="E-mail"  required>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <!-- Botón para mostrar el input del código -->
            <a id="show-code-btn">¿Tienes un código de administrador? Haz click</a>
            <!-- Contenedor para el input del código (inicialmente oculto) -->
            <div class="code-input-container">
                <input type="password" id="code" name="code" placeholder="Código de Administrador">
            </div>
            <button type="submit" id="place-register-btn">Confirmar</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#show-code-btn').click(function(event){
            event.preventDefault();
            $('.code-input-container').toggle();
        });
    });
</script>
</body>
</html>
