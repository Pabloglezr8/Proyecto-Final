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
    <title>Mi Carrito</title>
    <link rel="stylesheet" href="../styles/my_cart.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
 <div class="page">
    <div class="cart-container">
        <h1>Carrito</h1>
        <?php if(count($cartProducts) > 0): ?>
            <?php foreach($cartProducts as $producto): ?>
            <div class="product-container">
                <div class="product-delete">
                    <button class="remove-all-from-cart-btn" data-product-id="<?= $producto['id'] ?>">X</button>
                </div>
                <div class="product-data">
                    <img src="../assets/img/productos/<?= $producto['img'] ?>" alt="<?= $producto['name'] ?>">
                    <div class="product-text">
                        <div class="product-name"><?= $producto['name'] ?></div>
                        <div class="product-price"><?= $producto['price'] ?> €</div>
                    </div>
                    <div class="product-actions">
                        <div class="product-quantity">
                            <button class="increase-quantity-btn" id="add-quantity" data-product-id="<?= $producto['id'] ?>">+</button>
                            <span class="cart-quantity" data-product-id="<?= $producto['id'] ?>"><?= $_SESSION['cart'][$producto['id']] ?></span>
                            <button class="remove-from-cart-btn" id="delete-quantity" data-product-id="<?= $producto['id'] ?>">-</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="cart-total">
                <h2>Total: <span id="total-price"><?= $totalPrice ?> €</span></h2>
                <div >
                    <a href="order-confirmation.php">
                        <button id="checkout-btn">Realizar Pedido</button>
                    </a>
                    <button id="clear-cart-btn">Vaciar Cesta</button>
                </div>
            </div>
        <?php else: ?>
            <h2>No tienes productos añadidos a tu carrito</h2>
            <a href="shop.php"><button>Comprar</button></a>
        <?php endif; ?>
    </div>
</div>
<script src="../scripts/cart-actions.js"></script>
</body>
</html>
