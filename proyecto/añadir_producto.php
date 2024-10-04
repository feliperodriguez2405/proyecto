<?php
include('db/conet.php'); // Asegúrate de que esta ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria_id = $_POST['categoria_id']; // Captura el ID de la categoría

    // Manejo de la subida de la imagen
    $imagen = $_FILES['imagen'];
    $imagenRuta = 'img/' . basename($imagen['name']);

    // Verifica si la imagen se subió correctamente
    if (move_uploaded_file($imagen['tmp_name'], $imagenRuta)) {
        // Preparar la consulta para evitar inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, categoria_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagenRuta, $categoria_id); // Asegúrate de que el tipo sea correcto

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Producto añadido correctamente.";
            header("Location: admin.php"); // Redirigir de nuevo al panel de administración
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }
}

// Cerrar conexión
$conn->close();
?>
