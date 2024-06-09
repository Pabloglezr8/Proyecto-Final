<?php

use PHPUnit\Framework\TestCase;

class AddToCartTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost/tu-proyecto/src/api/'
        ]);
    }

    public function testAddToCartSuccessfully()
    {
        // Datos del producto a añadir
        $data = [
            'product_id' => 1,
            'quantity' => 2
        ];

        // Hacer la solicitud POST para añadir el producto al carrito
        $response = $this->client->post('add-to-cart.php', [
            'form_params' => $data
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody(), true);
        $this->assertTrue($responseData['status']);
        $this->assertEquals('Producto añadido al carrito correctamente.', $responseData['message']);
        $this->assertArrayHasKey('cart_count', $responseData);
    }

    // Añadir más métodos de prueba aquí...
}
