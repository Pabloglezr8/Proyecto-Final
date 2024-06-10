<?php
session_start();

if (!($_SESSION['role'] === 0)) {
    header('Location: error403.html');
    exit();
}

include("../api/connectDB.php");

$conn = connectDB();

// Variable para almacenar mensajes
$message = "";
$messageClass = ""; // Clase para el estilo del mensaje

// Función para eliminar un producto por su ID
function deleteProducto($conn, $id){
    $query = $conn->prepare("SELECT img FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $imgToDelete = $result['img'];

    $query = $conn->prepare("DELETE FROM productos WHERE id = :id");
    $query->bindParam(":id", $id);
    $deleted = $query->execute();

    if ($deleted && !empty($imgToDelete)) {
        $filePath = "../assets/img/productos/" . $imgToDelete;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    return $deleted;
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
    return $result ? $result['estado'] : 'En espera';
}

function updateEstadoPedido($conn, $id_pedido, $estado) {
    $fecha_actualizacion = date("Y-m-d H:i:s");
    $query = $conn->prepare("UPDATE estado_pedidos SET estado = :estado, fecha_actualizacion = :fecha_actualizacion WHERE pedido_id = :id_pedido");
    $query->bindParam(":estado", $estado);
    $query->bindParam(":fecha_actualizacion", $fecha_actualizacion);
    $query->bindParam(":id_pedido", $id_pedido);
    return $query->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_estado'])) {
    $id_pedido = $_POST['id_pedido'];
    $estado = $_POST['estado'];
    if (updateEstadoPedido($conn, $id_pedido, $estado)) {
        $message = "Estado del pedido actualizado correctamente.";
        $messageClass = "success";
    } else {
        $message = "Error al actualizar el estado del pedido.";
        $messageClass = "error";
    }
}

// Función para listar todos los productos disponibles
function listProducto($conn, &$message, &$messageClass){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['delete'])){
            $id = $_POST['delete_id'];
            deleteProducto($conn, $id);
            $message = "Producto eliminado correctamente.";
            $messageClass = "success";
        } else if(isset($_POST['edit'])){
            $id = $_POST['edit_id'];
            editProducto($conn, $id);
        } else if(isset($_POST['insert'])){
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
                $img = basename($_FILES['img']['name']);
                $target_dir = "../assets/img/productos/";
                $target_file = $target_dir . $img;

                if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)){
                    addProducto($conn, $name, $img, $description, $price);
                    $message = "Producto añadido correctamente.";
                    $messageClass = "success";
                } else {
                    $message = "Error al subir la imagen.";
                    $messageClass = "error";
                }
            } else {
                $message = "Error al insertar el producto. Imagen no válida.";
                $messageClass = "error";
            }
        } else if(isset($_POST['update'])){
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
                $img = basename($_FILES['img']['name']);
                $target_dir = "../assets/img/productos/";
                $target_file = $target_dir . $img;

                if(move_uploaded_file($_FILES['img']['tmp_name'], $target_file)){
                    updateProducto($conn, $id, $name, $img, $description, $price);
                    $message = "Producto actualizado correctamente.";
                    $messageClass = "success";
                } else {
                    $message = "Error al subir la imagen.";
                    $messageClass = "error";
                }
            } else {
                $img = $_POST['current_img'];
                updateProducto($conn, $id, $name, $img, $description, $price);
                $message = "Producto actualizado correctamente.";
                $messageClass = "success";
            }
        }
    }

    $query = $conn->prepare("SELECT * FROM productos ORDER BY name ASC");
    $query->execute();

    $productoToEdit = null;

    if(isset($_POST['edit_id'])){
        $id = $_POST['edit_id'];
        $productoToEdit = editProducto($conn, $id);
    }

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferretería Vegagrande</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/admin.js"></script>
    <script src="../scripts/user.js"></script>
    <script src="https://cdn.tiny.cloud/1/fizcb6unxwb4kzc9elhnv9ny7im3f15bzd59wu9waibmaufq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
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

        <div id="myModal" class="text-editor-modal">
            <div class="text-editor-modal-content">
                <textarea id="modal-editor"><?= $descripcion ?></textarea>
                <span class="close">&times;</span>
            </div>
        </div>

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
                                    <button type='submit' name='delete' id="delete-btn">Eliminar</button>
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
                $stmt = $conn->prepare("SELECT pedidos.id AS pedido_id, pedidos.date AS fecha_pedido, pedidos.total_price AS costo_total, 
                estado_pedidos.estado AS estado_actual, estado_pedidos.fecha_actualizacion AS fecha_actualizacion_estado, 
                usuarios.name AS nombre_usuario, usuarios.surname AS apellido_usuario,
                GROUP_CONCAT(productos.name SEPARATOR ', ') AS productos, GROUP_CONCAT(productos.price SEPARATOR ', ') AS precios, 
                GROUP_CONCAT(pedidos_productos.quantity SEPARATOR ', ') AS cantidades, GROUP_CONCAT(productos.img SEPARATOR ', ') AS imagenes
                FROM pedidos
                JOIN usuarios ON pedidos.id_usuario = usuarios.id
                LEFT JOIN pedidos_productos ON pedidos.id = pedidos_productos.pedido_id
                LEFT JOIN productos ON pedidos_productos.product_id = productos.id
                LEFT JOIN estado_pedidos ON pedidos.id = estado_pedidos.pedido_id
                GROUP BY pedidos.id");
                $stmt->execute();
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($orders as $order):
                $order_json = htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8');
                ?>
                <tr>
                    <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['pedido_id'] ?></button></td>
                    <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['nombre_usuario'] . ' ' . $order['apellido_usuario'] ?></button></td>
                    <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['fecha_pedido'] ?></button></td>
                    <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['costo_total'] ?>€</button></td>
                    <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['fecha_actualizacion_estado'] ?></button>
                        <form id="estado-form" method='POST'>
                            <input type='hidden' name='id_pedido' value='<?= htmlspecialchars($order['pedido_id']) ?>'>
                            <select class="form-select" name='estado'>
                                <option value='En Espera' <?= ($order['estado_actual'] == 'En Espera' ? 'selected' : '') ?>>En espera</option>
                                <option value='Procesando' <?= ($order['estado_actual'] == 'Procesando' ? 'selected' : '') ?>>Procesando</option>
                                <option value='Cancelado' <?= ($order['estado_actual'] == 'Cancelado' ? 'selected' : '') ?>>Cancelado</option>
                                <option value='Enviado' <?= ($order['estado_actual'] == 'Enviado' ? 'selected' : '') ?>>Enviado</option>
                            </select>
                            <button type='submit' name='update_estado'>Actualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="modal-detalle-pedido" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Detalles del pedido</h2>
                <div id="detalle-pedido-content"></div>
            </div>
        </div>
    </div>
    <script src="../scripts/modal.js"></script>
</body>
</html>

<?php
}

$conn = connectDB();
listProducto($conn, $message, $messageClass);
?>