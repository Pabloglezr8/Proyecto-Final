<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

$response = ['status' => false, 'message' => 'Error al añadir el producto al carrito.'];

if ($product_id > 0 && $quantity > 0) {
    // Aquí deberías validar si el producto existe en la base de datos, pero para simplificar, omitimos esa parte.
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    $response['status'] = true;
    $response['message'] = 'Producto añadido al carrito correctamente.';
}

echo json_encode($response);
exit;
?>
