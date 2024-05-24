$(document).ready(function() {
    // Controlador de eventos para el botón original
    $('.add-to-cart-btn').click(function() {
        var productId = $(this).data('product-id');
        var quantity = 1;  // Cantidad fija
        
        addToCart(productId, quantity);
    });

    // Controlador de eventos para el botón dinámico
    $('.add-to-cart-btn-product').click(function() {
        var productId = $(this).data('product-id');
        var quantity = $(this).prev('.quantity-input').val();
        quantity = parseInt(quantity); // Convertir a entero
        
        addToCart(productId, quantity);
    });

    function addToCart(productId, quantity) {
        $.ajax({
            url: 'add-to-cart.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.status) {
                    $('#success-message').fadeIn('slow');
                    updateCartCount(response.cart_count);
                    updateCartView(); // Actualiza la visualización de la cesta
                    setTimeout(function() {
                        $('#success-message').fadeOut('slow');
                    }, 3000);
                } else {
                    showMessage(response.message, 'error');
                }
            },
            error: function() {
                showMessage('Error al añadir el producto al carrito.', 'error');
            }
        });
    }

    function updateCartCount(quantity) {
        $('#cart-count').text(quantity);
    }

    function showMessage(message, type) {
        $('#error-message').text(message).show();
    }
});
