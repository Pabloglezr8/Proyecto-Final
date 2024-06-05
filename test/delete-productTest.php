<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '../templates/admin.php';
class DeleteProductTest extends TestCase {
    public function testDeleteProductSuccess() {
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
        $_POST["delete_id"] = 1; // Supongamos que el producto a eliminar tiene ID 1

        // Ejecutar la función de eliminación de producto
        ob_start(); // Capturar salida de posibles mensajes
        listProducto($mockPDO, $message, $messageClass);
        $output = ob_get_clean();

        // Verificar que se haya eliminado el producto correctamente
        $this->assertStringContainsString("Producto eliminado correctamente.", $output);
        $this->assertEquals("success", $messageClass);
    }

    public function testDeleteProductFailure() {
        // Mock de la función connectDB para simular una falla
        function connectDB() {
            return false;
        }

        // Datos de prueba
        $_POST["delete_id"] = 1; // Supongamos que el producto a eliminar tiene ID 1

        // Ejecutar la función de eliminación de producto
        ob_start(); // Capturar salida de posibles mensajes
        $output = ob_get_clean();

        // Verificar que se muestre un mensaje de error al fallar la conexión a la base de datos
        $this->assertStringContainsString("Error al conectar a la Base de Datos", $output);
    }
}
