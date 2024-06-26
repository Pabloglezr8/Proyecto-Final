<?php 
// Incluir el archivo para la conexión a la base de datos
include("../api/header.php");


// Establecer la conexión a la base de datos
$conn = connectDB();

// Verificar si la conexión a la base de datos fue exitosa
if($conn){
    try {
        // Consultar todos los productos ordenados por nombre
        $query = "SELECT * FROM productos ORDER BY name";
        $statement = $conn->prepare($query);
        $statement->execute();
        $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // En caso de error en la consulta, imprimir el mensaje de error
        echo "Error en la consulta: " . $e->getMessage();
        // Detener la ejecución del script
        exit();
    }
} else {
    // Si hubo un error en la conexión, mostrar un mensaje de error
    echo "Error al conectar a la base de datos";
    // Detener la ejecución del script
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function getCartProducts($pdo) {
    if (empty($_SESSION['cart'])) {
        return [];
    }

    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM productos WHERE id IN ($ids)");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



$cartProducts = getCartProducts($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="../styles/shop.css">
    
</head>
<body>
    <div class="page">
        <div class="products-container">
            <?php if(isset($productos) && count($productos) > 0): ?>
                <?php foreach($productos as $producto): ?>
                            <div class="product-card">
                                <a href="../templates/product.php?product_id=<?=$producto['id']?>">
                                    <img src="../assets/img/productos/<?= $producto['img'] ?>" alt="<?= $producto['name'] ?>">
                                    <h4 class="product-title"><?= $producto['name'] ?></h4>
                                    <p class="product-price parragraf"><?= $producto['price'] ?> €</p>
                                </a>
                                <button class="add-to-cart-btn" data-product-id="<?php echo $producto['id']; ?>"><img src="../assets/img/icons/add.png" alt=""></button>
                            </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="error">No se encontraron resultados</p>
            <?php endif; ?>
        </div>
        <?php
include("../api/footer.php");
?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/add-to-cart.js"></script>
</body>
</html>