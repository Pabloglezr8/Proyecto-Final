<?php
include("connectDB.php");

function login($email, $password) {
    $response = [
        "success" => false,
        "message" => "Error desconocido"
    ];

    if (empty($email) || empty($password)) {
        $response["message"] = "Todos los campos son obligatorios";
        return $response;
    }

    try {
        $conn = connectDB();
        if ($conn) {
            $query = "SELECT id, name, surname, email, password, address, postal_code, location, country, phone, role FROM usuarios WHERE email = :email";
            $statement = $conn->prepare($query);
            $statement->bindParam(":email", $email);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
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

                $response["success"] = true;
                $response["message"] = "Inicio de sesión exitoso";
            } else {
                $response["message"] = "Correo o Contraseña incorrectos";
            }
        } else {
            $response["message"] = "Error en la conexión con la base de datos";
        }
    } catch (Exception $e) {
        $response["message"] = "Excepción capturada: " . $e->getMessage();
    }

    return $response;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $response = login($email, $password);

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
