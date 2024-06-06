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

    // Verificar si el carrito está vacío
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        $response['message'] = 'El carrito está vacío. No se puede procesar el pedido.';
        echo json_encode($response);
        exit;
    }

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
        $stmt = $conn->prepare("INSERT INTO pedidos (id_usuario, total_price, date, payment_method, shipment_method) VALUES (?, ?, NOW(), ?, ?)");
        if (!$stmt->execute([$userId, $totalPrice, $paymentMethod, $shipmentMethod])) {
            throw new Exception('Error al insertar en la tabla pedidos.');
        }
        $pedidoId = $conn->lastInsertId();

        // Insertar estado inicial del pedido en la tabla estado_pedidos
        $estadoInicial = "En espera"; // Estado inicial del pedido
        $stmt = $conn->prepare("INSERT INTO estado_pedidos (pedido_id, estado) VALUES (?, ?)");
        if (!$stmt->execute([$pedidoId, $estadoInicial])) {
            throw new Exception('Error al insertar en la tabla estado_pedidos.');
        }

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

        // Enviar correo electrónico de confirmación
        $to = $email;
        $subject = "Confirmación de Pedido";
        $message = "
        <html>
        <head>
            <title>Confirmación de pedido</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #1c1c1c;
                }
                .container {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                    background-color: #f9f9f9;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .header .logo {
                    max-width: 200px;
                    margin-bottom: 10px;
                }
                .header .title {
                    font-size: 24px;
                    color: #0e3083;
                }
                .saludo {
                    text-align: center;
                    margin-bottom: 20px;
                    color: #0e3083;
                }
                .details {
                    margin: 20px 0;
                    padding: 20px;
                    background-color: #c1c1c1;
                    border-radius: 10px;
                }
                .details p {
                    margin: 5px 0;
                }
                table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ccc;
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #0e3083;
                    color: #fff;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                .total-price {
                    text-align: center;
                    margin: 20px 0;
                    padding: 10px;
                    background-color: #0e3083;
                    color: #fff;
                    border-radius: 10px;
                }
                .footer {
                    text-align: center;
                    padding: 10px;
                    border-top: 2px solid #f29400;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='https://practicas.pagespeedwordpress.com/assets/img/logoCompleto.png' alt='Ferreteria Vegagrande' class='logo'/>
                    <h1 class='title'>Gracias por tu compra</h1>
                </div>
                <div class='saludo'>
                    <p><strong>Hola $name $surname,</strong></p>
                    <p><strong>Aquí están los detalles de tu compra:</strong></p>
                </div>
                <div class='details'>
                    <p><strong>Número de Pedido:</strong> $pedidoId</p>
                    <p><strong>Fecha del Pedido:</strong> " . date('d-m-Y H:i:s') . "</p>
                    <p><strong>Nombre:</strong> $name $surname</p>
                    <p><strong>Dirección:</strong> $address, $postalCode, $location, $country</p>
                    <p><strong>Método de Pago:</strong> $paymentMethod</p>
                    <p><strong>Método de Envío:</strong> $shipmentMethod</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>";
        
        // Generar filas de productos comprados
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $stmt = $conn->prepare("SELECT name, price FROM productos WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $productName = $product['name'];
            $productPrice = $product['price'];
            $productTotal = $productPrice * $quantity;
        
            $message .= "
                        <tr>
                            <td>$productName</td>
                            <td>$quantity</td>
                            <td>$productPrice €</td>
                            <td>$productTotal €</td>
                        </tr>";
        }
        
        $message .= "
                    </tbody>
                </table>
                <p class='total-price'><strong>Total del Pedido:</strong> $totalPrice €</p>
                <div class='footer'>
                    <p>Saludos,<br>Ferretería Vegagrande</p>
                </div>
            </div>
        </body>
        </html>";
        
        $headers = "From: no-reply@tu-tienda.com";

        if (mail($to, $subject, $message, $headers)) {
            error_log("Correo de confirmación enviado a $email");
        } else {
            error_log("Error al enviar el correo de confirmación a $email");
        }

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
