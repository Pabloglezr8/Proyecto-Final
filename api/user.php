<?php 
// Incluir el archivo para la conexión a la base de datos
include("connectDB.php");

// Iniciar la sesión
session_start();

// Establecer la conexión a la base de datos
$conn = connectDB();

// Obtener el nombre de usuario de la sesión
$user = $_SESSION['name'];

// Verificar si la conexión a la base de datos fue exitosa
if($conn){
    // Consultar todos los productos ordenados por nombre
    $query = "SELECT * FROM productos ORDER BY name";
    $statement = $conn->prepare($query);
    $statement->execute();
    $productos = $statement->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <!-- Estructura HTML para mostrar el menú de productos -->
    <div class="header">
        <a href="./login.php">Home</a>
        <?php
         echo "<p> Bienvenido " . $user ."</p>";
        ?>
        <div></div>
    </div>

    <!-- Formulario para seleccionar la cantidad de productos -->
    <div class="h2Container">
<div class="line"></div>
<h2 class='heading'>Productos</h2>
<div class="line"></div>
</div>
    <?php if(count($productos) > 0): ?>
            <table class="products-table">
                <tr>
                    <th>Imágen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                </tr>
                <?php foreach($productos as $producto): ?>
                    <tr>
                        <td><img src="../assets/img/productos/<?= $producto['img'] ?>" alt="<?= $producto['name'] ?>"></td>
                        <td><?= $producto['name'] ?></td>
                        <td><?= $producto['description'] ?></td>
                        <td><?= $producto['price'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="orderColumn">
                    <div class='btn-container'>
                    </div>
                    </td>
                </tr>
            </table>
    <?php else: ?>
        <p>No se encontraron resultados</p>
    <?php endif;

    // Cerrar la conexión a la base de datos
    $conn = null;
} else {
    echo "Error al conectar a la Base de datos";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $user ?></title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
</body>
</html>
