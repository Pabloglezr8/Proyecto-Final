document.addEventListener('DOMContentLoaded', function () {
    const userInfo = document.getElementById('user-info');
    const pedidos = document.getElementById('pedidos');
    const mostrarPedidos = document.getElementById('mostrarPedidos');

    // letiable para mantener el estado del formulario
    let userInfoVisible = false;

    mostrarPedidos.addEventListener('click', function (e) {
        e.preventDefault(); // Prevenir el comportamiento predeterminado del botón

        // Si el formulario está visible, ocúltalo; de lo contrario, muéstralo
        if (userInfoVisible) {
            userInfo.style.display = 'block';
            pedidos.style.display = 'none';
            mostrarPedidos.textContent = 'Pedidos';
            userInfoVisible = false;
        } else {
            userInfo.style.display = 'none';
            pedidos.style.display = 'block';
            mostrarPedidos.textContent = 'Mi Información';
            userInfoVisible = true;
        }
    });

    document.getElementById('userForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        formData.append('ajax', true);  // Añadir un campo adicional para diferenciar la solicitud AJAX

        fetch('user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('order-message').textContent = data.message;
                document.getElementById('order-message').classList.remove('error');
                document.getElementById('order-message').classList.add('success');

                // Actualiza los campos con los datos nuevos
                document.getElementById('name').value = formData.get('name');
                document.getElementById('surname').value = formData.get('surname');
                document.getElementById('email').value = formData.get('email');
                document.getElementById('address').value = formData.get('address');
                document.getElementById('postal_code').value = formData.get('postal_code');
                document.getElementById('location').value = formData.get('location');
                document.getElementById('country').value = formData.get('country');
                document.getElementById('phone').value = formData.get('phone');
            } else {
                document.getElementById('order-message').textContent = data.message;
                document.getElementById('order-message').classList.add('error');
                document.getElementById('order-message').classList.remove('success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('order-message').textContent = 'Ha ocurrido un problema al procesar la solicitud.';
            document.getElementById('order-message').classList.add('error');
            document.getElementById('order-message').classList.remove('success');
        });
    });
});

// Función para mostrar la ventana modal con los detalles del pedido
function showModal(orderDetails) {
    let modal = document.getElementById("modal-detalle-pedido");
    let detalleContent = document.getElementById("detalle-pedido-content");

    // Parseamos el objeto JSON de los detalles del pedido
    let order = JSON.parse(orderDetails);

    // Creamos la tabla para mostrar los detalles del pedido
    let table = "<table>";
    table += "<thead><tr><th>Nombre</th><th>Precio</th><th>Cantidad</th><th></th></tr></thead>";
    table += "<tbody class='parragraf'>";

    let productos = order.productos.split(', ');
    let precios = order.precios.split(', ');
    let cantidades = order.cantidades.split(', ');
    let imagenes = order.imagenes.split(', ');

    // Iteramos sobre los productos del pedido y los agregamos a la tabla
    for (let i = 0; i < productos.length; i++) {
        table += "<tr>";
        table += "<td class='name'>" + productos[i] + "</td>";
        table += "<td class='price'>" + precios[i] + "€</td>";
        table += "<td class='quantity'>" + cantidades[i] + "</td>";
        table += "<td ><img src='../assets/img/productos/" + imagenes[i] + "' alt='" + productos[i] + "' style='max-width: 100px; max-height: 100px;'></td>";
        table += "</tr>";
    }

    table += "</tbody></table>";

    // Insertamos la tabla en el contenido de la ventana modal
    detalleContent.innerHTML = table;
    modal.style.display = "block";
}

function closeModal() {
    let modal = document.getElementById("modal-detalle-pedido");
    modal.style.display = "none";
}

document.addEventListener('DOMContentLoaded', () => {
    let closeButton = document.querySelector('.close');
    closeButton.addEventListener('click', closeModal);
});
