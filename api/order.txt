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
if(!$conn){
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
    <title>Confirmar Pedido</title>
    <link rel="stylesheet" href="../styles/order_confirmation.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="page">
    <div class="order-confirmation-container">
        <h1>Confirmar Pedido</h1>
        <form id="order-form">
            <h2>Detalles del Pedido</h2>
            <?php if(count($cartProducts) > 0): ?>
                <ul>
                    <?php foreach($cartProducts as $producto): ?>
                        <li>
                            <div><?= $producto['name'] ?> - <?= $producto['price'] ?> € x <?= $_SESSION['cart'][$producto['id']] ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="total">
                    <h2>Total: <?= $totalPrice ?> €</h2>
                </div>
            <?php else: ?>
                <p>No hay productos en la cesta</p>
            <?php endif; ?>

            <h2>Datos del Usuario</h2>
            <form method="post" class="enter">
        <div>
            <label for="username">Nombre de usuario</label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <label for="address">Dirección</label>
            <input type="address" name="address" id="address">
        </div>

            <button type="button" id="finalize-order-btn">Finalizar Pedido</button>
        </form>
    </div>
</div>
<script src="../scripts/order-confirmation.js"></script>
</body>
</html>
