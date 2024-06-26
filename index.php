<?php
include("./api/header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferretería Vegagrande</title>
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
<div class="page">
    <section class="intro-section">
        <h1 class="intro-title section-title">Ferretería en Gijón</h1>
        <div class="intro-container section-container">
            <div class="intro-left section-left">
                <div class="tenyears-container">
                    <div class="tenyears-num">
                        <h1>+</h1>
                        <h1 class="counter" id="counter">+0</h1>
                        </div>         
                    <div class="tenyears-text">
                        <img src="./assets/img/icons/certified.png" alt="">
                        <h3>AÑOS</h3>
                    </div>
                </div>
                <div class="tenyears-slogan">
                     <h2 id="change-word">DE COMPROMISO</h2>
                </div>
            </div>
            <div class="intro-right section-right">
                <p class="intro-text parragraf">
                    Donde comienza cada mejora de tu hogar, ahí estamos nosotros, en la ciudad de Gijón,
                    siendo mucho más que una simple ferretería. En nuestra tienda, encontrarás un oasis
                    de soluciones para tus proyectos, desde el menaje más práctico hasta las herramientas de
                    bricolaje básico que necesitas para llevar a cabo cualquier tarea.
                </p>
                <a href="templates/contact.php"><button>Localízanos</button></a>
            </div>
        </div>
    </section>
    <section class="quality-section">
        <h1 class="quality-title section-title">Con la Mejor Calidad</h1>
        <div class="quality-container section-container">
            <div class="quality-left section-left">
                <p class="quality-text parragraf">
                    En nuestra ferretería, <strong>la calidad es nuestra prioridad número uno</strong>. Cada
                    producto que ofrecemos,
                    ya sea una herramienta para bricolaje, un utensilio de cocina o una cerradura para tu hogar, ha
                    sido cuidadosamente seleccionado por su excelencia y durabilidad.
                </p>
                <p class="quality-text parragraf">
                    Nuestro compromiso por abastecer al cliente con <strong>los mejores productos</strong> se refleja en
                    cada detalle: desde las
                    herramientas que resisten el paso del tiempo y hacen que cada tarea sea más fácil y eficiente,
                    hasta el menaje de cocina que aguanta el uso diario y sigue luciendo como nuevo, y las cerraduras
                    que garantizan la seguridad y tranquilidad en tu hogar.
                </p>
                <p class="quality-text parragraf">
                    Aseguramos la fiabilidad de nuestros productos porque sabemos que <strong>están diseñados para
                        perdurar</strong>
                    y procuramos comprometernos con la excelencia, porque sabemos que nuestros clientes merecen lo
                    mejor.
                </p>
                <div class="quality-slogan">
                    <p>En nuestra ferretería, la calidad es más que una promesa, es nuestra garantía de satisfacción.
                    </p>
                </div>
                <a href="templates/aboutus.php"><button>Conócenos</button></a>
            </div>
            <div class="quality-right section-right">
                <div class="gallery quality-right section-right">
                    <img class="gallery-img" src="./assets/img/producto1.jpg" alt="Image 1">
                    <img class="gallery-img" src="./assets/img/producto2.jpg" alt="Image 2">
                    <img class="gallery-img" src="./assets/img/producto3.jpg" alt="Image 3">
                    <img class="gallery-img" src="./assets/img/producto4.jpg" alt="Image 4">
                    <img class="gallery-img" src="./assets/img/producto5.jpg" alt="Image 5">
                    <img class="gallery-img" src="./assets/img/producto6.jpg" alt="Image 6">
                </div>
            </div>
        </div>
    </section>



    <section class="product-section">
        <h1 class="product-title section-title">y una Gran Variedad de Productos</h1>
        <div class="product-container">
            <div class="card" id="tools">
                <div class="front">
                <img src="./assets/img/icons/tools.png" alt="Herramientas">

                    <h3>Herramientas</h3>
                </div>
                <div class="back">
                    <ul>
                        <li>Martillos</li>
                        <li>Destornilladores Estrella</li>
                        <li>Destornilladores Planos</li>
                        <li>Llaves Allen</li>
                        <li>Llaves de Tubo</li>
                        <li>Sierras de mano</li>
                        <li>Alicates</li>
                    </ul>
                </div>
            </div>
            <div class="card" id="tools">
                <div class="front">
                <img src="./assets/img/icons/menaje.png" alt="Cocina/Hogar">
                    <h3>Cocina/Hogar</h3>
                </div>
                <div class="back">
                    <ul>
                        <li>Cuchillos</li>
                        <li>Sartenes</li>
                        <li>Tablas de Cortar</li>
                        <li>Ollas</li>
                        <li>Teteras</li>
                        <li>Vajillas</li>
                        <li>Cafeteras</li>
                    </ul>
                </div>
            </div>
            <div class="card" id="tools">
                <div class="front">
                    <img src="./assets/img/icons/cerrajeria.png" alt="Cerrajería">
                    <h3>Cerrajería</h3>
                </div>
                <div class="back">
                    <ul>
                        <li>Cerraduras Puerta</li>
                        <li>Bombines Seguridad</li>
                        <li>Kit Reparación</li>
                        <li>Candado Combinación</li>
                        <li>Candado Bicicleta</li>
                        <li>Aceites Cerrajero</li>
                        <li>Palometas</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <script src="./scripts/galeria.js"></script>
    <script src="./scripts/change-word.js"></script>
<?php
include("./api/footer.php");
?>
</div>

</body>
</html>