<?php
// Establecer la conexión a la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "jenny";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer el atributo PDO::ATTR_ERRMODE para mostrar errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Código para recargar la base de datos
    $sql = file_get_contents('C:\xampp\htdocs\php\proyecto\base de datos\jenny.sql');
    $conn->exec($sql);

    echo "Base de datos recargada exitosamente.";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>