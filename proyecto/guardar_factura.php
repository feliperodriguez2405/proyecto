<?php

session_start();
require_once 'db/conet.php'; // Asegúrate de que esta ruta sea correcta

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    // Verificar si hay productos en el carrito
    if (empty($_SESSION['carrito'])) {
        header('Location: carrito.php');
        exit();
    }

    // Obtener detalles de los productos en el carrito
    $productos_carrito = [];
    if (!empty($_SESSION['carrito'])) {
        $ids = implode(',', $_SESSION['carrito']);
        $query = "SELECT * FROM productos WHERE id IN ($ids)";
        $result = $conn->query($query);
        if ($result) {
            $productos_carrito = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    // Calcular totales
    $total = 0;
    foreach ($productos_carrito as $producto) {
        $total += $producto['precio'];
    }
    $impuestos = $total * 0.1; // 10% de impuestos
    $total_final = $total + $impuestos;

    // Preparar la consulta para guardar la factura
    $stmt = $conn->prepare("INSERT INTO facturas (nombre, email, direccion, subtotal, impuestos, total) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("sssddd", $nombre, $email, $direccion, $total, $impuestos, $total_final);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $factura_id = $stmt->insert_id;
        echo "Factura guardada con éxito. 
";

        // Crear una carta con los resultados de la factura
        $pedido_detalles = [];
        foreach ($productos_carrito as $producto) {
            $detalle = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1,
                'total' => $producto['precio']
            ];
            $pedido_detalles[] = $detalle;
        }

        // Mostrar la factura en pantalla
        echo "<h1>Factura #{$factura_id}</h1>";
        echo "<p>Fecha: ".date('d/m/Y')."</p>";
        echo "<p>Hora: ".date('H:i:s')."</p>";
        echo "<p>Pedido #{$factura_id}</p>";
        echo "<p>Nombre: {$nombre}</p>";
        echo "<p>Email: {$email}</p>";
        echo "<p>Dirección: {$direccion}</p>";

        echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
        echo "<tr style='background-color: #f0f0f0; border-bottom: 1px solid #ccc;'>";
        echo "<th style='padding: 10px; border-right: 1px solid #ccc;'>Producto</th>";
        echo "<th style='padding: 10px; border-right: 1px solid #ccc;'>Cantidad</th>";
        echo "<th style='padding: 10px; border-right: 1px solid #ccc;'>Precio</th>";
        echo "<th style='padding: 10px;'>Total</th>";
        echo "</tr>";

        foreach ($pedido_detalles as $detalle) {
            echo "<tr>";
            echo "<td style='padding: 10px; border-right: 1px solid #ccc;'>{$detalle['nombre']}</td>";
            echo "<td style='padding: 10px; border-right: 1px solid #ccc;'>{$detalle['cantidad']}</td>";
            echo "<td style='padding: 10px; border-right: 1px solid #ccc;'>\${$detalle['precio']}</td>";
            echo "<td style='padding: 10px;'>\${$detalle['total']}</td>";
            echo "</tr>";
        }

        echo "</table>";

        echo "<p>Subtotal: \${$total}</p>";
        echo "<p>Impuestos (10%): \${$impuestos}</p>";
        echo "<p>Total: \${$total_final}</p>";

        // Imprimir la factura
        echo "<button onclick='window.print()'>Imprimir factura</button>";

    } else {
        echo "Error al guardar la factura: ". $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si el formulario no fue enviado, redirigir al formulario
    header('Location: index.php');
    exit();
}