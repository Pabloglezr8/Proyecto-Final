document.addEventListener('DOMContentLoaded', function () {
    const userInfo = document.getElementById('user-info');
    const pedidos = document.getElementById('pedidos');
    const mostrarPedidos = document.getElementById('mostrarPedidos');

    // Variable para mantener el estado del formulario
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

        var formData = new FormData(this);
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
