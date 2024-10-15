<?php
session_start();
include('db/conet.php'); // Asegúrate de que esta ruta sea correcta

// Inicializar carrito y total
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
    $_SESSION['total'] = 0;
}

// Función para calcular el total del carrito
function calcularTotalCarrito($productos) {
    $total = 0;
    foreach ($productos as $producto) {
        $total += $producto['precio'];
    }
    return $total;
}

// Agregar producto al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $producto_id = intval($_POST['producto_id']);
    
    // Verificar si el producto ya está en el carrito
    if (!in_array($producto_id, $_SESSION['carrito'])) {
        $_SESSION['carrito'][] = $producto_id;
        
        // Obtener detalles del producto
        $query = "SELECT * FROM productos WHERE id = $producto_id";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            
            // Actualizar total del carrito
            $_SESSION['total'] += $producto['precio'];
        }
    }
}

// Eliminar producto del carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_id'])) {
    $eliminar_id = intval($_POST['eliminar_id']);
    if (($key = array_search($eliminar_id, $_SESSION['carrito'])) !== false) {
        unset($_SESSION['carrito'][$key]);
        
        // Obtener detalles del producto eliminado
        $query = "SELECT * FROM productos WHERE id = $eliminar_id";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $producto_eliminado = $result->fetch_assoc();
            // Actualizar total del carrito
            $_SESSION['total'] -= $producto_eliminado['precio'];
        }
    }
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Supermercado Ipiranga</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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
                            <a class="nav-link active" href="index.php">Inicio</a>
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
        <h1 class="text-center mb-4">Tu Carrito de Compras</h1>
        
        <div class="row">
            <?php if (count($productos_carrito) > 0): ?>
                <?php foreach ($productos_carrito as $producto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo $producto['imagen']; ?>" class="card-img-top" alt="<?php echo $producto['nombre']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                                <p class="card-text"><?php echo $producto['descripcion']; ?></p>
                                <p class="card-text"><strong>Precio:</strong> <?php echo formatoMoneda($producto['precio']); ?></p>
                                <form action="" method="POST" class="d-inline">
                                    <input type="hidden" name="eliminar_id" value="<?php echo $producto['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                













                <div class="col-12">
    <h3 class="mb-3">Resumen del Carrito</h3>
    <table class="table" id="resumen-carrito">
        <tr>
            <th>Subtotal</th>
            <td id="subtotal"></td>
        </tr>
        <tr>
            <th>Impuestos (10%)</th>
            <td id="impuestos"></td>
        </tr>
        <tr>
            <th>Total</th>
            <td id="total"></td>
        </tr>
    </table>
</div>

<script>
    // Arreglo de productos desde PHP
    const productosCarrito = <?php echo json_encode($productos_carrito); ?>; 

    // Función de formateo de moneda en formato correcto
    const formatoMoneda = (numero) => {
        return 'R$ ' + numero.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    // Función para calcular y mostrar los totales
    const calcularResumenCarrito = (productos) => {
        let subtotal = 0;
        productos.forEach(producto => {
            subtotal += parseFloat(producto.precio); // Asegurarse de que sea numérico
        });

        const impuestos = subtotal * 0.1; // 10% de impuestos
        const total = subtotal + impuestos;

        // Mostrar los resultados en la tabla
        document.getElementById('subtotal').textContent = formatoMoneda(subtotal);
        document.getElementById('impuestos').textContent = formatoMoneda(impuestos);
        document.getElementById('total').textContent = formatoMoneda(total);
    };

    // Ejecutar la función para calcular los totales
    calcularResumenCarrito(productosCarrito);
</script>












                    <form action="finalizar_compra.php" method="POST">
                        <button type="submit" class="btn btn-success">Finalizar Compra</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Tu carrito está vacío. ¡Ve de compras!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-dark text-light mt-5 py-3">
        <div class="container text-center">
            <p>&copy; 2023 Supermercado Ipiranga. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
