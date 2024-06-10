<?php

include("connectDB.php");

session_start();

$conn = connectDB();

if (isset($_SESSION['id'])) {
    // Obtener los datos actualizados del usuario desde la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($updatedUser) {
        // Actualizar los datos de la sesión con los datos actualizados
        $_SESSION['name'] = $updatedUser['name'];
        $_SESSION['surname'] = $updatedUser['surname'];
        $_SESSION['password'] = $updatedUser['password'];
        $_SESSION['email'] = $updatedUser['email'];
        $_SESSION['address'] = $updatedUser['address'];
        $_SESSION['postal_code'] = $updatedUser['postal_code'];
        $_SESSION['location'] = $updatedUser['location'];
        $_SESSION['country'] = $updatedUser['country'];
        $_SESSION['phone'] = $updatedUser['phone'];
        $_SESSION['payment_method'] = $updatedUser['payment_method'];
        $_SESSION['shipment_method'] = $updatedUser['shipment_method'];
        $_SESSION['role'] = $updatedUser['role'];
    }

    // Cargar los datos del usuario desde la sesión
    $user = [
        'name' => $_SESSION['name'],
        'surname' => $_SESSION['surname'],
        'password' => $_SESSION['password'],
        'email' => $_SESSION['email'],
        'address' => $_SESSION['address'],
        'postal_code' => $_SESSION['postal_code'],
        'location' => $_SESSION['location'],
        'country' => $_SESSION['country'],
        'phone' => $_SESSION['phone'],
        'payment_method' => $_SESSION['payment_method'],
        'shipment_method' => $_SESSION['shipment_method'],
        'role' => $_SESSION['role']
    ];
    $isLoggedIn = true;

} else {
    // Usuario no está logueado
    $user = [
        'name' => '',
        'surname' => '',
        'email' => '',
        'address' => '',
        'postal_code' => '',
        'location' => '',
        'country' => '',
        'phone' => '',
        'payment_method' => '',
        'shipment_method' => '',
        'role' => ''
    ];
    $isLoggedIn = false;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total_productos = 0;
foreach ($_SESSION['cart'] as $prod) {
    $total_productos += $prod;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferretería Vegagrande</title>
    <link rel="stylesheet" href="/FerreteriaVegagrande/styles/header.css">
    <link rel="shortcut icon" href="/FerreteriaVegagrande/favicon.ico" type="image/x-icon">
</head>

<body>
    <header class="index-header">
        <div class="header-top-container">
            <div class="identity-container">
                <div class="Logo-container">
                    <a href="/FerreteriaVegagrande/index.php">
                        <img id="logo" src="/FerreteriaVegagrande/assets/img/Logo.png" alt="Logo">
                        <img id="logo-completo" src="/FerreteriaVegagrande/assets/img/LogoCompleto.png" alt="Logo">
                    </a>
                </div>
                <div class="identity-title-container">
                    <a href="/FerreteriaVegagrande/index.php">
                        <h1 class="identity-title">Ferretería Vegagrande</h1>
                        <h2 class="identity-subtitle">Donde comienza la mejora de tu hogar</h2>
                    </a>
                </div>
            </div>
            <!-- <div class="menu-container"></div> -->
            <!-- Botón hamburguesa para dispositivos móviles -->
            <div class="menu-toggle" id="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>


            <!-- Menú de usuario para dispositivos de escritorio -->
            <nav class="user-menu hidden">
                <div class="menu-element cart-icon">
                    <a href="/FerreteriaVegagrande/templates/my_cart.php"><span id="cart-count"><?php echo $total_productos; ?></span><img src="/FerreteriaVegagrande/assets/img/icons/shopcart.png" alt="shopcart"></a>
                </div>
                <?php if($isLoggedIn): ?>
                    <div class="menu-element">
                        <a class="my-account" href="/FerreteriaVegagrande/templates/user.php">
                            <img src="/FerreteriaVegagrande/assets/img/icons/myaccount.png" alt="">
                            <span class="user-name"><?php echo $_SESSION['name'];?></span>
                        </a>
                    </div>                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/logout-process.php">LogOut</a></div>
                <?php else: ?>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/templates/login.php">LogIn</a></div>
                <?php endif; ?>
                <?php if(!$isLoggedIn): ?>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/templates/register.php">Register</a></div>
                <?php endif; ?>
            </nav>
        </div>
        

        <!-- Menú de navegación para dispositivos de escritorio -->
        <div class="menu-container navigation-menu-container hidden">
            <nav class="navigation-menu">
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/aboutus.php">Quiénes somos</a></div>
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/contact.php">Dónde estamos</a></div>
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/shop.php">Tienda</a></div>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 0): ?>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/templates/admin.php">Panel de Administrador</a></div>
                <?php endif; ?>
            </nav>
            
        </div>
        <div id="success-message" class="message">
            <div><p>Producto añadido al carrito correctamente.</p></div>
        </div>
        <div id="error-message" class="message">
            <div><p>Error al añadir el producto al carrito.</p></div>
        </div>

    </header>

    <!-- Menú desplegable para dispositivos móviles -->
    <div class="hamburger-container">
        <nav class="burger-menu">
            <div class=" user-menu-burger">
                <div class="menu-element cart-icon">
                    <a href="/FerreteriaVegagrande/templates/my_cart.php"><span id="cart-count-burger"><?php echo $total_productos; ?></span><img src="/FerreteriaVegagrande/assets/img/icons/shopcart.png" alt="shopcart"></a>
                </div>
                <?php if($isLoggedIn): ?>
                    <div class="menu-element">
                        <a class="my-account" href="/FerreteriaVegagrande/templates/user.php">
                            <img src="/FerreteriaVegagrande/assets/img/icons/myaccountOrange.png" alt="">
                            <span class="user-name"><?php echo $_SESSION['name'];?></span>
                        </a>
                    </div>
                <div class="menu-element"><a href="/FerreteriaVegagrande/api/logout-process.php"><img class="logout" src="/assets/img/icons/logout.png" alt=""></a></div>
                <?php else: ?>
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/login.php">LogIn</a></div>
                <?php endif; ?>
                <?php if(!$isLoggedIn): ?>
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/register.php">Register</a></div>
                <?php endif; ?>
            </div>
            <div class="navigation-menu-burger">
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/aboutus.php">Quiénes somos</a></div>
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/contact.php">Dónde estamos</a></div>
                <div class="menu-element"><a href="/FerreteriaVegagrande/templates/shop.php">Tienda</a></div>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 0): ?>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/templates/admin.php">Panel de Administrador</a></div>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    <script src="/FerreteriaVegagrande/scripts/burgermenu.js"></script>
</body>
</html>