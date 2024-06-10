<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '../api/cart-actions.php';

class RemoveFromCartTest extends TestCase {
    public function testRemoveFromCartSuccess() {
        // Establecer las variables de sesión necesarias para la prueba
        $_SESSION['cart'] = [123 => 2, 456 => 1];

        // Datos de prueba
        $_POST["product_id"] = 123;

        // Ejecutar la función para eliminar del carrito
        ob_start(); // Capturar salida de posibles mensajes
        include __DIR__ . '/../api/remove-from-cart.php';
        $output = ob_get_clean();

        // Verificar que se eliminó el producto correctamente del carrito
        $response = json_decode($output, true);
        $this->assertTrue($response['status']);
        $this->assertEquals('Producto eliminado de la cesta correctamente.', $response['message']);
        $this->assertEquals(1, $_SESSION['cart'][123]); // Verificar la cantidad restante en el carrito
    }

    public function testRemoveFromCartFailure() {
        // Establecer las variables de sesión necesarias para la prueba
        $_SESSION['cart'] = [123 => 2, 456 => 1];

        // Datos de prueba con un producto no existente
        $_POST["product_id"] = 789;

        // Ejecutar la función para eliminar del carrito
        ob_start(); // Capturar salida de posibles mensajes
        include __DIR__ . '/../api/remove-from-cart.php';
        $output = ob_get_clean();

        // Verificar que se haya mostrado un mensaje de error
        $response = json_decode($output, true);
        $this->assertFalse($response['status']);
        $this->assertEquals('El producto no está en la cesta.', $response['message']);
        // Verificar que el carrito no se haya modificado
        $this->assertEquals([123 => 2, 456 => 1], $_SESSION['cart']);
    }
}
