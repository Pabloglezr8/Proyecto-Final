$(document).ready(function() {
    $("#loginForm").on("submit", function(event) {
        event.preventDefault();

        var email = $("#email").val();
        var password = $("#password").val();

        $.ajax({
            url: "../api/login-process.php",
            type: "POST",
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                console.log("Server response:", response);
                if (response.success) {
                    $("#message").html("<p class='success'>" + response.message + "</p>");
                    // Redirigir al usuario al index
                    window.location.href = "../index.php";
                } else {
                    $("#message").html("<p class='error'>" + response.message + "</p>");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error in AJAX request: ", status, error);
                $("#message").html("<p class='message error'>Error en la petición AJAX</p>");
            }
        });
    });
});
