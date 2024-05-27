$(document).ready(function(){
    // Al hacer clic en el botón, mostrar u ocultar el input del código
    $("#show-code-btn").click(function(){
        $(".code-input-container").toggle();
    });

    // Al hacer clic en el botón de enviar
    $("#place-register-btn").click(function(){
        // Obtener los valores del formulario
        var name = $("#name").val();
        var surname = $("#surname").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var code = $("#code").val();
        
        // Verificar si el código es válido
        var admin_code = "admin"; // Reemplaza 'admin' con tu código de administrador predefinido
        var role = 1; // Por defecto, asignar el rol 1
        
        if(code === admin_code){
            role = 0; // Si el código es válido, asignar el rol 0
        } else if(code !== '' && code !== admin_code){
            $("#register-message").html("El código no es válido").addClass("color-message-error");
            return; // Salir de la función si el código no es válido
        }
        
        // Hashear la contraseña (asegurándote de que la librería de hashing esté incluida si es necesario)
        var hashed_password = $.trim(password); // Suponiendo que ya has importado jQuery
        
        // Verificar si todos los campos están llenos
        if(name === '' || surname === '' || email === '' || password === ''){
            $("#register-message").html("Por favor, llene todos los campos").addClass("color-message-error");
            return; // Salir de la función si algún campo está vacío
        }
        
        // Asignar el valor del rol al campo oculto y enviar el formulario
        $("#role").val(role);
        
        // Enviar el formulario mediante AJAX
        $.ajax({
            url: 'register.php',
            type: 'POST',
            data: {
                name: name,
                surname: surname,
                email: email,
                password: hashed_password,
                role: role
            },
            success: function(response) {
                if (response.status) {
                    window.location.href = '../api/login.php';
                } else {
                    $("#register-message").html(response.message).addClass("color-message-error");
                }
            },
            error: function() {
                $("#register-message").html("Error al registrar. Inténtelo de nuevo.").addClass("color-message-error");
            }
        });
    });
});
