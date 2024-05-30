<?php
include("connectDB.php");

function login($email, $password) {
    // Inicializar la respuesta
    $response = [
        "success" => false,
        "message" => "Error desconocido"
    ];

    try {
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
                session_start();
                $_SESSION["id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["surname"] = $user["surname"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["password"] = $user["password"];
                $_SESSION["address"] = $user["address"];
                $_SESSION["postal_code"] = $user["postal_code"];
                $_SESSION["location"] = $user["location"];
                $_SESSION["country"] = $user["country"];
                $_SESSION["phone"] = $user["phone"];
                $_SESSION["role"] = $user["role"];

                $response = [
                    "success" => true,
                    "message" => "Inicio de sesión exitoso"
                ];
            } else {
                $response["message"] = "Correo electrónico o contraseña incorrectos";
            }
        } else {
            $response["message"] = "Error de conexión a la base de datos";
        }
    } catch (Exception $e) {
        $response["message"] = "Excepción capturada: " . $e->getMessage();
    }

    return $response;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el correo electrónico y la contraseña del formulario
    $email = $_POST["email"];
    $password = $_POST["password"];

    $response = login($email, $password);

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
