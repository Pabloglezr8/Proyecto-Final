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

document.getElementById('payment-method').addEventListener('change', function() {
    var paymentMethod = this.value;
    var instructions = document.getElementById('bank-transfer-instructions');
    if (paymentMethod === 'transferencia') {
        instructions.style.display = 'block';
    } else {
        instructions.style.display = 'none';
    }
});