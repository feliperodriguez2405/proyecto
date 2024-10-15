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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas</title>
    <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Font Awesome Icons -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
      <!-- Custom CSS -->
       <link href="style.css" rel="stylesheet">
    <title>Document</title>
</head>
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


<body>
    No tenemos ofertas por el momento 
    <button type="submit" href="index.php">REGRESAR A LA PAGINA PRNCIPAL </button>
    
</body>
</html>