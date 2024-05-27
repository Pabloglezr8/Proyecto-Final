$(document).ready(function() {
    $('#order-form').submit(function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma convencional
        var formData = $(this).serialize(); // Obtener datos del formulario

        $.ajax({
            url: 'order-process.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#order-message').text(response.message);
                if (response.status) {
                    // Redirigir a una página de confirmación
                    window.location.href = 'order-confirmation.php?order_id=' + response.order_id;
                }
            },
            error: function() {
                $('#order-message').text('Error al procesar el pedido.');
            }
        });
    });
    
});

$(document).ready(function() {
    $("#loginForm").on("submit", function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de la forma tradicional

        var email = $("#email").val();
        var password = $("#password").val();

        $.ajax({
            url: "login-process.php",
            type: "POST",
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                // Agrega depuración aquí para ver la respuesta completa
                console.log("Server response:", response);

                try {
                    // Verifica que response sea un objeto JSON
                    if (typeof response === 'object') {
                        if (response.success) {
                            // Verifica si la respuesta incluye una URL de redireccionamiento
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                // Si no hay URL de redireccionamiento, redirige al índice por defecto
                                window.location.href = "../api/order.php";
                            }
                        } else {
                            $("#message").html("<p class='message error'>" + response.message + "</p>");
                        }
                    } else {
                        throw new Error("Invalid JSON response");
                    }
                } catch (e) {
                    console.error("Error parsing JSON response: ", e);
                    $("#message").html("<p class='message error'>Error en la respuesta del servidor</p>");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error in AJAX request: ", status, error);
                $("#message").html("<p class='message error'>Error en la petición AJAX</p>");
            }
        });
    });
});

document.getElementById('payment-method').addEventListener('change', function() {
    var paymentMethod = this.value;
    var instructions = document.getElementById('bank-transfer-instructions');
    if (paymentMethod === 'transferencia') {
        instructions.style.display = 'block';
    } else {
        instructions.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var mensaje = document.getElementById('order-login');
    var loginForm = document.getElementById('loginForm');
    var mostrarFormulario = document.getElementById('mostrarFormulario');
  
    // Función para mostrar el formulario al hacer clic en el enlace
    mostrarFormulario.addEventListener('click', function(e) {
      e.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
      mensaje.style.display = 'none'; // Ocultar el mensaje inicial
      loginForm.style.display = 'flex'; // Mostrar el formulario de inicio de sesión
    });
  });