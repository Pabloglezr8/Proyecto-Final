<?php
session_start();
header('Content-Type: application/json');

// Conectar a la base de datos
include("connectDB.php");
$conn = connectDB();

$response = ['status' => false, 'message' => 'Error al procesar el pedido.'];

// Función para validar y limpiar la entrada del usuario
function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verificar si hay datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(print_r($_POST, true));  // Registro de los datos POST recibidos para depuración

    // Comprobación de los campos requeridos
    $required_fields = ['name', 'surname', 'email', 'password', 'address', 'postal_code', 'location', 'country', 'phone', 'payment_method', 'shipment_method'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode($response);
            exit;
        }
    }

    // Validaciones
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['name'])) {       
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['surname'])) {        
        echo json_encode($response);
        exit;
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {       
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^\d{5}$/", $_POST['postal_code'])) {        
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['location'])) {        
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['country'])) {        
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[0-9]{9}$/", $_POST['phone'])) {    
        echo json_encode($response);
        exit;
    }

    $name = validateInput($_POST['name']);
    $surname = validateInput($_POST['surname']);
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $address = validateInput($_POST['address']);
    $postalCode = validateInput($_POST['postal_code']);
    $location = validateInput($_POST['location']);
    $country = validateInput($_POST['country']);
    $phone = validateInput($_POST['phone']);
    $paymentMethod = validateInput($_POST['payment_method']);
    $shipmentMethod = validateInput($_POST['shipment_method']);
    $totalPrice = 0;

    try {
        // Verificar si el usuario ya existe en la base de datos
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Iniciar la transacción
        $conn->beginTransaction();

        if ($existingUser) {
            // Actualizar los datos del usuario existente
            $stmt = $conn->prepare("UPDATE usuarios SET name = ?, surname = ?, address = ?, postal_code = ?, location = ?, country = ?, phone = ?, payment_method = ?, shipment_method = ? WHERE email = ?");
            if (!$stmt->execute([$name, $surname, $address, $postalCode, $location, $country, $phone, $paymentMethod, $shipmentMethod, $email])) {
                throw new Exception('Error al actualizar los datos del usuario.');
            }
            $userId = $existingUser['id'];
        } else {
            // Insertar un nuevo usuario si no existe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 1; // Usuario normal

            $stmt = $conn->prepare("INSERT INTO usuarios (name, surname, email, password, address, postal_code, location, country, phone, payment_method, shipment_method, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt->execute([$name, $surname, $email, $hashedPassword, $address, $postalCode, $location, $country, $phone, $paymentMethod, $shipmentMethod, $role])) {
                throw new Exception('Error al insertar en la tabla usuarios.');
            }
            $userId = $conn->lastInsertId();
        }

        // Calcular el precio total del pedido
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $stmt = $conn->prepare("SELECT price FROM productos WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $totalPrice += $product['price'] * $quantity;
            } else {
                throw new Exception("Producto con ID $productId no encontrado.");
            }
        }

        // Insertar pedido en la tabla pedidos
        $stmt = $conn->prepare("INSERT INTO pedidos (id_usuario, total_price, date, payment_method, shipment_method) VALUES (?, ?, NOW(), ?,?)");
        if (!$stmt->execute([$userId, $totalPrice, $paymentMethod, $shipmentMethod])) {
            throw new Exception('Error al insertar en la tabla pedidos.');
        }
        $pedidoId = $conn->lastInsertId();

        // Insertar productos en la tabla pedidos_productos
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $stmt = $conn->prepare("SELECT price FROM productos WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $conn->prepare("INSERT INTO pedidos_productos (pedido_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            if (!$stmt->execute([$pedidoId, $productId, $quantity, $product['price']])) {
                throw new Exception('Error al insertar en la tabla pedidos_productos.');
            }
        }

        // Confirmar la transacción
        $conn->commit();

        // Limpiar el carrito
        unset($_SESSION['cart']);

        $response['status'] = true;
        $response['message'] = 'Pedido realizado con éxito.';
        $response['order_id'] = $pedidoId;

    } catch (Exception $e) {
        // Revertir la transacción
        $conn->rollBack();
        $response['message'] = 'Error al procesar el pedido: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
exit;
?>
