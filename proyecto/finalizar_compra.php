<?php
session_start();
include('db/conet.php'); // Asegúrate de que esta ruta sea correcta

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

// Función para formatear moneda
function formatoMoneda($numero) {
    return 'R$ ' . number_format($numero, 2, ',', '.');
}

// Calcular totales
$total = 0;
foreach ($productos_carrito as $producto) {
    $total += $producto['precio'];
}
$impuestos = $total * 0.1; // 10% de impuestos
$total_final = $total + $impuestos;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalización de Compra - Supermercado Ipiranga</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #31c654;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="img/ipiranga_natal.png" alt="Logo del Supermercado Ipiranga" height="50">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Alternar navegación">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ofertas.php">Ofertas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contacto.php">Contacto</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="carrito.php">
                                <i class="fas fa-shopping-cart"></i> Carrito
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-user"></i> Mi Cuenta
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container mt-5 pt-5">
        <h1 class="text-center mb-4">Finalización de Compra</h1>
        
        <h3 class="mb-3">Resumen de la Compra</h3>
        <table class="table">
            <tr>
                <th>Subtotal</th>
                <td><?php echo formatoMoneda($total); ?></td>
            </tr>
            <tr>
                <th>Impuestos (10%)</th>
                <td><?php echo formatoMoneda($impuestos); ?></td>
            </tr>
            <tr>
                <th>Total</th>
                <td><?php echo formatoMoneda($total_final); ?></td>
            </tr>
        </table>

        <h3 class="mb-3">Datos del Cliente</h3>
        <form method="POST" action="guardar_factura.php"> <!-- Cambia a tu archivo de procesamiento -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <button type="submit" class="btn btn-success">Generar Factura</button>
        </form>

        <div id="factura" class="mt-4" style="display: none;">
            <h3>Factura</h3>
            <pre id="facturaContent"></pre>
        </div>
    </main>

    <footer class="bg-dark text-light mt-5 py-3">
        <!-- Contenido del footer -->
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
