<?php
include("header.php");

$conn = connectDB();

$order_id = $_GET['order_id'];

$stmt = $conn->prepare("SELECT p.id, p.total_price, p.date, p.payment_method, u.username, u.email, u.address FROM pedidos p JOIN usuarios u ON p.id_usuario = u.id WHERE p.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT pp.quantity, pp.price, pr.name FROM pedidos_productos pp JOIN productos pr ON pp.product_id = pr.id WHERE pp.pedido_id = ?");
$stmt->execute([$order_id]);
$order_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="../styles/order-confirmation.css">
</head>
<body>
<div class="page">
    <div class="order-confirmation">
        <h1>Pedido Confirmado</h1>
        <img src="../assets/img/icons/check.svg" alt="check">
        <p class="parragraf">Gracias por su compra, <?= htmlspecialchars($order['username']) ?>!</p>
        <p class="parragraf">Hemos recibido su pedido y estamos procesándolo.</p>
    </div>
    <div class="order-details">
        <h2>Detalles del Pedido</h2>
        <p><strong>ID del Pedido:</strong> <?= $order_id ?></p>
        <p><strong>Fecha:</strong> <?= $order['date'] ?></p>
        <p><strong>Método de Pago:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <p><strong>Total:</strong> <?= $order['total_price'] ?> €</p>
        <h2>Productos:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= $product['quantity'] ?></td>
                            <td><?= $product['price'] ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>
    <div class="shipping-data">
        <h2>Datos de Envío</h2>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($order['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($order['address']) ?></p>

        <?php if ($order['payment_method'] == 'transferencia'): ?>
            <h2>Instrucciones para la Transferencia Bancaria</h2>
            <p>Por favor, realice la transferencia al siguiente número de cuenta:</p>
            <p><strong>Banco:</strong> XYZ Bank</p>
            <p><strong>IBAN:</strong> ES00 0000 0000 0000 0000</p>
            <p><strong>SWIFT:</strong> ABCDESMMXXX</p>
            <p>Asegúrese de incluir su nombre y el ID del pedido en la referencia de la transferencia.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
