<?php
session_start();
header('Content-Type: application/json');

// Conectar a la base de datos
include("connectDB.php");
$conn = connectDB();

$response = ['status' => false, 'message' => 'Error al procesar el pedido.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['payment_method'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $paymentMethod = $_POST['payment_method'];
        $totalPrice = 0;

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

        try {
            // Generar una contraseña aleatoria
            $password = bin2hex(random_bytes(8));
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 1; // Usuario normal

            // Iniciar la transacción
            $conn->beginTransaction();

            // Insertar usuario en la tabla usuarios
            $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email, address, role) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt->execute([$username, $hashedPassword, $email, $address, $role])) {
                throw new Exception('Error al insertar en la tabla usuarios.');
            }
            $userId = $conn->lastInsertId();

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
