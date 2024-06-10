<?php 
// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo para la conexión a la base de datos
include("../api/connectDB.php");

// Iniciar la sesión
session_start();

// Verificar si se ha enviado un formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializar la respuesta
    $response = [
        "success" => false,
        "message" => "Error desconocido"
    ];

    // Obtener los datos del formulario
    $name = trim($_POST["name"]);
    $surname = trim($_POST["surname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = null;

    // Validar campos requeridos
    $_fields = ['name', 'surname', 'email', 'password'];
    foreach ($_fields as $field) {
        if (empty($_POST[$field])) {
            $response["message"] = "Todos los campos son obligatorios";
            echo json_encode($response);
            exit;
        }
    }

    // Validaciones
    if (!preg_match("/^[\p{L}\s]+$/u", $name)) {
        $response["message"] = "Nombre no válido.";
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[\p{L}\s]+$/u", $surname)) {
        $response["message"] = "Apellido no válido.";
        echo json_encode($response);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["message"] = "E-mail no válido.";
        echo json_encode($response);
        exit;
    }

    // Verificar el código del administrador
    $admin_code = "admin";
    $code = $_POST["code"];

    // Si el campo del código está vacío, asignar rol 1
    if (empty($code)) {
        $role = 1;
    } else {
        // Si el campo del código no está vacío, pero no coincide con el código de administrador, mostrar error
        if ($code != $admin_code) {
            $response["message"] = "El código no es válido";
            echo json_encode($response);
            exit;
        } else {
            // Si el código de administrador es correcto, asignar rol 0
            $role = 0;
        }
    }

    // Verificar si hay un rol asignado antes de continuar
    if (is_null($role)) {
        $response["message"] = "El código no es válido";
        echo json_encode($response);
        exit;
    } else {
        // Conexión a la base de datos
        $conn = connectDB();

        // Verificar si la conexión fue exitosa
        if ($conn) {
            // Verificar si el correo electrónico ya está registrado
            $query_check_email = "SELECT email FROM usuarios WHERE email = :email";
            $statement_check_email = $conn->prepare($query_check_email);
            $statement_check_email->bindParam(":email", $email, PDO::PARAM_STR);
            $statement_check_email->execute();
            $result_check_email = $statement_check_email->fetch(PDO::FETCH_ASSOC);

            if ($result_check_email) {
                $response["message"] = "El correo electrónico ya existe";
            } else {
                // Hashear la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Preparar la consulta para insertar un nuevo usuario en la base de datos
                $query = "INSERT INTO usuarios (name, surname, email, password, role) VALUES (:name, :surname, :email, :password, :role)";
                $statement = $conn->prepare($query);

                $statement->bindParam(":name", $name, PDO::PARAM_STR);
                $statement->bindParam(":surname", $surname, PDO::PARAM_STR);
                $statement->bindParam(":email", $email, PDO::PARAM_STR);
                $statement->bindParam(":password", $hashed_password, PDO::PARAM_STR);
                $statement->bindParam(":role", $role, PDO::PARAM_INT);

                // Ejecutar la consulta para insertar el nuevo usuario
                if ($statement->execute()) {
                    $response["success"] = true;
                    $response["message"] = "Usuario registrado correctamente";
                } else {
                    $response["message"] = "Error al registrar al usuario";
                }
            }
        } else {
            $response["message"] = "Error al conectar a la Base de Datos";
        }
    }

    echo json_encode($response);
    exit;
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
    <script src="../scripts/register.js"></script>
    <style>
    </style>
</head>
<body>
<div class="logo"><a href="/FerreteriaVegagrande2/index.php"><img src="../assets/img/LogoCompleto.png" alt="Ferretería Vegagrande"></a></div>
<div class="container">
    <div class="title-container">
        <a class="home-button" href="../index.php"><img src="../assets/img/icons/goBack.png" alt="home"></a>
        <h1 class="title">Regístrate</h1>
    </div>
    <div class="register-form-container">
        <form id="register-form" method="post" action="">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" placeholder="Nombre" >
            
            <label for="surname">Apellidos</label>
            <input type="text" id="surname" name="surname" placeholder="Apellidos" >
            
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="E-mail"  >
            
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" >
            <!-- Botón para mostrar el input del código -->
            <a id="show-code-btn">¿Tienes un código de administrador? Haz click</a>
            <!-- Contenedor para el input del código (inicialmente oculto) -->
            <div class="code-input-container" style="display: none;">
                <input type="password" id="code" name="code" placeholder="Código de Administrador">
            </div>
            <div id="register-message" class="message"></div>
            <button type="submit" id="place-register-btn">Confirmar</button>
        </form>
    </div>
</div>
</body>
</html>