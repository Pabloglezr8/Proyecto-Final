$(document).ready(function() {
    $("#loginForm").on("submit", function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe de la forma tradicional

        var email = $("#email").val();
        var password = $("#password").val();

        $.ajax({
            url: "login-process.php",
            type: "POST",
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                // Agrega depuración aquí para ver la respuesta completa
                console.log("Server response:", response);

                try {
                    // Verifica que response sea un objeto JSON
                    if (typeof response === 'object') {
                        if (response.success) {
                            // Verifica si la respuesta incluye una URL de redireccionamiento
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                // Si no hay URL de redireccionamiento, redirige al índice por defecto
                                window.location.href = "../index.php";
                            }
                        } else {
                            $("#message").html("<p class='message error'>" + response.message + "</p>");
                        }
                    } else {
                        throw new Error("Invalid JSON response");
                    }
                } catch (e) {
                    console.error("Error parsing JSON response: ", e);
                    $("#message").html("<p class='message error'>Error en la respuesta del servidor</p>");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error in AJAX request: ", status, error);
                $("#message").html("<p class='message error'>Error en la petición AJAX</p>");
            }
        });
    });
});
