<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jenny";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables para la paginación
$resultados_por_pagina = 10; // Puedes ajustar este número según tus necesidades
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Obtener la página actual, o 1 si no está definida

// Función para generar la paginación
function paginacion($pagina_actual, $total_paginas, $url) {
    $paginacion = "<nav><ul class='pagination'>";
    $paginacion .= "<li class='page-item" . ($pagina_actual == 1 ? " disabled" : "") . "'><a class='page-link' href='" . $url . "?pagina=" . ($pagina_actual - 1) . "'>Anterior</a></li>";

    for ($i = 1; $i <= $total_paginas; $i++) {
        $paginacion .= "<li class='page-item" . ($i == $pagina_actual ? " active" : "") . "'><a class='page-link' href='" . $url . "?pagina=" . $i . "'>" . $i . "</a></li>";
    }

    $paginacion .= "<li class='page-item" . ($pagina_actual == $total_paginas ? " disabled" : "") . "'><a class='page-link' href='" . $url . "?pagina=" . ($pagina_actual + 1) . "'>Siguiente</a></li>";
    $paginacion .= "</ul></nav>";

    return $paginacion;
}

// Consulta para obtener productos y sus categorías
$query_productos = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.imagen, p.cantidad, c.nombre AS categoria 
                    FROM productos p JOIN categorias c ON p.categoria_id = c.id";

$parametros = [];
$tipos = "";

// Agregar condiciones de búsqueda
if (!empty($categoria_id)) {
    $query_productos .= " WHERE p.categoria_id = ?";
    $parametros[] = $categoria_id;
    $tipos .= "i"; // Tipo de parámetro para id (integer)
}

if (!empty($busqueda)) {
    $query_productos .= (empty($categoria_id) ? " WHERE" : " AND") . " (p.nombre LIKE ? OR p.descripcion LIKE ?)";
    $parametros[] = "%$busqueda%"; // Para nombre
    $parametros[] = "%$busqueda%"; // Para descripción
    $tipos .= "ss"; // Tipo de parámetros para string
}

// Agregar paginación
$query_productos .= " ORDER BY p.id LIMIT ?, ?";
$parametros[] = ($pagina - 1) * $resultados_por_pagina; // Desplazamiento
$parametros[] = $resultados_por_pagina; // Resultados por página
$tipos .= "ii"; // Tipo de parámetros para integer

$stmt_productos = $conn->prepare($query_productos);
$stmt_productos->bind_param($tipos, ...$parametros); // Usar el operador de expansión para pasar los parámetros

$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();



// Manejar eliminación de productos
if (isset($_POST['eliminar_producto'])) {
    $producto_id = $_POST['producto_id'];
    $query = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $producto_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Producto eliminado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el producto.</div>";
    }
    $stmt->close();
}

// Manejar actualización de producto
if (isset($_POST['editar_producto'])) {
    $producto_id = $_POST['producto_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $categoria_id = $_POST['categoria_id'];
    $cantidad = $_POST['cantidad'];

    $query = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ?, categoria_id = ?, cantidad = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdsiid", $nombre, $descripcion, $precio, $imagen, $categoria_id, $cantidad, $producto_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Producto actualizado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar el producto.</div>";
    }
    $stmt->close();
}

// Obtener categorías para el filtro
$categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$resultados_por_pagina = 10;

// Obtener todas las categorías
$query_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($query_categorias);

// Calcular el total de páginas
$total_productos = $conn->query("SELECT COUNT(*) FROM productos")->fetch_row()[0];
$total_paginas = ceil($total_productos / $resultados_por_pagina);

// Calcular el desplazamiento
$offset = ($pagina - 1) * $resultados_por_pagina;

// Generar la paginación
$paginacion = paginacion($pagina, $total_paginas, 'administrar.php');

// Obtener los productos para la lista
$query_productos = "SELECT p.*, c.nombre AS categoria FROM productos p LEFT JOIN categorias c ON p.categoria_id = c.id LIMIT ?, ?";
$stmt_productos = $conn->prepare($query_productos);
$stmt_productos->bind_param("ii", $offset, $resultados_por_pagina);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

// Obtener el producto a editar
if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id'];
    $query_producto = "SELECT * FROM productos WHERE id = ?";
    $stmt_producto = $conn->prepare($query_producto);
    $stmt_producto->bind_param("i", $producto_id);
    $stmt_producto->execute();
    $producto = $stmt_producto->get_result()->fetch_assoc();
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos - Supermercado Ipiranga</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                        <a class="nav-link active" href="admin.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="administrar.php">Administrar Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ofertas.php">Ofertas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container mt-5 pt-5">
    <h1 class="text-center mb-4">Administrar Productos</h1>

    <!-- Formulario para editar productos -->
    <div class="mb-4">
        <h2>Editar Producto</h2>
        <form action="administrar.php" method="POST">
            <input type="hidden" name="producto_id" value="<?php echo isset($producto['id']) ? $producto['id'] : ''; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo isset($producto['nombre']) ? $producto['nombre'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" required><?php echo isset($producto['descripcion']) ? $producto['descripcion'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" name="precio" step="0.01" value="<?php echo isset($producto['precio']) ? $producto['precio'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen URL</label>
                <input type="text" class="form-control" name="imagen" value="<?php echo isset($producto['imagen']) ? $producto['imagen'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoría</label>
                <select class="form-select" name="categoria_id" required>
                    <option value="">Seleccione una categoría</option>
                    <?php while ($categoria = $result_categorias->fetch_assoc()): ?>
                        <option value="<?php echo $categoria['id']; ?>" <?php echo (isset($producto['categoria_id']) && $producto['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                            <?php echo $categoria['nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" name="cantidad" value="<?php echo isset($producto['cantidad']) ? $producto['cantidad'] : ''; ?>" required>
            </div>
            <button type="submit" name="editar_producto" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>

    <!-- Tabla de productos -->
    <h2>Lista de Productos</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = $result_productos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $producto['id']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo $producto['precio']; ?></td>
                    <td><?php echo $producto['categoria']; ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                            <button type="submit" name="eliminar_producto" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este producto?');">Eliminar</button>
                        </form>
                        <a href="administrar.php?producto_id=<?php echo $producto['id']; ?>" class="btn btn-warning">Editar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Mostrar paginación -->
    <div class="text-center">
        <?php echo $paginacion; ?>
    </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
