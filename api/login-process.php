<?php
// Incluir el archivo para la conexión a la base de datos
include("connectDB.php");

// Mostrar todos los errores (para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar la sesión
session_start();

// Inicializar la respuesta
$response = [
    "success" => false,
    "message" => "Error desconocido"
];

// Verificar si se ha enviado un formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtener el correo electrónico y la contraseña del formulario
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Conexión a la base de datos
        $conn = connectDB();

        if ($conn) {
            // Consultar la base de datos para obtener el usuario por su correo electrónico
            $query = "SELECT id, name, surname, email, password, address, postal_code, location, country, phone, role FROM usuarios WHERE email = :email";
            $statement = $conn->prepare($query);
            $statement->bindParam(":email", $email);
            $statement->execute();

            // Obtener los datos del usuario de la base de datos
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Verificar si se encontró el usuario y si la contraseña es correcta
            if ($user && password_verify($password, $user["password"])) {
                // Establecer las variables de sesión para el usuario
                $_SESSION["id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["surname"] = $user["surname"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["address"] = $user["address"];
                $_SESSION["postal_code"] = $user["postal_code"];
                $_SESSION["location"] = $user["location"];
                $_SESSION["country"] = $user["country"];
                $_SESSION["phone"] = $user["phone"];
                $_SESSION["role"] = $user["role"];

                // Redireccionar según el rol del usuario (admin o usuario normal)
                if ($user["role"] == 0) {
                    $response = [
                        "success" => true,
                        "message" => "Inicio de sesión exitoso",
                        "redirect" => "../index.php"
                    ];
                } else {
                    $response = [
                        "success" => true,
                        "message" => "Inicio de sesión exitoso",
                        "redirect" => "../index.php"
                    ];
                }
            } else {
                $response["message"] = "Correo electrónico o contraseña incorrectos";
            }
        } else {
            $response["message"] = "Error de conexión a la base de datos";
        }
    } catch (Exception $e) {
        $response["message"] = "Excepción capturada: " . $e->getMessage();
    }
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
