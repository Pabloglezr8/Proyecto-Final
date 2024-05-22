<?php

include("connectDB.php");

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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
                        <a href="/FerreteriaVegagrande/api/my_cart.php"><span id="cart-count"><?php echo count($_SESSION['cart']); ?></span><img src="/FerreteriaVegagrande/assets/img/icons/shopcart.png" alt="shopcart"></a>
                    </div>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/login.php">LogIn</a></div>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/register.php">Register</a></div>
                </ul>
            </nav>
        </div>
        </div>

        <!-- Menú de navegación para dispositivos de escritorio -->
        <div class="menu-container navigation-menu-container hidden">
            <nav class="navigation-menu">
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/aboutus.php">Quiénes somos</a></div>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/contact.php">Dónde estamos</a></div>
                    <div class="menu-element"><a href="/FerreteriaVegagrande/api/shop.php">Tienda</a></div>
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
                    <div class="menu-element user-element"><a href="">LogIn</a></div>
                    <div class="menu-element user-element"><a href="">Register</a></div>
                    <div class="menu-element cart-icon">
                        <a href="/FerreteriaVegagrande/api/my_cart.php">Carrito (<span id="cart-count"><?php echo count($_SESSION['cart']); ?></span>)</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <script src="/FerreteriaVegagrande/scripts/burgermenu.js"></script>
    </body>
    </html>