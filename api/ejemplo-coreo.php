<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/confirmation-mail.css">
<style>
    body {
    font-family: Arial, sans-serif;
    color: #1c1c1c;
}

.header {
    margin: 0 auto;
    width: 30%;
    display: flex;
    flex-direction: column;
    align-items: center;
    }
    
    .title {
    margin: 20px auto;
    background-color: #0e3083;
    width: 100%;
    text-align: center;
    border-radius: 20px;
    padding: 10px;
    color: #f29400;
}

.saludo {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    margin: 30px auto;
    width: 30%;
    color: #0e3083;
}

.parragraf {
    font-size: 1.1em;
}

.details {
    background-color: #c1c1c1;
    border-radius: 20px;
    display: flex;
    flex-direction: row;
    margin: 0 auto;
    width: 60%;
    justify-content: space-around;
}

table {
    margin: 30px auto;
    width: 60%;
    border-collapse: collapse;
}

th {
    background-color: #c1c1c1;
}

tr {
    border-bottom: #0e3083;
}

.total-price {
    margin: 50px auto;
    padding: 20px;
    width: 30%;
    background-color: #0e3083;
    color: #f0f0f0;
    border-radius: 20px;
    text-align: center;
}

.footer {
    margin: 0 auto;
    width: 70%;
    border-bottom: 4px solid #f29400;

}

</style>

</head>
<body>
<body>
    <div class='container'>
        <div class='header'>
            <img src='https://practicas.pagespeedwordpress.com/assets/img/logoCompleto.png' alt='Ferreteria Vegagrande' class='logo'/>
            <h1 class="title">Gracias por tu compra</h1>
        </div>
        <div class="saludo">
            <p class="parragraf"><strong>Hola $name $surname,</strong></p>
            <p class="parragraf"><strong>Aquí están los detalles de tu compra:</strong></p>
        </div>
        <div class='details'>
            <div class="detail-one">
                <p class="parragraf"><strong>Número de Pedido:</strong> $pedidoId</p>
                <p class="parragraf"><strong>Fecha del Pedido:</strong> " . date('d-m-Y H:i:s') . "</p>
                <p class="parragraf"><strong>Nombre:</strong> $name $surname</p>
            </div>
            <div class="detail-two">
                <p class="parragraf"><strong>Dirección:</strong> $address, $postalCode, $location, $country</p>
                <p class="parragraf"><strong>Método de Pago:</strong> $paymentMethod</p>
                <p class="parragraf"><strong>Método de Envío:</strong> $shipmentMethod</p>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>";

// Generar filas de productos comprados
foreach ($_SESSION['cart'] as $productId => $quantity) {
    $stmt = $conn->prepare("SELECT name, price FROM productos WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $productName = $product['name'];
    $productPrice = $product['price'];
    $productTotal = $productPrice * $quantity;

    $message .= "
                <tr>
                    <td>$productName</td>
                    <td>$quantity</td>
                    <td>$productPrice €</td>
                    <td>$productTotal €</td>
                </tr>";
}

$message .= "
            </tbody>
        </table>
        <p class="total-price parragraf"><strong>Total del Pedido:</strong> $totalPrice €</p>
        <div class='footer'>
            <p class="parragraf">Saludos,<br>Ferretería Vegagrande</p>
        </div>
    </div>
</body>
</html>";