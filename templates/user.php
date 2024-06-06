<?php
session_start();


if (!isset($_SESSION['id'])) {
    header('Location: error403.html');
    exit();
}

include("../api/connectDB.php");

$conn = connectDB();

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax'])) {
    // Comprobación de los campos requeridos
    $required_fields = ['name', 'surname', 'email', 'password', 'address', 'postal_code', 'location', 'country', 'phone'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $response['message'] = "El campo $field es obligatorio.";
            echo json_encode($response);
            exit;
        }
    }

    // Validaciones
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['name'])) {
        $response['message'] = 'Nombre no válido.';
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['surname'])) {
        $response['message'] = 'Apellido no válido.';
        echo json_encode($response);
        exit;
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Correo electrónico no válido.';
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^\d{5}$/", $_POST['postal_code'])) {
        $response['message'] = 'Código postal no válido.';
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['location'])) {
        $response['message'] = 'Localidad no válida.';
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[\p{L}\s]+$/u", $_POST['country'])) {
        $response['message'] = 'País no válido.';
        echo json_encode($response);
        exit;
    }
    if (!preg_match("/^[0-9]{9}$/", $_POST['phone'])) {
        $response['message'] = 'Número de teléfono no válido';
        echo json_encode($response);
        exit;
    }

    // Si todas las validaciones pasan, actualiza los datos en la base de datos
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $password = $_POST['password']; // Recuerda siempre encriptar las contraseñas antes de guardarlas en la base de datos
    $email = $_POST['email'];
    $address = $_POST['address'];
    $postal_code = $_POST['postal_code'];
    $location = $_POST['location'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    
    $stmt = $conn->prepare("UPDATE usuarios SET name=?, surname=?, password=?, email=?, address=?, postal_code=?, location=?, country=?, phone=? WHERE id=?");
    $stmt->execute([$name, $surname, $password, $email, $address, $postal_code, $location, $country, $phone, $_SESSION['id']]);

    // Actualiza los datos en la sesión
    $_SESSION['name'] = $name;
    $_SESSION['surname'] = $surname;
    $_SESSION['email'] = $email;
    $_SESSION['address'] = $address;
    $_SESSION['postal_code'] = $postal_code;
    $_SESSION['location'] = $location;
    $_SESSION['country'] = $country;
    $_SESSION['phone'] = $phone;
    $_SESSION['password'] = $password; // Asegúrate de encriptar la contraseña

    $response['success'] = true;
    $response['message'] = 'Datos actualizados correctamente.';
    echo json_encode($response);
    exit();
}

// Consulta los datos actualizados del usuario de la base de datos
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferretería Vegagrande</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/user.css">
    <link rel="shortcut icon" href="/FerreteriaVegagrande/favicon.ico" type="image/x-icon">
    <script src="../scripts/user.js" defer></script>
</head>
<body>

<div class="header">
    <div class="title-container">
        <a href="../index.php"><img src="../assets/img/icons/goBack.png" alt="home"></a>
        <h1 class='title'><?= htmlspecialchars($user['name']); ?> <?= htmlspecialchars($user['surname']); ?></h1>
    </div>
</div>
<button id="mostrarPedidos">Pedidos</button>
<div id="user-info">
    <div class="section-title"><h2>Mi Información</h2></div>
    <form id="userForm" method="post">
        <div class="user-form">
            <div class="input-cont">
                <div class="name-cont">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" placeholder="Nombre" value="<?= htmlspecialchars($user['name']); ?>" >
                </div>
                <div class="surname-cont">
                    <label for="surname">Apellidos</label>
                    <input type="text" id="surname" name="surname" placeholder="Apellidos" value="<?= htmlspecialchars($user['surname']); ?>" >
                </div>
            </div>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="E-mail" value="<?= htmlspecialchars($user['email']); ?>" >
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" value="<?= htmlspecialchars($user['password']); ?>" readonly>
            <label for="address">Dirección</label>
            <input type="text" id="address" name="address" placeholder="Dirección de envío" value="<?= htmlspecialchars($user['address']); ?>" >
            <div class="input-cont">
                <div class="pcode-cont">
                    <label for="postal_code">Código Postal</label>
                    <input type="text" id="postal_code" name="postal_code" placeholder="Código Postal" value="<?= htmlspecialchars($user['postal_code']); ?>" >
                </div>
                <div class="location-cont">
                    <label for="location">Localidad</label>
                    <input type="text" id="location" name="location" placeholder="Localidad" value="<?= htmlspecialchars($user['location']); ?>" >
                </div>
            </div>
            <div class="input-cont">
                <div class="country-cont">
                    <label for="country">País</label>
                    <input type="text" id="country" name="country" placeholder="Pais" value="<?= htmlspecialchars($user['country']); ?>" >
                </div>
                <div class="phone-cont">
                    <label for="phone">Teléfono</label>
                    <input type="text" id="phone" name="phone" placeholder="Teléfono" value="<?= htmlspecialchars($user['phone']); ?>" >
                </div>
            </div>
        </div>
        <div id="order-message"></div>
        <button type="submit" id="place-order-btn">Guardar</button>
    </form>
</div>

<div id="pedidos" style="display:none;">
    <div class="section-title"><h2>Pedidos</h2></div>
    <table>
        <thead>
            <tr>
                <th>Número Pedido</th>
                <th>Fecha</th>
                <th>Precio Total</th>
                <th>Detalle del Pedido</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="parragraf">
            <?php
            $stmt = $conn->prepare("SELECT pedidos.id AS pedido_id, pedidos.date, pedidos.total_price, estado_pedidos.estado, estado_pedidos.fecha_actualizacion,
            GROUP_CONCAT(productos.name SEPARATOR ', ') AS productos, GROUP_CONCAT(productos.price SEPARATOR ', ') AS precios,
            GROUP_CONCAT(pedidos_productos.quantity SEPARATOR ', ') AS cantidades, GROUP_CONCAT(productos.img SEPARATOR ', ') AS imagenes
            FROM pedidos
            JOIN usuarios ON pedidos.id_usuario = usuarios.id
            LEFT JOIN pedidos_productos ON pedidos.id = pedidos_productos.pedido_id
            LEFT JOIN productos ON pedidos_productos.product_id = productos.id
            LEFT JOIN estado_pedidos ON pedidos.id = estado_pedidos.pedido_id
            WHERE usuarios.id = ?
            GROUP BY pedidos.id");
            $stmt->execute([$_SESSION['id']]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($orders as $order):
            $order_json = htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8');
            ?>
            <tr>
            <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['pedido_id'] ?></button></td>
            <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['date'] ?></button></td>
            <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><?= $order['total_price'] ?>€</button></td>
            <td><button class="order-detail-btn" onclick="showModal('<?= $order_json ?>')"><div><?= $order ['fecha_actualizacion']?></div> <?= $order['estado'] ?: 'En espera' ?></button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="modal-detalle-pedido" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Detalles del Pedido</h2>
        <div id="detalle-pedido-content"></div>
    </div>
</div>

</div>
</body>
</html>
