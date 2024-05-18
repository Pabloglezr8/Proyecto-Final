$(document).ready(function() {
    $('.add-to-cart-btn').click(function() {
        var productId = $(this).data('product-id');
        var quantity = 1;  // Aquí podrías tener una lógica para seleccionar la cantidad deseada

        $.ajax({
            url: 'add-to-cart.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.status) {
                    $('#success-message').fadeIn('slow'); // Mostrar el mensaje de éxito con fade in suave y display: inline-flex
                    updateCartCount(); // Actualizar contador del carrito
                    setTimeout(function() {
                        $('#success-message').fadeOut('slow'); // Ocultar el mensaje de éxito con fade out suave después de 3 segundos
                    }, 3000);
                } else {
                    showMessage(response.message, 'error'); // Mostrar mensaje de error
                }
            },
            error: function() {
                showMessage('Error al añadir el producto al carrito.', 'error'); // Mostrar mensaje de error
            }
        });
    });

    function updateCartCount() {
        var currentCount = parseInt($('#cart-count').text());
        var newCount = currentCount + 1;
        $('#cart-count').text(newCount);
    }

    function showMessage(message, type) {
        $('#error-message').text(message).show(); // Mostrar mensaje de error
    }
});
