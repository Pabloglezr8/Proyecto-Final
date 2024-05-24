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
        
        // Hashear la contraseña
        var hashed_password = $.trim(password); // Suponiendo que ya has importado jQuery
        
        // Verificar si todos los campos están llenos
        if(name === '' || surname === '' || email === '' || password === ''){
            $("#register-message").html("Por favor, llene todos los campos").addClass("color-message-error");
            return; // Salir de la función si algún campo está vacío
        }
        
        // Enviar el formulario si todo está bien
        $("#role").val(role); // Asignar el valor del rol al campo oculto
        $("#register-form").submit(); // Enviar el formulario
    });
});
