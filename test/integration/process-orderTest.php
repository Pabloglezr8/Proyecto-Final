<?php

use PHPUnit\Framework\TestCase;

class ProcessOrderTest extends TestCase
{
    private $client;
    private $conn;

    protected function setUp(): void
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost/tu-proyecto/src/api/'
        ]);

        // Configuración para conectar a la base de datos
        $this->conn = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'tu_usuario', 'tu_contraseña');
    }

    public function testProcessOrderSuccessfully()
    {
        // Simular que hay productos en el carrito
        $_SESSION['cart'] = [
            1 => 2,
            2 => 1
        ];

        // Datos del usuario y del pedido
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'address' => '123 Main St',
            'postal_code' => '12345',
            'location' => 'City',
            'country' => 'Country',
            'phone' => '123456789',
            'payment_method' => 'Credit Card',
            'shipment_method' => 'Express'
        ];

        // Hacer la solicitud POST para realizar el pedido
        $response = $this->client->post('process-order.php', [
            'form_params' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertTrue($responseData['status']);
        $this->assertEquals('Pedido realizado con éxito.', $responseData['message']);
        $this->assertArrayHasKey('order_id', $responseData);

        // Verificar que el carrito esté vacío después de realizar el pedido
        $this->assertEmpty($_SESSION['cart']);
    }

    public function testProcessOrderWithEmptyCart()
    {
        // Simular que el carrito está vacío
        $_SESSION['cart'] = [];

        // Datos del usuario y del pedido
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'address' => '123 Main St',
            'postal_code' => '12345',
            'location' => 'City',
            'country' => 'Country',
            'phone' => '123456789',
            'payment_method' => 'Credit Card',
            'shipment_method' => 'Express'
        ];

        // Hacer la solicitud POST para realizar el pedido
        $response = $this->client->post('process-order.php', [
            'form_params' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertFalse($responseData['status']);
        $this->assertEquals('El carrito está vacío. No se puede procesar el pedido.', $responseData['message']);
    }

    // Añadir más métodos de prueba aquí...
}
