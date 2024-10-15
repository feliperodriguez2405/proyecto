




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Supermercado JENNY</title>

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
                    <img src="img/ipiranga_natal.png" alt="Logo del Supermercado JENNY" height="50">
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
        <h1 class="text-center mb-4">Panel de Administración</h1>

        <!-- Formulario para añadir productos -->
        <div class="mb-4">
            <h2>Añadir Producto</h2>
            <form action="añadir_producto.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen del Producto</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria" name="categoria_id" required>
                        <option value="">Seleccione una categoría</option>
                        <option value="1">Alimentos</option>
                        <option value="2">Bebidas</option>
                        <option value="3">Limpieza</option>
                        <option value="4">Higiene</option>
                    </select>
                </div>
                <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" name="cantidad" value="<?php echo isset($producto['cantidad']) ? $producto['cantidad'] : ''; ?>" required>
            </div>
                <button type="submit" class="btn btn-primary">Añadir Producto</button>
            </form>
        </div>

        <!-- Tabla de productos -->
        <div>
            <h2>Lista de Productos</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Categoría</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se agregarán los productos desde la base de datos -->
                    <?php
                    // Conexión a la base de datos
                    include('db/conet.php');

                    // Consulta para obtener productos y sus categorías
                    $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.imagen, c.nombre AS categoria FROM productos p JOIN categorias c ON p.categoria_id = c.id";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['nombre']}</td>
                                    <td>{$row['descripcion']}</td>
                                    <td>\${$row['precio']}</td>
                                    <td><img src='{$row['imagen']}' alt='{$row['nombre']}' height='50'></td>
                                    <td>{$row['categoria']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay productos disponibles.</td></tr>";
                    }

                    // Cerrar conexión
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="bg-dark text-light mt-5 py-3">
        <div class="container">
            <div class="text-center">
                <p>&copy; 2023 Supermercado Ipiranga. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>