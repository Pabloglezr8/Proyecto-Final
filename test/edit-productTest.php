<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '../templates/admin.php';

class EditProductTest extends TestCase {
    public function testEditProductSuccess() {
        // Mock de la función connectDB
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        // Configurar el mock
        $mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStatement);

        // Reemplazar la función connectDB con el mock
        function connectDB() {
            global $mockPDO;
            return $mockPDO;
        }

        // Datos de prueba
        $_POST["name"] = "New Product Name";
        $_POST["price"] = "10.99";
        $_POST["description"] = "New Product Description";
        $_POST["current_img"] = "current_image.jpg";
        $_POST["id"] = 1; // Supongamos que el producto a editar tiene ID 1

        // Ejecutar la función de edición de producto
        ob_start(); // Capturar salida de posibles mensajes
        listProducto($mockPDO, $message, $messageClass);
        $output = ob_get_clean();

        // Verificar que se haya actualizado el producto correctamente
        $this->assertStringContainsString("Producto actualizado correctamente.", $output);
        $this->assertEquals("success", $messageClass);
    }

    public function testEditProductFailure() {
        // Mock de la función connectDB para simular una falla
        function connectDB() {
            return false;
        }

        // Datos de prueba
        $_POST["name"] = "New Product Name";
        $_POST["price"] = "10.99";
        $_POST["description"] = "New Product Description";
        $_POST["current_img"] = "current_image.jpg";
        $_POST["id"] = 1; // Supongamos que el producto a editar tiene ID 1

        // Ejecutar la función de edición de producto
        ob_start(); // Capturar salida de posibles mensajes
        $output = ob_get_clean();

        // Verificar que se muestre un mensaje de error al fallar la conexión a la base de datos
        $this->assertStringContainsString("Error al conectar a la Base de Datos", $output);
    }
}
