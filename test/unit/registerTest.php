<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../templates/register.php';

class RegisterProcessTest extends TestCase {
    public function testRegisterSuccess() {
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
        $_POST["name"] = "John";
        $_POST["surname"] = "Doe";
        $_POST["email"] = "john@example.com";
        $_POST["password"] = "password123";
        $_POST["code"] = "";

        ob_start(); // Capturar salida de la función json_encode
        $response = json_decode(ob_get_clean(), true);

        $this->assertTrue($response['success']);
        $this->assertEquals("Usuario registrado correctamente", $response['message']);
    }

    public function testRegisterFailure() {
        // Mock de la función connectDB para simular una falla
        function connectDB() {
            return false;
        }

        // Datos de prueba
        $_POST["name"] = "John";
        $_POST["surname"] = "Doe";
        $_POST["email"] = "john@example.com";
        $_POST["password"] = "password123";
        $_POST["code"] = "";

        ob_start(); // Capturar salida de la función json_encode

        $response = json_decode(ob_get_clean(), true);

        $this->assertFalse($response['success']);
        $this->assertEquals("Error al conectar a la Base de Datos", $response['message']);
    }
}
