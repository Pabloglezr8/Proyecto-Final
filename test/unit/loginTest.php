<?php

use PHPUnit\Framework\TestCase;

require '../api/login-process.php';

class LoginTest extends TestCase {
    protected function setUp(): void {
        // Iniciar sesión simulada para las pruebas
        $_SESSION = [];
    }

    protected function tearDown(): void {
        // Limpiar la sesión después de cada prueba
        session_destroy();
    }

    public function testLoginSuccess() {
        // Mock de la función connectDB
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        // Configurar el mock
        $mockStatement->expects($this->once())
            ->method('execute');

        $mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn([
                "id" => 1,
                "name" => "John",
                "surname" => "Doe",
                "email" => "john@example.com",
                "password" => password_hash("password123", PASSWORD_DEFAULT),
                "address" => "123 Main St",
                "postal_code" => "12345",
                "location" => "City",
                "country" => "Country",
                "phone" => "123456789",
                "role" => "user"
            ]);

        $mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStatement);

        // Reemplazar la función connectDB con el mock
        function connectDB() {
            global $mockPDO;
            return $mockPDO;
        }

        $response = login("john@example.com", "password123");

        $this->assertTrue($response['success']);
        $this->assertEquals("Inicio de sesión exitoso", $response['message']);
        $this->assertEquals(1, $_SESSION["id"]);
        $this->assertEquals("John", $_SESSION["name"]);
        $this->assertEquals("Doe", $_SESSION["surname"]);
        $this->assertEquals("john@example.com", $_SESSION["email"]);
    }

    public function testLoginFailure() {
        // Mock de la función connectDB
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        // Configurar el mock
        $mockStatement->expects($this->once())
            ->method('execute');

        $mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $mockPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStatement);

        // Reemplazar la función connectDB con el mock
        function connectDB() {
            global $mockPDO;
            return $mockPDO;
        }

        $response = login("john@example.com", "wrongpassword");

        $this->assertFalse($response['success']);
        $this->assertEquals("Correo electrónico o contraseña incorrectos", $response['message']);
    }

    public function testDatabaseConnectionError() {
        // Mock de la función connectDB para simular una falla
        function connectDB() {
            return false;
        }

        $response = login("john@example.com", "password123");

        $this->assertFalse($response['success']);
        $this->assertEquals("Error de conexión a la base de datos", $response['message']);
    }
}
