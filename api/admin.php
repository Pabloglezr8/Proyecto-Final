<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $name ?></title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="../styles/content.css">
    <script src="https://cdn.tiny.cloud/1/lpkru3bwlph0n9ix1g4arbvlm1i9l03nrofm1pm6v1njqqva/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#mytextarea',
        placeholder:'Descripción',
        language: 'es',
        height: 200,
        width: 400,
        branding: false,
        menubar:false,
        toolbar: ['undo redo | styles  forecolor| bold italic | outdent indent | alignleft aligncenter alignright'],
        statusbar: false,
        content_css: '../styles/textarea.css'
      });
</script>
    </script>
</head>
<body>




<?php 
include("connectDB.php");

session_start();

// Variable para almacenar mensajes
$message = "";
$messageClass = ""; // Clase para el estilo del mensaje

// Función para eliminar un producto por su ID

function deleteProducto($conn, $id){
    // Obtener el nombre de la imagen del producto a eliminar
    $query = $conn->prepare("SELECT img FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $imgToDelete = $result['img'];

    // Eliminar el producto de la base de datos
    $query = $conn->prepare("DELETE FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    $deleted = $query->execute();

    // Si se eliminó el producto de la base de datos, eliminar la imagen del servidor
    if ($deleted && !empty($imgToDelete)) {
        $filePath = "../assets/img/productos/" . $imgToDelete;
        if (file_exists($filePath)) {
            unlink($filePath); // Eliminar el archivo de imagen
        }
    }

    return $deleted; // Retornar si se eliminó el producto correctamente o no
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

// Función para listar todos los productos disponibles
function listProducto($conn, &$message, &$messageClass){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['delete'])){
            $id = $_POST['delete_id'];
            deleteProducto($conn, $id);
            $message = "Producto eliminado correctamente.";
            $messageClass = "success"; // Clase para el estilo de mensaje de éxito
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
                    $messageClass = "success"; // Clase para el estilo de mensaje de éxito
                } else {
                    $message = "Error al subir la imagen.";
                    $messageClass = "error"; // Clase para el estilo de mensaje de error
                }
            } else {
                $message = "Error al insertar el producto. Imagen no válida.";
                $messageClass = "error"; // Clase para el estilo de mensaje de error
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
                    $messageClass = "success"; // Clase para el estilo de mensaje de éxito
                } else {
                    $message = "Error al subir la imagen.";
                    $messageClass = "error"; // Clase para el estilo de mensaje de error
                }
            } else {
                // Si no se ha subido una nueva imagen, usar la imagen actual
                $img = $_POST['current_img'];
                updateProducto($conn, $id, $name, $img, $description, $price);
                $message = "Producto actualizado correctamente.";
                $messageClass = "success"; // Clase para el estilo de mensaje de éxito
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
    <div class="header">
        <div class="title-container">
            <a href="../index.php"><img src="../assets/img/icons/goBack.png" alt="home"></a>
            <h1 class='title'>Panel de Administrador</h1>
        </div>
        <form method='post' enctype='multipart/form-data' class='control-panel'>
            <div class="form-element-container">   
                    <div class="form-element">
                        <input type='text' name='name' id='name' placeholder="Nombre" value='<?= ($productoToEdit ? $productoToEdit["name"] : "") ?>'>
                    </div>

                    <div class="form-element">
                         <input type='text' name='price' id='price' placeholder="Precio" value='<?= ($productoToEdit ? $productoToEdit["price"] : "") ?>'>
                         <input type='hidden' name='id' value='<?= ($productoToEdit ? $productoToEdit["id"] : "") ?>'>
                    </div>
                

                    <div class="form-element">
                        <input type='file' name='img' id='input-file'>
                        <?php if($productoToEdit): ?>
                            <input type='hidden' name='current_img' value='<?= $productoToEdit["img"] ?>'>
                            <?php endif; ?>
                    </div>
                <div class="form-element">
                    <textarea type='text' name='description' id="mytextarea"><?php if($productoToEdit): ?><?=$productoToEdit["description"]?><?php endif; ?></textarea>
                </div>
            </div>
                <div class='btn-container'>
                    <button class='btn-insertar' type='submit' name='<?= ($productoToEdit ? "update" : "insert") ?>' id='insert-btn'><?= ($productoToEdit ? "Editar" : "Insertar") ?> producto</button>
                </div>
        </form>

            <!-- Mostrar mensaje si existe -->
    <?php if (!empty($message)): ?>
        <div class="message-container">
            <p class="message <?= $messageClass ?>"><?= $message ?></p>
        </div>
    <?php endif; ?>

    </div>
    <!-- Consulta los productos disponibles en la base de datos y muestra una tabla HTML con ellos -->
    <?php
    foreach($query as $product): ?> 
    <div class="product-container">
        <img src="../assets/img/productos/<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
        <p class="parragraf name" id="name"><?= $product["name"]?></p>
        <p class="parragraf description" id="description"><?= $product["description"]?></p>
        <p class="parragraf price" id="price"><?= $product["price"]?> €</p>
        <div class="button-container">
            <form method='post'>
                <input type='hidden' name='delete_id' value='<?= $product["id"] ?>'>
                <button class='btn' type='submit' name='delete' id="delete-btn">Eliminar</button>
            </form>
            <form method='post'>
                <input type='hidden' name='edit_id' value='<?= $product["id"] ?>'>
                <button class='btn' type='submit' name='edit' id='edit-btn'>Editar</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>              

<?php
}

$conn = connectDB();

listProducto($conn, $message, $messageClass);
?>

</body>
</html>