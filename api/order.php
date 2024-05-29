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
<a class="home-button" href="my_cart.php"><img src="../assets/img/icons/goBack.png" alt="home"></a>
        <div><h1 class="title">Detalles del Pedido</h1></div>
        <div class="order-container">
            <!-- Mostrar detalles del pedido -->
            <div class="order">
                <div class="order-details" id="order-details">
                <?php foreach($cartProducts as $producto): ?>
                    <div class="product-container">
                        <img src="../assets/img/productos/<?= $producto['img'] ?>" alt="<?= $producto['name'] ?>">
                        <div class="product-element parragraf"><?= $producto['name'] ?></div>
                        <div class="product-element parragraf"><?= $producto['price'] ?> €</div>
                        <div class="product-element parragraf">x<?= $_SESSION['cart'][$producto['id']] ?></div>
                        <div class="product-element parragraf">= <?= $_SESSION['cart'][$producto['id']] * $producto['price'] ?> €</div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="total-price parragraf">Coste del pedido= <?=$totalPrice?> €</div>
                <div id="bank-transfer-instructions" >
                    <p class="parragraf">Por favor, realice la transferencia al siguiente número de cuenta:</p>
                    <p class="parragraf"><strong>Banco:</strong>&nbsp;&nbsp;&nbsp; XYZ Bank</p>
                    <p class="parragraf"><strong>IBAN:</strong>&nbsp;&nbsp;&nbsp; ES00 0000 0000 0000 0000</p>
                    <p class="parragraf"><strong>SWIFT:</strong>&nbsp;&nbsp;&nbsp; ABCDESMMXXX</p>
                    <p class="parragraf">*Asegúrese de incluir su nombre y el ID del pedido en la referencia de la transferencia.</p>
                </div>
                </div>
            <div class="order-form-container">
                <div class="data-container">
                    <div id="order-login">
                        <button id="mostrarFormulario">Iniciar Sesión</></button>
                    </div>
                    <form id="loginForm" class="enter" style="display:none;">
                        <div>
                            <input type="email" name="email" id="email" placeholder="E-mail"required>
                        </div>
                        <div>
                            <input type="password" name="password" id="password" placeholder="Contraseña" required>
                        </div>
                        <div class='btn-container'>
                            <button class='btn insertar' type='submit'>Iniciar Sesión</button>
                        </div>
                    </form>
                    <div id="loginMessage"></div>
                    <form id="orderForm">
                        <div>
                            <input type="text" id="name" name="name" placeholder="Nombre"  value="<?= htmlspecialchars($user['name']); ?>" required>
                            <input type="text" id="surname" name="surname" placeholder="Apellidos"  value="<?= htmlspecialchars($user['surname']); ?>" required>
                        </div>
                        <?php if($isLoggedIn): ?>
                        <input type="email" id="email" name="email" placeholder="E-mail"  value="<?= htmlspecialchars($user['email']); ?>" readonly>
                        <input type="password" id="password" name="password" placeholder="Contraseña" value="<?= htmlspecialchars($user['pasword']); ?>" readonly>
                        <?php else: ?>
                        <input type="email" id="email" name="email" placeholder="E-mail"  value="<?= htmlspecialchars($user['email']); ?>" required>
                        <input type="password" id="password" name="password" placeholder="Contraseña" requiered>
                        <?php endif; ?>
                        <input type="text" id="address" name="address" placeholder="Dirección de envío"  value="<?= htmlspecialchars($user['address']); ?>" required>
                        <div>
                            <input type="text" id="postal_code" name="postal_code" placeholder="Código Postal"  value="<?= htmlspecialchars($user['postal_code']); ?>" required>
                            <input type="text" id="location" name="location" placeholder="Localidad"  value="<?= htmlspecialchars($user['location']); ?>" required>
                            <input type="text" id="country" name="country" placeholder="Pais"  value="<?= htmlspecialchars($user['country']); ?>" required>
                        </div>
                        <div>
                            <input type="tel" id="phone" name="phone" placeholder="Teléfono"  value="<?= htmlspecialchars($user['phone']); ?>" required>
                                <select id="payment-method" name="payment_method" required>
                                    <option value="contrareembolso">Contra Reembolso</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                </select> <br>
                        </div>    
                        <button type="submit" id="place-order-btn">Realizar Pedido</button>
                </form>
                <div id="order-message"></div>
            </div>
        </div>
</div>
<script src="../scripts/order.js"></script>
</body>
</html>
