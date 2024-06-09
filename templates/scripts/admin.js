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
        height: 400,
        width: 600,
        branding: false,
        menubar: false,
        toolbar: ['undo redo | styles | bold italic | alignleft aligncenter alignright'],
        statusbar: false,
        setup: function (editor) {
            // Función que se ejecuta al cerrar el modal
            editor.on('blur', function () {
                var content = editor.getContent().trim(); // Eliminar espacios en blanco al principio y al final
                // Verificar si el contenido está vacío
                if (content !== '') {
                    // Verificar si el contenido ya está envuelto en etiquetas <p>
                    if (!content.startsWith('<p class="parragraf">')) {
                        // Si no está envuelto, envolverlo en un párrafo con la clase especificada
                        content = '<p class="parragraf">' + content + '</p>';
                    }
                } else {
                    // Si el contenido está vacío, asignar una cadena vacía
                    content = '';
                }
                // Asignar el contenido procesado al campo de entrada
                document.getElementById('description-input').value = content;
            });
            // Configurar el plugin para procesar el contenido pegado
            editor.on('paste_preprocess', function (e) {
                // Verificar si el contenido ya está envuelto en etiquetas <p>
                if (!e.content.startsWith('<p class="parragraf">')) {
                    // Si no está envuelto, envolverlo en un párrafo con la clase especificada
                    e.content = '<p class="parragraf">' + e.content + '</p>';
                }
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
    btn.onclick = function () {
        modal.style.display = "block";
        tinymce.get('modal-editor').setContent(document.getElementById('description-input').value);
    }

    // Cuando el usuario haga clic en <span> (x), cierra el modal
    span.onclick = function () {
        modal.style.display = "none";
    }

    // Cuando el usuario haga clic en cualquier lugar fuera del modal, ciérralo
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});

document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.delete-form').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                this.submit(); // If the user confirms, submit the form
            }
        });
    });
});