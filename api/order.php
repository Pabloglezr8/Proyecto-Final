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
            </div>

            <div class="order-form-container">
                <h2>Datos del Usuario</h2>
            <?php if(!$isLoggedIn): ?>
            <div id="order-login">
                        <button id="mostrarFormulario">Iniciar Sesión</></button>
            </div>
            <?php endif; ?>
            <form id="loginForm" class="enter">
                            <input type="email" name="email" id="email" placeholder="E-mail">
                            <input type="password" name="password" id="password" placeholder="Contraseña" >
                            <button class='btn insertar' type='submit'>Iniciar Sesión</button>
            </form>
            <div id="loginMessage"></div>

            <form id="orderForm">
                <div class="user-form">
                    <div class="input-cont">
                        <div class="name-cont">
                            <label for="name">Nombre</label>
                            <input type="text" id="name" name="name" placeholder="Nombre"  value="<?= htmlspecialchars($user['name']); ?>" >
                        </div>
                        <div class="surname-cont">
                            <label for="surname">Apellidos</label>
                            <input type="text" id="surname" name="surname" placeholder="Apellidos"  value="<?= htmlspecialchars($user['surname']); ?>" >
                        </div>
                    
                    </div>
                    <?php if($isLoggedIn): ?>
                    <label for="email" style="display:none">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="E-mail"  value="<?= htmlspecialchars($user['email']); ?>" style="display:none">
                    
                    <label for="password" style="display:none">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Contraseña" value="<?= htmlspecialchars($user['password']); ?>" style="display:none">
                    <?php else: ?>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="E-mail"  value="<?= htmlspecialchars($user['email']); ?>" >
                    
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Contraseña" requiered>
                    <?php endif; ?>
                    <label for="address">Dirección</label>
                    <input type="text" id="address" name="address" placeholder="Dirección de envío"  value="<?= htmlspecialchars($user['address']); ?>" >
                    
                    <div class="input-cont">
                        <div class="pcode-cont">
                            <label for="postal_code">Código Postal</label>
                            <input type="text" id="postal_code" name="postal_code" placeholder="Código Postal"  value="<?= htmlspecialchars($user['postal_code']); ?>" >
                        </div>
                        <div class="location-cont">
                            <label for="location">Localidad</label>
                            <input type="text" id="location" name="location" placeholder="Localidad"  value="<?= htmlspecialchars($user['location']); ?>" >
                        </div>
                    </div>
                    <div class="input-cont">  
                        <div class="country-cont">
                            <label for="country">País</label>
                            <input type="text" id="country" name="country" placeholder="Pais"  value="<?= htmlspecialchars($user['country']); ?>" >
                        </div>
                        <div class="phone-cont">  
                            <label for="phone">Teléfono</label>
                            <input type="text" id="phone" name="phone" placeholder="Teléfono"  value="<?= htmlspecialchars($user['phone']); ?>" >
                        </div>
                    </div>
                </div>

                <div class="add-form">
                    <div class="payment-form">
                        <h2>Metodo de Pago</h2>
                        <select class="form-select" id="payment-method" name="payment_method" >
                            <option value="">Selecciona Método Pago</option>
                            <option value="contrareembolso">Contra Reembolso</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                        </select> <br>
                    </div>

                    <div class="shipment-form">
                        <h2>Metodo de Envío</h2>
                        <select class="form-select" id="shipment-method" name="shipment_method" >
                            <option value="">Selecciona Método Envío</option>
                            <option value="Envío24h">Envío 24h (+10€)</option>
                            <option value="Envío Normal">Envío Normal(+5€)</option>
                        </select> <br>
                    </div>
                </div>
                <div id="bank-transfer-instructions" >
                    <p>Por favor, realice la transferencia al siguiente número de cuenta:</p>
                    <p><strong>Banco:</strong>&nbsp;&nbsp;&nbsp; XYZ Bank</p>
                    <p><strong>SWIFT:</strong>&nbsp;&nbsp;&nbsp; ABCDESMMXXX</p>
                    <p><strong>IBAN:</strong>&nbsp;&nbsp;&nbsp; ES00 0000 0000 0000 0000</p>
                    <p>*Asegúrese de incluir su nombre y el ID del pedido en la referencia de la transferencia.</p>
                </div>
                <div class="total-price parragraf" data-total-price="<?= $totalPrice ?>">Coste del pedido= <?= $totalPrice ?> €</div>
                <div id="order-message"></div>
                <button type="submit" id="place-order-btn">Realizar Pedido</button>
            </form>
            </div>

        </div>
<?php
include("footer.php");
?>
    </div>
    
<script src="../scripts/order.js"></script>
</body>
</html>
