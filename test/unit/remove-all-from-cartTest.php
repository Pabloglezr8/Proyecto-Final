<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '../api/cart-actions.php';

class ClearCartTest extends TestCase {
    public function testClearCartSuccess() {
        // Establecer las variables de sesión necesarias para la prueba
        $_SESSION['cart'] = [123 => 2, 456 => 1];

        // Ejecutar la función para vaciar el carrito
        ob_start(); // Capturar salida de posibles mensajes
        include __DIR__ . '/../api/clear-cart.php';
        ob_get_clean(); // Limpiar el buffer de salida

        // Verificar que el carrito esté vacío
        $this->assertEmpty($_SESSION['cart']);
    }
}
