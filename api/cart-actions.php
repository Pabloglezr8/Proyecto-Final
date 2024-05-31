<?php
session_start();
header('Content-Type: application/json');

$response = ['status' => false, 'message' => '', 'quantity' => 0]; // Agregamos 'quantity' al array de respuesta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'remove':
                $response = removeFromCart();
                break;
            case 'remove_all':
                $response = removeAllFromCart();
                break;
            case 'clear':
                $response = clearCart();
                break;
            case 'increase_quantity':
                $response = increaseCartItemQuantity();
                break;
            default:
                $response['message'] = 'Acción no válida.';
        }
    } else {
        $response['message'] = 'Falta la acción.';
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
exit;

function removeFromCart() {
    $response = ['status' => false, 'message' => 'Error al eliminar el producto de la cesta.', 'quantity' => 0]; // Agregamos 'quantity' al array de respuesta

    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        if (isset($_SESSION['cart'][$product_id])) {
            if ($_SESSION['cart'][$product_id] > 1) {
                $_SESSION['cart'][$product_id]--;
                $response['quantity'] = $_SESSION['cart'][$product_id]; // Actualizamos la cantidad en la respuesta
            } else {
                unset($_SESSION['cart'][$product_id]);
            }

            $response['status'] = true;
            $response['message'] = 'Producto eliminado de la cesta correctamente.';
        } else {
            $response['message'] = 'El producto no está en la cesta.';
        }
    }

    return $response;
}

function removeAllFromCart() {
    $response = ['status' => false, 'message' => 'Error al eliminar todos los productos de la cesta.', 'quantity' => 0]; // Agregamos 'quantity' al array de respuesta

    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $response['status'] = true;
            $response['message'] = 'Todos los productos eliminados de la cesta correctamente.';
        } else {
            $response['message'] = 'El producto no está en la cesta.';
        }
    }

    return $response;
}

function clearCart() {
    $response = ['status' => false, 'message' => 'Error al vaciar la cesta.', 'quantity' => 0]; // Agregamos 'quantity' al array de respuesta

    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
        $response['status'] = true;
        $response['message'] = 'Cesta vaciada correctamente.';
    }

    return $response;
}

function increaseCartItemQuantity() {
    $response = ['status' => false, 'message' => 'Error al aumentar la cantidad del producto en la cesta.', 'quantity' => 0];

    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
            $response['status'] = true;
            $response['message'] = 'Cantidad del producto aumentada en la cesta correctamente.';
            $response['quantity'] = $_SESSION['cart'][$product_id]; // Actualizar la cantidad en la respuesta
        } else {
            $response['message'] = 'El producto no está en la cesta.';
        }
    }

    return $response;
}

