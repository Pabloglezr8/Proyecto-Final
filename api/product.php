<?php 
include("header.php");

// Establecer la conexión a la base de datos
$conn = connectDB();

// Verificar si la conexión a la base de datos fue exitosa
if($conn){
    // Verificar si se proporciona un ID de producto válido en la URL
    if(isset($_GET['product_id']) && !empty($_GET['product_id'])) {
        $product_id = $_GET['product_id'];

        // Consultar el producto específico por su ID
        $query = "SELECT * FROM productos WHERE id = :product_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':product_id', $product_id);
        $statement->execute();
        $producto = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        // Si no se proporciona un ID de producto válido, redirigir a la página de inicio o mostrar un mensaje de error
        header("Location: ../index.html"); // Redirigir a la página de inicio
        exit(); // Detener la ejecución del script
    }
} else {
    // Si hubo un error en la conexión, mostrar un mensaje de error
    echo "Error al conectar a la base de datos";
    // Detener la ejecución del script
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $producto['name'] ?></title>
    <link rel="stylesheet" href="../styles/product.css">
    <style>
    </style>
</head>
<body>

    <div class="item-container">
        <?php if(isset($producto) && !empty($producto)): ?>
            <img src="../assets/img/productos/<?= $producto['img'] ?>" alt="<?= $producto['name'] ?>">
            <div class="item-data-container">
                <h1 class="item-title"><?= $producto['name'] ?></h1>
                <div class="item-buy-container">
                    <h2>Precio: <?= $producto['price']?> €</h2>
                    <button class="add-to-cart-btn" data-product-id="<?php echo $producto['id']; ?>">Añadir al carrito</button>
                </div>
                <div class="item-descriptin-container">
                    <h3 class="h3">Descripción</h3>
                    <p class="parragraf"><?= $producto['description'] ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>No se encontró el producto</p>
        <?php endif; ?>
    
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/add-to-cart.js"></script>
</body>
</html>
