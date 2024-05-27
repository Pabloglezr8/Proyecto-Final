<?php
session_start();
header('Content-Type: application/json');

// Conectar a la base de datos
include("connectDB.php");
$conn = connectDB();

$response = ['status' => false, 'message' => 'Error al procesar el pedido.'];

if (isset($_SESSION['id'])) {
    $user = [
        'name' => $_SESSION['name'],
        'surname' => $_SESSION['surname'],
        'pasword' => $_SESSION['password'],
        'email' => $_SESSION['email'],
        'address' => $_SESSION['address'],
        'postal_code' => $_SESSION['postal_code'],
        'location' => $_SESSION['location'],
        'country' => $_SESSION['country'],
        'phone' => $_SESSION['phone'],
        'role' => $_SESSION['role'],
    ];
    $isLoggedIn = true;
    

} else {
    $user = [
        'name' => '',
        'surname' => '',
        'email' => '',
        'address' => '',
        'postal_code' => '',
        'location' => '',
        'country' => '',
        'phone' => '',
        'role' => ''
    ];
    $isLoggedIn = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['address']) && isset($_POST['postal_code']) && isset($_POST['location']) && isset($_POST['country']) && isset($_POST['phone']) && isset($_POST['payment_method'])) {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $postalCode = $_POST['postal_code'];
        $location = $_POST['location'];
        $country = $_POST['country'];
        $phone = $_POST['phone'];
        $paymentMethod = $_POST['payment_method'];
        $totalPrice = 0;

        // Verificar si el usuario ya existe en la base de datos
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        try {

            if ($existingUser) {
                if (!$isLoggedIn) {
                    $response['message'] = 'El correo electrónico ya está en uso.';
                    echo json_encode($response);
                    exit;
                }
            }

            // Hash de la contraseña solo si es una nueva contraseña
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            } else {
                // Usar la contraseña existente si no se proporciona una nueva
                $hashedPassword = $existingUser['password'];
            }

            // Iniciar la transacción
            $conn->beginTransaction();

            if ($existingUser) {
                // Actualizar los datos del usuario existente
                $stmt = $conn->prepare("UPDATE usuarios SET name = ?, surname = ?, address = ?, postal_code = ?, location = ?, country = ?, phone = ? WHERE email = ?");
                if (!$stmt->execute([$name, $surname, $address, $postalCode, $location, $country, $phone, $email])) {
                    throw new Exception('Error al actualizar los datos del usuario.');
                }
                $userId = $existingUser['id'];
            } else {
                // Insertar un nuevo usuario si no existe
                $role = 1; // Usuario normal

                $stmt = $conn->prepare("INSERT INTO usuarios (name, surname, email, password, address, postal_code, location, country, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt->execute([$name, $surname, $email, $hashedPassword, $address, $postalCode, $location, $country, $phone, $role])) {
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
                    $response['message'] = "Producto con ID $productId no encontrado.";
                    echo json_encode($response);
                    exit;
                }
            }

            // Insertar pedido en la tabla pedidos
            $stmt = $conn->prepare("INSERT INTO pedidos (id_usuario, total_price, date, payment_method) VALUES (?, ?, NOW(), ?)");
            if (!$stmt->execute([$userId, $totalPrice, $paymentMethod])) {
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
        $response['message'] = 'Faltan datos requeridos.';
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
exit;
?>
