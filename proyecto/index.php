<?php
include('db/conet.php'); // Asegúrate de que esta ruta sea correcta

// Inicializa la variable de búsqueda
$searchTerm = '';

// Verifica si se ha enviado una búsqueda
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    // Escapa los caracteres especiales para evitar inyecciones SQL
    $searchTerm = $conn->real_escape_string($searchTerm);
    // Realiza la consulta para buscar productos
    $query = "SELECT * FROM productos WHERE nombre LIKE '%$searchTerm%'";
} else {
    // Realiza la consulta para obtener todos los productos si no hay búsqueda
    $query = "SELECT * FROM productos";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yenny - Supermercado Ipiranga</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="style.css" rel="stylesheet">
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
                            <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Productos
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Alimentos</a></li>
                                <li><a class="dropdown-item" href="#">Bebidas</a></li>
                                <li><a class="dropdown-item" href="#">Limpieza</a></li>
                                <li><a class="dropdown-item" href="#">Higiene</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ofertas.php">Ofertas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contacto.php">Contacto</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search" method="GET" action="">
                        <input class="form-control me-2" type="search" name="search" placeholder="Buscar productos" aria-label="Buscar" value="<?php echo htmlspecialchars($searchTerm); ?>">
                        <button class="btn btn-outline-light" type="submit">Buscar</button>
                    </form>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="carrito.php">
                                <i class="fas fa-shopping-cart"></i> Carrito
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-user"></i> Iniciar sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-5 pt-5">
        <h1 class="text-center mb-4">Productos en el Supermercado Ipiranga</h1>

        <div class="row">
    <?php foreach ($result as $row):?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?php echo $row['imagen'];?>" class="card-img-top" alt="<?php echo $row['nombre'];?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['nombre'];?></h5>
                    <p class="card-text"><?php echo $row['descripcion'];?></p>
                    <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($row['precio'], 2);?></p>
                    <p class="card-text"><strong>Cantidad disponible:</strong> <?php echo $row['cantidad'];?></p>
                    <p class="card-text"><strong>Categoría:</strong> <?php echo $row['categorianombre'];?></p>
                    <form action="carrito.php" method="POST">
                        <input type="hidden" name="producto_id" value="<?php echo $row['id'];?>">
                        <button type="submit" class="btn btn-primary">Añadir al Carrito</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>
    </main>

    <footer class="bg-dark text-light mt-5 py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Sobre Nosotros</h5>
                    <p>Supermercado Ipiranga, su mejor opción para compras en Natal.</p>
                </div>
                <div class="col-md-4">
                    <h5>Enlaces Útiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light">Política de Privacidad</a></li>
                        <li><a href="#" class="text-light">Términos de Uso</a></li>
                        <li><a href="#" class="text-light">Trabaja con Nosotros</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                    <address>
                        <p>Calle Ejemplo, 123 - Natal/RN</p>
                        <p>Email: contacto@ipiranga.com</p>
                        <p>Teléfono: (84) 1234-5678</p>
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2023 Supermercado Ipiranga. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



<?php
// Cerrar conexión
$conn->close();
?>
