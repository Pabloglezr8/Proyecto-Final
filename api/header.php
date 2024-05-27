<?php

include("connectDB.php");

session_start();


if (isset($_SESSION['id'])) {
    $user = [
        'name' => $_SESSION['name'],
        'surname' => $_SESSION['surname'],
        'pasword' => $_SESSION['password'],
        'email' => $_SESSION['email'],
        'address' => $_SESSION['address'],
        'postal_code' => $_SESSION['postal_code'],
        'location' => $_SESSION['location'],
        'country' => $_SESSION['country'],
        'phone' => $_SESSION['phone'],
        'role' => $_SESSION['role'],
    ];
    $isLoggedIn = true;
    

} else {
    $user = [
        'name' => '',
        'surname' => '',
        'email' => '',
        'address' => '',
        'postal_code' => '',
        'location' => '',
        'country' => '',
        'phone' => '',
        'role' => '' // Si el usuario no está logueado, el rol es vacío
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
            <a href="/FerreteriaVegagrande/index.php">
                <div class="identity-container">
                    <div class="Logo-container">
                        <img id="logo" src="/FerreteriaVegagrande/assets/img/Logo.png" alt="Logo">
                        <img id="logo-completo" src="/FerreteriaVegagrande/assets/img/LogoCompleto.png" alt="Logo">
                    </div>
                    <div class="identity-title-container">
                        <h1 class="identity-title">Ferretería Vegagrande</h1>
                        <h2 class="identity-subtitle">Donde comienza la mejora de tu hogar</h2>
                    </div>
                </div>
            </a>
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
                    <a href="/FerreteriaVegagrande/api/my_cart.php"><span id="cart-count"><?php echo $total_productos; ?></span><img src="/FerreteriaVegagrande/assets/img/icons/shopcart.png" alt="shopcart"></a>
                </div>
                <?php if($isLoggedIn): ?>
                    <span class="user-name"><?php echo $_SESSION['name']; ?></span>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/logout-process.php">LogOut</a></div>

                <?php else: ?>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/login.php">LogIn</a></div>
                <?php endif; ?>
                <?php if(!$isLoggedIn): ?>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/register.php">Register</a></div>
                <?php endif; ?>
            </nav>
        </div>
        </div>
        

        <!-- Menú de navegación para dispositivos de escritorio -->
        <div class="menu-container navigation-menu-container hidden">
            <nav class="navigation-menu">
                <div class="menu-element"><a href="/FerreteriaVegagrande/api/aboutus.php">Quiénes somos</a></div>
                <div class="menu-element"><a href="/FerreteriaVegagrande/api/contact.php">Dónde estamos</a></div>
                <div class="menu-element"><a href="/FerreteriaVegagrande/api/shop.php">Tienda</a></div>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 0): ?>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/admin.php">Panel de Administrador</a></div>
                <?php endif; ?>
            </nav>
        </div>

        <div id="success-message" class="message-container">
            <div><p class="message">Producto añadido al carrito correctamente.</p></div>
        </div>

    </header>

    <!-- Menú desplegable para dispositivos móviles -->
    <div class="hamburger-container">

        <nav class="burger-menu">
            <div>
                <div class="navigation-menu-burger">
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/aboutus.php">Quiénes somos</a></div>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/contact.php">Dónde estamos</a></div>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/shop.php">Tienda</a></div>
                </div>
            </div>
            <div>
                <div class=" user-menu-burger">
                    <div class="menu-element cart-icon">
                        <a href="/FerreteriaVegagrande/api/my_cart.php">Carrito (<span id="cart-count"><?php echo count($_SESSION['cart']); ?></span>)</a>
                    </div>
                    <?php if($isLoggedIn): ?>
                    <div class="menu-element user-element"><a href="/FerreteriaVegagrande/api/logout-process.php">LogOut</a></div>
                    <?php else: ?>
                    <div class="menu-element user-element"><a href="/FerreteriaVegagrande/api/login.php">LogIn</a></div>
                    <?php endif; ?>
                    <?php if(!$isLoggedIn): ?>
                        <div class="menu-element user-element"><a href="/FerreteriaVegagrande/api/register.php">Register</a></div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
    <script src="/FerreteriaVegagrande/scripts/burgermenu.js"></script>
</body>
</html>
