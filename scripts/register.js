$(document).ready(function(){
    $('#show-code-btn').click(function(event){
        event.preventDefault();
        $('.code-input-container').toggle();
    });

    $('#register-form').submit(function(event){
        event.preventDefault(); // Evitar el envío convencional del formulario

        var formData = $(this).serialize(); // Serializar los datos del formulario

        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#register-message').html("<p class='" + (response.success ? "success" : "error") + "'>" + response.message + "</p>");
                if (response.success) {
                    // Redirigir al usuario al login si el registro es exitoso
                    window.location.href = "login.php";
                }
            },
            error: function() {
                $('#register-message').html("<p class='error'>Error en la petición AJAX.</p>");
            }
        });
    });
});
