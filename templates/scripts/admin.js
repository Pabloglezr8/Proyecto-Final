document.addEventListener('DOMContentLoaded', function () {

    const producto = document.getElementById('producto');
    const pedido = document.getElementById('pedido');
    const mostrarPedidos = document.getElementById('mostrarPedidos');

    let productosVisible = false;

    mostrarPedidos.addEventListener('click', function (e) {
        e.preventDefault(); 

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
            editor.on('blur', function () {
                var content = editor.getContent().trim(); 
                if (content !== '') {
                    if (!content.startsWith('<p class="parragraf">')) {
                        content = '<p class="parragraf">' + content + '</p>';
                    }
                } else {
                    content = '';
                }
                document.getElementById('description-input').value = content;
            });
            editor.on('paste_preprocess', function (e) {
                if (!e.content.startsWith('<p class="parragraf">')) {
                    e.content = '<p class="parragraf">' + e.content + '</p>';
                }
            });
        }
    });


    // Obtener el modal
    var modal = document.getElementById("myModal");

    var btn = document.getElementById("description-button");

    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function () {
        modal.style.display = "block";
        tinymce.get('modal-editor').setContent(document.getElementById('description-input').value);
    }

    span.onclick = function () {
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
