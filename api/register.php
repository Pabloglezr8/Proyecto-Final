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
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
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
                $message_class = "color-message-error"; // Asignar la clase de mensaje de error
            } else {
                // Si el código de administrador es correcto, asignar rol 0
                $role = 0;
            }
        }

        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Conexión a la base de datos
        $conn = connectDB();

        // Verificar si la conexión fue exitosa
        if($conn){
            // Verificar si el correo electrónico ya está registrado
            $query_check_email = "SELECT COUNT(*) as count FROM usuarios WHERE email = :email";
            $statement_check_email = $conn->prepare($query_check_email);
            $statement_check_email->bindParam(":email", $email);
            $statement_check_email->execute();
            $result_check_email = $statement_check_email->fetch(PDO::FETCH_ASSOC);

            if($result_check_email['count'] > 0){
                // Si el correo electrónico ya está registrado, mostrar un mensaje de error
                $message = "El correo electrónico ya está registrado";
                $message_class = "color-message-error"; // Asignar la clase de mensaje de error
            } else {
                // Preparar la consulta para insertar un nuevo usuario en la base de datos
                $query = "INSERT INTO usuarios (name, surname, email, password, role) VALUES (:name, :surname, :email, :password, :role)";
                $statement = $conn->prepare($query);

                $statement->bindParam(":name", $name);
                $statement->bindParam(":surname", $surname);
                $statement->bindParam(":email", $email);
                $statement->bindParam(":password", $hashed_password); // Almacenar la contraseña hasheada
                $statement->bindParam(":role", $role);

                // Ejecutar la consulta para insertar el nuevo usuario
                if($statement->execute() && !empty($name) && !empty($surname) && !empty($email) && !empty($hashed_password)){
                    $message = "Usuario registrado correctamente";
                    $message_class = "color-message-success"; // Asignar la clase de mensaje de éxito
                    echo "<p class='message $message_class'>" . $message . "</p>";
                    header("Refresh: 3; url=./login.php");
                    exit();
                }else{
                    $message = "Error al registrar al usuario";
                    $message_class = "color-message-error"; // Asignar la clase de mensaje de error
                    echo "<p class='message $message_class'>" . $message . "</p>";
                    header("Refresh: 3; url=" . $_SERVER['PHP_SELF']); 
                }
            }
        }else{
            echo "Error al conectar a la Base de Datos";
        }
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/register.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .code-input-container {
            display: none;
        }
    </style>
</head>
<body>
<div class="page">
    <div><h1 class="title">Registrate</h1></div>
    <!-- Aquí se mostrará el mensaje -->
    <div id="register-message"></div>
    <div class="register-form-container">
        <form id="register-form" method="post" action="">
            <input type="text" id="name" name="name" placeholder="Nombre" required>
            <input type="text" id="surname" name="surname" placeholder="Apellidos" required>
            <input type="email" id="email" name="email" placeholder="E-mail"  required>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <!-- Botón para mostrar el input del código -->
            <a id="show-code-btn">¿Tienes un código de administrador?</a>
            <!-- Contenedor para el input del código (inicialmente oculto) -->
            <div class="code-input-container">
                <input type="password" id="code" name="code" placeholder="Código">
            </div>
            <button type="button" id="place-register-btn">Confirmar</button>
        </form>
        
        
    </div>
</div>
<script>
</script>
<script src="../scripts/register.js"></script>

</body>
</html>
