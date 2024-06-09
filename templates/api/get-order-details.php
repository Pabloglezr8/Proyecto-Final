<?php
include("connectDB.php");

$conn = connectDB();

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $stmt = $conn->prepare("SELECT productos.product_name, detalles_pedido.quantity, detalles_pedido.price
                            FROM detalles_pedido
                            JOIN productos ON detalles_pedido.product_id = productos.id
                            WHERE detalles_pedido.order_id = ?");
    $stmt->execute([$order_id]);
    $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($orderDetails);
}
?>
