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
                    // Limpiar el carrito o redirigir a una página de confirmación
                    window.location.href = 'order-confirmation.php';
                }
            },
            error: function() {
                $('#order-message').text('Error al procesar el pedido.');
            }
        });
    });
});
