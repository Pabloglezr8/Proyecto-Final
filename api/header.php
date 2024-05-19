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
        <div class="header-top-container"><a href="/FerreteriaVegagrande/index.php">
            <div class="identity-container">

                <div class="Logo-container">
                    <img src="/FerreteriaVegagrande/assets/img/Logo.png" alt="Logo">
                </div>
                <div class="identity-title-container">
                    <h1 class="identity-title">Ferretería Vegagrande</h1>
                    <h2 class="identity-subtitle">Donde comienza la mejora de tu hogar</h2>
                </div>

            </div></a>

            <div class="menu-container"></div>
            <!-- Botón hamburguesa para dispositivos móviles -->
            <div class="menu-toggle" id="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>


            <!-- Menú de usuario para dispositivos de escritorio -->
            <nav class="user-menu hidden">
                <ul>
                    <li class="menu-element cart-icon">
                        <a href="/FerreteriaVegagrande/api/my_cart.php">Carrito (<span id="cart-count"><?php echo count($_SESSION['cart']); ?></span>)</a>
                    </li>
                    <li class="menu-element"><a href="./api/login.php">LogIn</a></li>
                    <li class="menu-element"><a href="./api/register.php">Register</a></li>
                </ul>
            </nav>
        </div>
        </div>

        <!-- Menú de navegación para dispositivos de escritorio -->
        <div class="menu-container navigation-menu-container hidden">
            <nav class="navigation-menu">
                <ul>
                    <li class="menu-element"><a href="/FerreteriaVegagrande/api/aboutus.php">Quiénes somos</a></li>
                    <li class="menu-element"><a href="/FerreteriaVegagrande/api/contact.php">Dónde estamos</a></li>
                    <li class="menu-element"><a href="">Productos</a></li>
                    <li class="menu-element"><a href="/FerreteriaVegagrande/api/shop.php">Tienda</a></li>
                </ul>
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
                <ul class="navigation-menu">
                    <li class="menu-element"><a href="">Quiénes somos</a></li>
                    <li class="menu-element"><a href="">Dónde estamos</a></li>
                    <li class="menu-element"><a href="">Productos</a></li>
                    <li class="menu-element"><a href="">Tienda</a></li>
                </ul>
            </div>
            <div>
                <ul class="user-menu">
                    <li class="menu-element user-element"><a href="">LogIn</a></li>
                    <li class="menu-element user-element"><a href="">Register</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <script src="/FerreteriaVegagrande/scripts/burgermenu.js"></script>
    </body>
    </html>