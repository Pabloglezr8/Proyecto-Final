$(document).ready(function() {
    $('#orderForm').submit(function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de forma convencional
  
            // Obtener los datos del formulario
            var formData = $(this).serializeArray();
            var formDataObject = {};
            formData.forEach(function(item) {
                formDataObject[item.name] = item.value;
            });
    
            // Etiquetas amigables para los campos
            var fieldLabels = {
                name: 'Nombre',
                surname: 'Apellido',
                email: 'Correo electrónico',
                password: 'Contraseña',
                address: 'Dirección',
                postal_code: 'Código postal',
                location: 'Localidad',
                country: 'País',
                phone: 'Teléfono',
                payment_method: 'Método de Pago',
                shipment_method: 'Método de Envío'
            };
    
            // Validar campos requeridos
            for (var field in fieldLabels) {
                if (!formDataObject[field]) {
                    $('#order-message').html("<p class='error'>El campo <strong>" + fieldLabels[field] + "</strong> es obligatorio.</p>");
                    return;
                }
            }

        // Validaciones
        if (!/^[\p{L}\s]+$/u.test(formDataObject['name'])) {
            $('#order-message').html("<p class='error'>Nombre no válido.</p>");
            return;
        }
        if (!/^[\p{L}\s]+$/u.test(formDataObject['surname'])) {
            $('#order-message').html("<p class='error'>Apellido no válido.</p>");
            return;
        }
        if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(formDataObject['email'])) {
            $('#order-message').html("<p class='error'>Correo electrónico no válido.</p>");
            return;
        }
        if (!/^\d{5}$/.test(formDataObject['postal_code'])) {
            $('#order-message').html("<p class='error'>Código postal no válido.</p>");
            return;
        }
        if (!/^[\p{L}\s]+$/u.test(formDataObject['location'])) {
            $('#order-message').html("<p class='error'>Localidad no válida.</p>");
            return;
        }
        if (!/^[\p{L}\s]+$/u.test(formDataObject['country'])) {
            $('#order-message').html("<p class='error'>País no válido.</p>");
            return;
        }
        if (!/^[0-9]{9}$/.test(formDataObject['phone'])) {
            $('#order-message').html("<p class='error'>Número de teléfono no válido.</p>");
            return;
        }

        // Si todas las validaciones pasan, enviar la solicitud AJAX
        $.ajax({
            url: '../api/order-process.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#order-message').html("<p class='error'>" + response.message + "</p>");
                if (response.status) {
                    // Redirigir a una página de confirmación
                    window.location.href = '../templates/order-confirmation.php?order_id=' + response.order_id;
                }
            },
            error: function() {
                $('#order-message').html("<p class='error'>Error en la petición AJAX.</p>");
            }
        });
    });

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

    // Manejar el cambio de método de pago
    document.getElementById('payment-method').addEventListener('change', function() {
        var paymentMethod = this.value;
        var instructions = document.getElementById('bank-transfer-instructions');
        if (paymentMethod === 'transferencia') {
            instructions.style.display = 'flex';
        } else {
            instructions.style.display = 'none';
        }
    });

    // Manejar la alternancia entre el formulario de inicio de sesión y el formulario de pedido
    var loginForm = document.getElementById('loginForm');
    var mostrarFormulario = document.getElementById('mostrarFormulario');
    var formularioVisible = false;

    mostrarFormulario.addEventListener('click', function(e) {
        e.preventDefault();

        if (formularioVisible) {
            loginForm.style.display = 'none';
            orderForm.style.display = 'unset';
            mostrarFormulario.textContent = 'Iniciar Sesión';
            formularioVisible = false;
        } else {
            loginForm.style.display = 'unset';
            orderForm.style.display = 'none';
            loginMessage.style.display = 'block';
            mostrarFormulario.textContent = 'Registrarse';
            formularioVisible = true;
        }
    });

    // Manejar el formulario de inicio de sesión
    $("#loginForm").on("submit", function(event) {
        event.preventDefault();

        var email = $("#email").val();
        var password = $("#password").val();

        $.ajax({
            url: "../api/login-process.php",
            type: "POST",
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                $("#loginMessage").html("<p class='server-response'>Correo o contraseña incorrectos</p>");
                console.log("Server response:", response);

                try {
                    if (typeof response === 'object') {
                        if (response.success) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.href = "../templates/order.php";
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