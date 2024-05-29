<?php
include("header.php");

$conn = connectDB();

$order_id = $_GET['order_id'];

$stmt = $conn->prepare("SELECT p.id, p.total_price, p.date, p.payment_method, u.name, u.surname, u.email, u.address, u.postal_code, u.location, u.country FROM pedidos p JOIN usuarios u ON p.id_usuario = u.id WHERE p.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT pp.quantity, pp.price, pr.name, pr.img FROM pedidos_productos pp JOIN productos pr ON pp.product_id = pr.id WHERE pp.pedido_id = ?");
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
        <p class="parragraf">Gracias por su compra, <?= htmlspecialchars($order['name'])?> <?= htmlspecialchars($order['surname'])?>!</p>
        <p class="parragraf">Hemos recibido su pedido y estamos procesándolo.</p>
    </div>
    <div class="order-container">
        <div>
            <div class="order-details">
                <h2>Detalles del Pedido</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Numero Pedido</th>
                            <th>Fecha</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td><?= $order_id ?></td>
                                <td><?= $order['date'] ?></td>
                                <td style="white-space:nowrap;"><?= $order['total_price'] ?>€</td>
                            </tr>
                    </tbody>
                </table>
            </div>
            <div class="shipping-data">
            <h2>Datos de Envío</h2>
            <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>E-mail</th>
                            <th>Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td><?= htmlspecialchars($order['name'])?> <?= htmlspecialchars($order['surname'])?></td>
                                <td><?= htmlspecialchars($order['email']) ?></td>
                                <td><?= htmlspecialchars($order['address']) ?>,&nbsp;&nbsp;  
                                <?= htmlspecialchars($order['postal_code']) ?>,&nbsp;&nbsp; 
                                <?= htmlspecialchars($order['location']) ?>,&nbsp;&nbsp; 
                                <?= htmlspecialchars($order['country']) ?>.</td>
                            </tr>
                    </tbody>
                </table>

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
        <div class="order-products">
            <h2>Productos Pedidos</h2>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_products as $product): ?>
                            <tr>
                                <td><img src="../assets/img/productos/<?= $product['img'] ?>" alt="<?= $product['name'] ?>"></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= $product['quantity'] ?></td>
                                <td style="white-space:nowrap;"><?= $product['price'] ?>€</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>
</body>
</html>
