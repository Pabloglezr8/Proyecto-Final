$(document).ready(function() {
    $('.remove-from-cart-btn').click(function() {
        var productId = $(this).data('product-id');
        removeFromCart(productId);
    });

    $('.delete-product').click(function() {
        var productId = $(this).data('product-id');
        removeAllFromCart(productId);
    });

    $('#clear-cart-btn').click(function() {
        clearCart();
    });

    $('.increase-quantity-btn').click(function() {
        var productId = $(this).data('product-id');
        increaseCartItemQuantity(productId);
    });

    function removeFromCart(productId) {
        performCartAction('remove', productId);
    }

    function removeAllFromCart(productId) {
        performCartAction('remove_all', productId);
    }

    function clearCart() {
        performCartAction('clear');
    }

    function increaseCartItemQuantity(productId) {
        var quantityElement = $('.cart-quantity[data-product-id="' + productId + '"]');
        var currentQuantity = parseInt(quantityElement.text());
        quantityElement.text(currentQuantity + 1); // Incrementar la cantidad mostrada en la página
        updateTotalPrice(); // Actualizar el precio total
        performCartAction('increase_quantity', productId);
    }

    function performCartAction(action, productId = null) {
        $.ajax({
            url: 'cart-actions.php',
            type: 'POST',
            data: {
                action: action,
                product_id: productId
            },
            success: function(response) {
                if (response.status) {
                    if (action === 'remove' || action === 'remove_all' || action === 'clear') {
                        location.reload(); // Recargar la página para reflejar los cambios en la cesta
                    } else {
                        // Actualizar la cantidad mostrada en la página
                        $('.cart-quantity[data-product-id="' + productId + '"]').text(response.quantity);
                        updateTotalPrice(); // Actualizar el precio total después de cada acción
                    }
                } else {
                    alert(response.message); // Mostrar mensaje de error
                }
            },
            error: function() {
                alert('Error en la operación de la cesta.'); // Mostrar mensaje de error
            }
        });
    }

    function updateTotalPrice() {
        var totalPrice = 0;
        $('.product-container').each(function() {
            var price = parseFloat($(this).find('.product-price').text());
            var quantity = parseInt($(this).find('.cart-quantity').text());
            totalPrice += price * quantity;
        });
        $('#total-price').text(totalPrice.toFixed(2) + ' €');
    }
});
