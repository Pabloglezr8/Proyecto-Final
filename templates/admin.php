<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferretería Vegagrande</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="shortcut icon" href="/FerreteriaVegagrande/favicon.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/admin.js"></script>
    <script src="https://cdn.tiny.cloud/1/lpkru3bwlph0n9ix1g4arbvlm1i9l03nrofm1pm6v1njqqva/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
<?php 
include("../api/connectDB.php");

$conn = connectDB();

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

// Función para obtener el estado de un pedido
function getEstadoPedido($conn, $id_pedido) {
    $query = $conn->prepare("SELECT estado FROM estado_pedidos WHERE pedido_id = :id_pedido");
    $query->bindParam(":id_pedido", $id_pedido);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['estado'] : 'en espera';
}

// Función para actualizar el estado de un pedido
function updateEstadoPedido($conn, $id_pedido, $estado) {
    $fecha_actualizacion = date("Y-m-d H:i:s"); // Obtiene la fecha y hora actual
    
    $query = $conn->prepare("UPDATE estado_pedidos SET estado = :estado, fecha_actualizacion = :fecha_actualizacion WHERE pedido_id = :id_pedido");
    $query->bindParam(":estado", $estado);
    $query->bindParam(":fecha_actualizacion", $fecha_actualizacion);
    $query->bindParam(":id_pedido", $id_pedido);
    
    return $query->execute();
}

// Lógica para manejar el cambio de estado del pedido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $estado = $_POST['estado'];
    updateEstadoPedido($conn, $id_pedido, $estado);
    $message = "Estado del pedido actualizado correctamente.";
    $messageClass = "success";
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
</div>
<button id="mostrarPedidos">Pedidos</button>
<div id="producto">
    <div class="section-title"><h2>Productos</h2></div>
    <form id="producto-form" method='post' enctype='multipart/form-data' class='control-panel'>
        <div class="form-element-container">
            <div class="form-element">
                <input type='text' name='name' id='name' placeholder="Nombre" value='<?= ($productoToEdit ? htmlspecialchars($productoToEdit["name"]) : "") ?>'>
            </div>
            <div class="form-element">
                <input type='text' name='price' id='price' placeholder="Precio" value='<?= ($productoToEdit ? htmlspecialchars($productoToEdit["price"]) : "") ?>'>
                <input type='hidden' name='id' value='<?= ($productoToEdit ? htmlspecialchars($productoToEdit["id"]) : "") ?>'>
            </div>
            <div class="form-element">
                <input type='file' name='img' id='input-file'>
                <?php if($productoToEdit): ?>
                    <input type='hidden' name='current_img' value='<?= htmlspecialchars($productoToEdit["img"]) ?>'>
                <?php endif; ?>
            </div>
            <div class="form-element">
                <button type="button" id="description-button" class="description-button">Descripción</button>
                <?php 
                // Ajusta el valor de la descripción para eliminar las etiquetas <p> duplicadas
                $descripcion = ($productoToEdit ? htmlspecialchars($productoToEdit["description"]) : "");
                $descripcion = preg_replace('#<p[^>]*>(.*?)<\/p>#is', '$1', $descripcion);
                ?>
                <input type='hidden' name='description' id='description-input' value='<?= $descripcion ?>'>
            </div>
        </div>
        <div class='btn-container'>
            <button class='btn-insertar' type='submit' name='<?= ($productoToEdit ? "update" : "insert") ?>' id='insert-btn'><?= ($productoToEdit ? "Guardar" : "Añadir Producto") ?></button>
        </div>
    </form>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <textarea id="modal-editor"><?= $descripcion ?></textarea>
            <span class="close">&times;</span>
        </div>
    </div>

    <!-- Mostrar mensaje si existe -->
    <?php if (!empty($message)): ?>
        <div id="message">
            <p class="message <?= $messageClass ?>"><?= $message ?></p>
        </div>
    <?php endif; ?>

    <table class="product-table">
        <thead>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($query as $product): ?>
                <tr>               
                    <td><img src="../assets/img/productos/<?= $product['img'] ?>" alt="<?= $product['name'] ?>"></td>
                    <td class="name"><p class="parraf" id="name"><?= $product["name"]?></p></td>
                    <td class="descripcion"><p class="parragraf" id="description"><?= $product["description"]?></p></td>
                    <td class="precio"><p class="parraf" id="price"><?= $product["price"]?> €</p></p></td>
                    <td class="actions">
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
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div id="pedido" style="display:none;">
    <div class="section-title"><h2>Pedidos</h2></div>
    <?php if (!empty($message)): ?>
        <div id="estado-message">
            <p class="estado-message <?= $messageClass ?>"><?= $message ?></p>
        </div>
    <?php endif; ?>
    <table >
        <thead>
            <tr>
                <th>Numero Pedido</th>
                <th>Nombre Usuario</th>
                <th>Fecha</th>
                <th>Precio</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="parragraf">
            <?php
            $stmt = $conn->prepare("SELECT pedidos.id, pedidos.date, pedidos.total_price, usuarios.name, usuarios.surname FROM pedidos JOIN usuarios ON pedidos.id_usuario = usuarios.id");
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($orders as $order):
                $estado = getEstadoPedido($conn, $order['id']);
            ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['name'] ?> <?= $order['surname'] ?></td>
                <td><?= $order['date'] ?></td>
                <td><?= $order['total_price'] ?>€</td>
                <td>
                    <form id="estado-form" method='POST'>
                        <input type='hidden' name='id_pedido' value='<?= htmlspecialchars($order['id']) ?>'>
                        <select class="form-select" name='estado'>
                            <option value='En Espera' <?= ($estado == 'En Espera' ? 'selected' : '') ?>>En espera</option>
                            <option value='Procesando' <?= ($estado == 'Procesando' ? 'selected' : '') ?>>Procesando</option>
                            <option value='Cancelado' <?= ($estado == 'Cancelado' ? 'selected' : '') ?>>Cancelado</option>
                            <option value='Enviado' <?= ($estado == 'Enviado' ? 'selected' : '') ?>>Enviado</option>
                        </select>
                        <td><button type='submit' name='update_estado'>Actualizar</button></td>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <?php
}

$conn = connectDB();

listProducto($conn, $message, $messageClass);
?>
</body>
</html>