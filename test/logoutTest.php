<?php

use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase {
    public function testLogout() {
        // Iniciar una sesión simulada
        $_SESSION["id"] = 1;
        $_SESSION["name"] = "John";
        $_SESSION["email"] = "john@example.com";

        // Ejecutar la lógica de cierre de sesión
        require __DIR__ . '/../api/logout.php';

        // Verificar que todas las variables de sesión se han eliminado
        $this->assertEmpty($_SESSION);
    }
}
