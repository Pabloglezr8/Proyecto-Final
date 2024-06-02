$(document).ready(function() {
    $('#orderForm').submit(function(event) {
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
    // Función para calcular el precio total del pedido
    function calcularPrecioTotal() {
        var precioTotal = parseFloat($('.total-price').data('total-price')); // Obtener el precio total desde el atributo de datos
        var metodoEnvio = $('#shipment-method').val();
        
        // Sumar costo adicional según el método de envío seleccionado
        if (metodoEnvio === "Envío24h") {
            precioTotal += 10;
        } else if (metodoEnvio === "Envío Normal") {
            precioTotal += 5;
        }

        // Actualizar el precio total en la interfaz
        $('.total-price').text('Coste del pedido= ' + precioTotal.toFixed(2) + ' €');
    }

    // Calcular el precio total cuando se carga la página
    calcularPrecioTotal();

    // Calcular el precio total cada vez que cambia el método de envío seleccionado
    $('#shipment-method').change(function() {
        calcularPrecioTotal();
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
                $("#loginMessage").html("<p class='server-response'>Correo o contraseña incorrectos</p>");
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
        instructions.style.display = 'flex';
    } else {
        instructions.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var loginForm = document.getElementById('loginForm');
    var mostrarFormulario = document.getElementById('mostrarFormulario');

    // Variable para mantener el estado del formulario
    var formularioVisible = false;

    // Función para alternar la visibilidad del formulario al hacer clic en el botón
    mostrarFormulario.addEventListener('click', function(e) {
        e.preventDefault(); // Prevenir el comportamiento predeterminado del botón

        // Si el formulario está visible, ocúltalo; de lo contrario, muéstralo
        if (formularioVisible) {
            loginForm.style.display = 'none';
            orderForm.style.display = 'unset';
            mostrarFormulario.textContent = 'Iniciar Sesión';


            formularioVisible = false;
        } else {
            loginForm.style.display = 'unset';
            orderForm.style.display = 'none';
            loginMessage.style.display = 'block';
            mostrarFormulario.textContent = 'Registarse';

            formularioVisible = true;
        }
    });
});
