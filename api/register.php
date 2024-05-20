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
    // Obtener el nombre de usuario, la contraseña, el correo electrónico y la dirección del formulario
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $rol = null;

    // Verificar el tipo de usuario (admin o no admin)
    if($username == "admin"){
        $rol = 0; // Si el nombre de usuario es "admin", asignar rol 0
    }else{
        $rol = 1; // Si no, asignar rol 1
    }

    // Verificar si los campos de nombre de usuario, contraseña, correo electrónico y dirección están vacíos
    if(empty($username) || empty($password) || empty($email) || empty($address)){
        $message = "Nombre de usuario, contraseña, correo electrónico y dirección son obligatorios";
        echo "<p class='message error'>" . $message . "</p>";
    } else {
        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Conexión a la base de datos
        $conn = connectDB();

        // Verificar si la conexión fue exitosa
        if($conn){
            // Comenzar una transacción
            $conn->beginTransaction();

            try {
                // Preparar la consulta para insertar un nuevo usuario en la base de datos
                $query_user = "INSERT INTO usuarios (username, password, email, address, rol) VALUES (:username, :password, :email, :address, :rol)";
                $statement_user = $conn->prepare($query_user);

                $statement_user->bindParam(":username", $username);
                $statement_user->bindParam(":password", $hashed_password); // Utilizar la contraseña hasheada
                $statement_user->bindParam(":email", $email);
                $statement_user->bindParam(":address", $address);
                $statement_user->bindParam(":rol", $rol);

                // Ejecutar la consulta para insertar el nuevo usuario
                $statement_user->execute();

                // Obtener el ID del usuario recién insertado
                $user_id = $conn->lastInsertId();

                // Calcular el precio total del pedido
                $total_price = 0;

                // Recorrer los productos en el carrito
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    // Consultar el precio del producto en la base de datos
                    $stmt_product = $conn->prepare("SELECT price FROM productos WHERE id = ?");
                    $stmt_product->execute([$product_id]);
                    $product = $stmt_product->fetch(PDO::FETCH_ASSOC);

                    // Calcular el subtotal del producto (precio * cantidad)
                    $subtotal = $product['price'] * $quantity;

                    // Agregar el subtotal al precio total del pedido
                    $total_price += $subtotal;
                }

                // Obtener la fecha actual
                $date = date("Y-m-d H:i:s");

                // Preparar la consulta para insertar un nuevo pedido en la base de datos
                $query_order = "INSERT INTO pedidos (user_id, total_price, date) VALUES (:user_id, :total_price, :date)";
                $statement_order = $conn->prepare($query_order);

                $statement_order->bindParam(":user_id", $user_id);
                $statement_order->bindParam(":total_price", $total_price);
                $statement_order->bindParam(":date", $date);

                // Ejecutar la consulta para insertar el nuevo pedido
                $statement_order->execute();

                // Confirmar la transacción
                $conn->commit();

                $message = "Usuario registrado correctamente y pedido creado";
                echo "<p class='message success'>" . $message . "</p>";
                header("Refresh: 3; url=./login.php");
                exit();
            } catch (PDOException $e) {
                // Revertir la transacción en caso de error
                $conn->rollback();
                
                $message = "Error al registrar al usuario y crear el pedido: " . $e->getMessage();
                echo "<p class='message error'>" . $message . "</p>";
                header("Refresh: 3; url=" . $_SERVER['PHP_SELF']); 
            }
        } else {
            echo "<p class='message error'>Error al conectar a la Base de Datos </p>";
        }
    }
}

?>
