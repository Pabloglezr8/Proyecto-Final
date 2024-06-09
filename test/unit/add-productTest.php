<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '../templates/admin.php'; 

class CreateProductTest extends TestCase {
    public function testCreateProductSuccess() {
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
        $_POST["name"] = "Nuevo Producto";
        $_POST["price"] = "10";
        $_POST["description"] = "Descripción del nuevo producto";
        $_FILES["img"]["name"] = "imagen.jpg"; // Suponiendo que se sube una imagen válida

        // Ejecutar la función de creación de producto
        ob_start(); // Capturar salida de posibles mensajes
        listProducto($mockPDO, $message, $messageClass);
        $output = ob_get_clean();

        // Verificar que se haya creado el producto correctamente
        $this->assertStringContainsString("Producto añadido correctamente.", $output);
        $this->assertEquals("success", $messageClass);
    }

    public function testCreateProductFailure() {
        // Mock de la función connectDB para simular una falla
        function connectDB() {
            return false;
        }

        // Datos de prueba
        $_POST["name"] = "Nuevo Producto";
        $_POST["price"] = "10";
        $_POST["description"] = "Descripción del nuevo producto";
        $_FILES["img"]["name"] = "imagen.jpg"; // Suponiendo que se sube una imagen válida

        // Ejecutar la función de creación de producto
        ob_start(); // Capturar salida de posibles mensajes
        $output = ob_get_clean();

        // Verificar que se muestre un mensaje de error al fallar la conexión a la base de datos
        $this->assertStringContainsString("Error al conectar a la Base de Datos", $output);
    }
}
