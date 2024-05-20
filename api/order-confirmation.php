<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado</title>
    <link rel="stylesheet" href="../styles/confirmation.css">
</head>
<body>
<div class="page">
    <div class="confirmation-container">
        <h1>Pedido Confirmado</h1>
        <p>Gracias por tu compra. Tu pedido ha sido realizado con éxito.</p>
        
        <!-- Mostrar detalles del pedido -->
        <h2>Detalles del Pedido</h2>
        <p><strong>Nombre:</strong> <?php echo $_SESSION['order_details']['username']; ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo $_SESSION['order_details']['email']; ?></p>
        <p><strong>Dirección de Envío:</strong> <?php echo $_SESSION['order_details']['address']; ?></p>
        <p><strong>Total:</strong> <?php echo $_SESSION['order_details']['total_price']; ?> €</p>
        
        <h3>Productos:</h3>
        <ul>
            <?php foreach($_SESSION['order_details']['products'] as $product): ?>
                <li><?php echo $product['name']; ?> - Cantidad: <?php echo $product['quantity']; ?></li>
            <?php endforeach; ?>
        </ul>
        
        <a href="shop.php"><button>Seguir Comprando</button></a>
    </div>
</div>
</body>
</html>
