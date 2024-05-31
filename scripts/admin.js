
document.addEventListener('DOMContentLoaded', function () {

    const producto = document.getElementById('producto');
    const pedido = document.getElementById('pedido');
    const mostrarPedidos = document.getElementById('mostrarPedidos');

    // Variable para mantener el estado del formulario
    let productosVisible = false;

    mostrarPedidos.addEventListener('click', function (e) {
        e.preventDefault(); // Prevenir el comportamiento predeterminado del botón

        // Si el formulario está visible, ocúltalo; de lo contrario, muéstralo
        if (productosVisible) {
            producto.style.display = 'block';
            pedido.style.display = 'none'
            mostrarPedidos.textContent = 'Pedidos';
            productosVisible = false;
        } else {
            producto.style.display = 'none';
            pedido.style.display = 'block';
            mostrarPedidos.textContent = 'Productos';
            productosVisible = true;
        }
    });

            // Inicializa TinyMCE
            tinymce.init({
                selector: '#modal-editor',
                language: 'es',
                height: 500,
                width: 1000,
                branding: false,
                menubar: false,
                toolbar: ['undo redo | styles | bold italic | alignleft aligncenter alignright'],
                statusbar: false,
                setup: function(editor) {
                    // Función que se ejecuta al cerrar el modal
                    editor.on('blur', function() {
                        document.getElementById('description-button').innerText = editor.getContent({format: 'text'});
                        document.getElementById('description-input').value = editor.getContent();
                    });
                }
            });

            // Obtener el modal
            var modal = document.getElementById("myModal");

            // Obtener el botón que abre el modal
            var btn = document.getElementById("description-button");

            // Obtener el elemento <span> que cierra el modal
            var span = document.getElementsByClassName("close")[0];

            // Cuando el usuario haga clic en el botón, abre el modal
            btn.onclick = function() {
                modal.style.display = "block";
                tinymce.get('modal-editor').setContent(document.getElementById('description-input').value);
            }

            // Cuando el usuario haga clic en <span> (x), cierra el modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // Cuando el usuario haga clic en cualquier lugar fuera del modal, ciérralo
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
});

