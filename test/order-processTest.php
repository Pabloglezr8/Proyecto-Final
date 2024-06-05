<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../api/process-order.php';

class ProcessOrderTest extends TestCase {
    public function testProcessOrderSuccess() {
        // Establecer las variables de sesión necesarias para la prueba
        $_SESSION['cart'] = [1 => 2, 2 => 1]; // Productos en el carrito
        $_POST['name'] = 'John';
        $_POST['surname'] = 'Doe';
        $_POST['email'] = 'john@example.com';
        $_POST['password'] = 'password';
        $_POST['address'] = '123 Main St';
        $_POST['postal_code'] = '12345';
        $_POST['location'] = 'City';
        $_POST['country'] = 'Country';
        $_POST['phone'] = '123456789';
        $_POST['payment_method'] = 'Credit Card';
        $_POST['shipment_method'] = 'Express';

        // Ejecutar la función para procesar el pedido
        ob_start(); // Capturar salida de posibles mensajes
        include __DIR__ . '/../api/process-order.php';
        $output = ob_get_clean(); // Obtener la salida de la función

        // Verificar que el pedido se haya realizado correctamente
        $response = json_decode($output, true);
        $this->assertTrue($response['status']);
        $this->assertEquals('Pedido realizado con éxito.', $response['message']);
        $this->assertArrayHasKey('order_id', $response);

        // Verificar que el carrito esté vacío después de realizar el pedido
        $this->assertEmpty($_SESSION['cart']);
    }
}
