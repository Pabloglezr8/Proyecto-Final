<?php 
include("connectDB.php");

session_start();

$user = $_SESSION['username'];
$message = null;

// Función para eliminar una producto por su ID
function deleteProducto($conn, $id){
     // Preparar y ejecutar la consulta para eliminar la producto de la base de datos
    $query = $conn->prepare("DELETE FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    return $query->execute();
}

// Función para obtener los detalles de una producto por su ID para editarla
function editProducto($conn, $id){
      // Preparar y ejecutar la consulta para obtener los detalles de la producto por su ID
    $query = $conn->prepare("SELECT * FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    $query->execute();
    // Devuelve los detalles de la producto como un array asociativo
    return $query->fetch(PDO::FETCH_ASSOC);
}


// Función para insertar una nueva producto en la base de datos
function addProducto($conn, $name, $category, $description, $price){
    if(isset($name, $category, $description, $price)){
        $query = $conn->prepare("INSERT INTO productos (name, category, description, price) VALUES (:name, :category, :description, :price)");
        $query->bindParam(":name", $name);
        $query->bindParam(":category", $category);
        $query->bindParam(":description", $description);
        $query->bindParam(":price", $price);
        return $query->execute();// Devuelve true si la inserción fue exitosa, de lo contrario, devuelve false
    }   
}
?>
    <div class="header">
        <a href="./login.php">Home</a>
        <h1 class="title">producto House</h1>
        <div></div>
    </div>
<?php

// Función para listar todas las productos disponibles
function listProducto($conn){
       // Lógica para manejar las operaciones POST como eliminar, editar e insertar productos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['delete'])){
            $id = $_POST['delete_id'];
            deleteProducto($conn, $id);
            echo "<p id='message' class='message success'>Producto eliminado correctamente.</p>";
        } else if(isset($_POST['edit'])){
            $id = $_POST['edit_id'];
            editProducto($conn, $id);
        }else if(isset($_POST['insert'])){
            $name = $_POST['name'];
            $category = $_POST['category'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            if(!empty($name) && !empty($category) && !empty($description) && !empty($price)){
                addProducto($conn, $name, $category, $description, $price);
                $message = "Prodcuto añadido correctamente.";
                echo "<p id='message' class='message success'>" . $message . "</p>";
            }else{
                $message = "Error al insertar la producto.";
                echo "<p id='message' class='message error'>" . $message . "</p>";
            }
           
        }
    }

    if(isset($_POST['update'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        
       
        if(!empty($name) && !empty($category) && !empty($description) && !empty($price)){
            $query = $conn->prepare("UPDATE productos SET name = :name, category = :category, description = :description, price = :price WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->bindParam(":name", $name);
            $query->bindParam(":category", $category);
            $query->bindParam(":description", $description);
            $query->bindParam(":price", $price);
              
            if($query->execute()){
                $message = "Producto actualizado correctamente.";
                 echo "<p id='message' class='message success'>" . $message . "</p>";
             }
        }else {
            $message = "Error al actualizar el producto.";
            echo "<p id='message' class='message error'>" . $message . "</p>";
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
    
    <form method='post' class='shadow'>
    <legend><?= ($productoToEdit ? "Editar Producto" : "Insertar Producto") ?></legend>   
    <label for='name'>Nombre</label>
    <input type='text' name='name' id='name' value='<?= ($productoToEdit ? $productoToEdit["name"] : "") ?>'>
    <label for='category'>Categoría</label>
    <input type='text' name='category' id='category' value='<?= ($productoToEdit ? $productoToEdit["category"] : "") ?>'>
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
     <!-- Consulta las productos disponibles en la base de datos y muestra una tabla HTML con ellas -->
    <table border='1' class='userTable'>
        <tr>
            <th>Nombre</th>
            <th>Categoria</th>
            <th>Descripcion</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
    <?php
    foreach($query as $row){          
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["price"] . "</td>";
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

// Función para obtener las productos más vendidas




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