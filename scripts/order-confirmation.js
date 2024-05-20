    $(document).ready(function(){
        $("#finalize-order-btn").click(function(){
            var username = $("#username").val();
            var password = $("#password").val();
            var email = $("#email").val();
            var address = $("#address").val();

            $.ajax({
                url: "register.php",
                type: "POST",
                data: {
                    username: username,
                    password: password,
                    email: email,
                    address: address
                },
                success: function(response){
                    alert(response); // Muestra la respuesta del servidor
                    // Aquí puedes redirigir al usuario a otra página si es necesario
                }
            });
        });
    });
