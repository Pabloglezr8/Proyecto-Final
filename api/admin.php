<?php 
include("connectDB.php");

session_start();

// Función para eliminar un producto por su ID
function deleteProducto($conn, $id){
    $query = $conn->prepare("DELETE FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    return $query->execute();
}

// Función para obtener los detalles de un producto por su ID para editarlo
function editProducto($conn, $id){
    $query = $conn->prepare("SELECT * FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Función para insertar un nuevo producto en la base de datos
function addProducto($conn, $name, $img, $description, $price){
    if(isset($name, $img, $description, $price)){
        $query = $conn->prepare("INSERT INTO productos (name, img, description, price) VALUES (:name, :img, :description, :price)");
        $query->bindParam(":name", $name);
        $query->bindParam(":img", $img);
        $query->bindParam(":description", $description);
        $query->bindParam(":price", $price);
        return $query->execute();
    }
}

function updateProducto($conn, $id, $name, $img, $description, $price){
    $query = $conn->prepare("UPDATE productos SET name = :name, img = :img, description = :description, price = :price WHERE id = :id");
    $query->bindParam(":id", $id);
    $query->bindParam(":name", $name);
    $query->bindParam(":img", $img);
    $query->bindParam(":description", $description);
    $query->bindParam(":price", $price);
    return $query->execute();
}
?>

<?php

// Función para listar todos los productos disponibles
function listProducto($conn){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['delete'])){
            $id = $_POST['delete_id'];
            deleteProducto($conn, $id);
            echo "<p id='message' class='message success'>Producto eliminado correctamente.</p>";
        } else if(isset($_POST['edit'])){
            $id = $_POST['edit_id'];
            editProducto($conn, $id);
        } else if(isset($_POST['insert'])){
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            // Manejo de la imagen
            if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
                $img = basename($_FILES['img']['name']);
                $target_dir = "../assets/img/productos/";
                $target_file = $target_dir . $img;

                // Mueve el archivo a la carpeta de destino
                if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)){
                    addProducto($conn, $name, $img, $description, $price);
                    $message = "Producto añadido correctamente.";
                    echo "<p id='message' class='message success'>" . $message . "</p>";
                } else {
                    $message = "Error al subir la imagen.";
                    echo "<p id='message' class='message error'>" . $message . "</p>";
                }
            } else {
                $message = "Error al insertar el producto. Imagen no válida.";
                echo "<p id='message' class='message error'>" . $message . "</p>";
            }
        } else if(isset($_POST['update'])){
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            // Manejo de la imagen
            if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
                $img = basename($_FILES['img']['name']);
                $target_dir = "../assets/img/productos/";
                $target_file = $target_dir . $img;

                // Mueve el archivo a la carpeta de destino
                if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)){
                    updateProducto($conn, $id, $name, $img, $description, $price);
                    $message = "Producto actualizado correctamente.";
                    echo "<p id='message' class='message success'>" . $message . "</p>";
                } else {
                    $message = "Error al subir la imagen.";
                    echo "<p id='message' class='message error'>" . $message . "</p>";
                }
            } else {
                // Si no se ha subido una nueva imagen, usar la imagen actual
                $img = $_POST['current_img'];
                updateProducto($conn, $id, $name, $img, $description, $price);
                $message = "Producto actualizado correctamente.";
                echo "<p id='message' class='message success'>" . $message . "</p>";
            }
        }
    }

    $query = $conn->prepare("SELECT * FROM productos");
    $query->execute();

    $productoToEdit = null;

    if(isset($_POST['edit_id'])){
        $id = $_POST['edit_id'];
        $productoToEdit = editProducto($conn, $id);
    }
    ?>

    <form method='post' enctype='multipart/form-data' class='shadow'>
        <legend><?= ($productoToEdit ? "Editar Producto" : "Insertar Producto") ?></legend>   
        <label for='name'>Nombre</label>
        <input type='text' name='name' id='name' value='<?= ($productoToEdit ? $productoToEdit["name"] : "") ?>'>
        <label for='img'>Imagen</label>
        <input type='file' name='img' id='img'>
        <?php if($productoToEdit): ?>
            <input type='hidden' name='current_img' value='<?= $productoToEdit["img"] ?>'>
        <?php endif; ?>
        <label for='description'>Descripción</label>
        <input type='text' name='description' id='description' value='<?= ($productoToEdit ? $productoToEdit["description"] : "") ?>'>
        <label for='price'>Precio</label>
        <input type='text' name='price' id='price' value='<?= ($productoToEdit ? $productoToEdit["price"] : "") ?>'>
        <input type='hidden' name='id' value='<?= ($productoToEdit ? $productoToEdit["id"] : "") ?>'>

        <div class='btn-container'>
            <button class='btn insertar' type='submit' name='<?= ($productoToEdit ? "update" : "insert") ?>' id='insert-btn'><?= ($productoToEdit ? "Editar" : "Insertar") ?> producto</button>
        </div>
    </form>

    <div class="h2Container">
        <div class="line"></div>
        <h2 class='heading'>Nuestros productos</h2>
        <div class="line"></div>
    </div>
    
    <!-- Consulta los productos disponibles en la base de datos y muestra una tabla HTML con ellos -->
    <table border='1' class='userTable'>
        <tr>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    <?php
    foreach($query as $row){          
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td><img src='../assets/img/productos/" . $row["img"] . "' alt='" . $row["name"] . "'></td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["price"] ."€" . "</td>";
        echo "<td>
                <form method='post'>
                    <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                    <input class='btn' type='submit' name='delete' value='Eliminar'>
                </form>
                <form method='post'>
                    <input type='hidden' name='edit_id' value='" . $row["id"] . "'>
                    <input class='btn' type='submit' name='edit' value='Editar' id='edit-btn'>
                </form>
              </td>";
        echo "</tr>";           
    }
    echo "</table>";    
}

$conn = connectDB();

listProducto($conn);
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
    <script type="module" src="../scripts/main.js"></script>
    <script src="https://kit.fontawesome.com/c3db1c8a5f.js" crossorigin="anonymous"></script>
</body>
</html>
