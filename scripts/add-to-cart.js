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
        quantity = parseInt(quantity);
        
        addToCart(productId, quantity);
    });

    function addToCart(productId, quantity) {
        $.ajax({
            url: '../api/add-to-cart.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.status) {
                    $('#success-message').hide().fadeIn('slow');
                    updateCartCount(response.cart_count);
                    setTimeout(function() {
                        $('#success-message').fadeOut('slow');
                    }, 2000);
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
        $('#cart-count-burger').text(quantity);
    }

    function showMessage(message, type) {
        $('#error-message').text(message).show();
    }
});
