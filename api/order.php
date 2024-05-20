<?php
include("header.php");


// Establecer la conexión a la base de datos
$conn = connectDB();

function getCartProducts($pdo) {
    if (empty($_SESSION['cart'])) {
        return [];
    }

    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM productos WHERE id IN ($ids)");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Verificar si la conexión a la base de datos fue exitosa
if (!$conn) {
    echo "Error al conectar a la base de datos";
    exit();
}

$cartProducts = getCartProducts($conn);
$totalPrice = 0;
foreach ($cartProducts as $producto) {
    $totalPrice += $producto['price'] * $_SESSION['cart'][$producto['id']];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido</title>
    <link rel="stylesheet" href="../styles/order.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="page">
    <div class="order-container">
        <h1>Detalles del Pedido</h1>
        <!-- Mostrar detalles del pedido -->
        <div id="order-details">
            <?php foreach($cartProducts as $producto): ?>
                <div class="product-container">
                    <img src="../assets/img/productos/<?= $producto['img'] ?>" alt="<?= $producto['name'] ?>">
                    <div class="product-name"><?= $producto['name'] ?></div>
                    <div class="product-quantity"><?= $_SESSION['cart'][$producto['id']] ?></div>
                    <div class="product-price"><?= $producto['price'] ?> €</div>
                </div>
            <?php endforeach; ?>
            <h2>Total: <span id="total-price"><?= $totalPrice ?> €</span></h2>
        </div>
        <h2>Datos de Usuario y Envío</h2>
        <form id="order-form">
            <label for="username">Nombre Completo:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="address">Dirección de Envío:</label>
            <input type="text" id="address" name="address" required>
            <!-- Otros campos del formulario -->
            <button type="submit" id="place-order-btn">Realizar Pedido</button>
        </form>
        <div id="order-message"></div>
    </div>
</div>
<script src="../scripts/order.js"></script>
</body>
</html>
