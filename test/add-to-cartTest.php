<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '../api/add-to-cart.php';

class AddToCartTest extends TestCase {
    public function testAddToCartSuccess() {
        // Establecer las variables de sesión necesarias para la prueba
        $_SESSION['cart'] = [];

        // Datos de prueba
        $_POST["product_id"] = 123;
        $_POST["quantity"] = 2;

        // Ejecutar la función para añadir al carrito
        ob_start(); // Capturar salida de posibles mensajes
        include __DIR__ . '/../api/add-to-cart.php';
        $output = ob_get_clean();

        // Verificar que se añadió el producto correctamente al carrito
        $response = json_decode($output, true);
        $this->assertTrue($response['status']);
        $this->assertEquals('Producto añadido al carrito correctamente.', $response['message']);
        $this->assertEquals(2, $_SESSION['cart'][123]); // Verificar la cantidad añadida en el carrito
    }

    public function testAddToCartFailure() {
        // Establecer las variables de sesión necesarias para la prueba
        $_SESSION['cart'] = [];

        // Datos de prueba con cantidad inválida
        $_POST["product_id"] = 123;
        $_POST["quantity"] = 0;

        // Ejecutar la función para añadir al carrito
        ob_start(); // Capturar salida de posibles mensajes
        include __DIR__ . '/../api/add-to-cart.php';
        $output = ob_get_clean();

        // Verificar que se haya mostrado un mensaje de error
        $response = json_decode($output, true);
        $this->assertFalse($response['status']);
        $this->assertEquals('Error al añadir el producto al carrito.', $response['message']);
    }
}
