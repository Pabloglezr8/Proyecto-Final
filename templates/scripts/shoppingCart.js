document.addEventListener('DOMContentLoaded', function() {
    var cartButton = document.getElementById('cart-button');
    var cartMenu = document.getElementById('cart-menu');

    cartButton.addEventListener('click', function() {
        if (cartMenu.style.right === '0px') {
            cartMenu.style.right = '-350px'; // Oculta el menú desplegable
            cartButton.innerHTML = "Carrito"; // Cambia el contenido del botón a su estado original
            cartButton.dataset.menuState = "closed";
        } else {
            cartMenu.style.right = '0px'; // Muestra el menú desplegable
            cartButton.innerHTML = "X"; // Cambia el contenido del botón a "X"
            cartButton.dataset.menuState = "open";
        }
    });
});