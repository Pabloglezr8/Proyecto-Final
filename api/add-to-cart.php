<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    $response = ['status' => false, 'message' => 'Error al añadir el producto al carrito.'];

    if ($product_id > 0 && $quantity > 0) {
                
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }

        $response['status'] = true;
        $response['message'] = 'Producto añadido al carrito correctamente.';
        $response['cart_count'] = array_sum($_SESSION['cart']); // Actualizar la cantidad total en la cesta
    }
} else {
    $response = ['status' => false, 'message' => 'No se proporcionaron los datos necesarios.'];
}

echo json_encode($response);
exit;
?>
