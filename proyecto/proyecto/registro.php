<?php
include('db/conet.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar entradas
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];  // Guardar la contraseña como texto plano
    
    // Asignar rol de "cliente" automáticamente
    $rol = 'cliente';

    // Usar consultas preparadas para evitar inyección SQL
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $nombre, $email, $password, $rol);

    // Ejecutar la consulta y verificar si se insertó correctamente
    if ($stmt->execute()) {
        header('Location: login.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Enlaza el favicon -->
    <link rel="icon" href="img/viajar.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('img/4.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9);
            width: 100%;
            max-width: 400px;
        }
        .card-header {
            background-color: #20b2aa;
            color: white;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }
        .btn-danger {
            background-color: #1e90ff;
            border: none;
            border-radius: 20px;
        }
        .btn-danger:hover {
            background-color: #0077b6;
        }
        .form-control {
            border-radius: 20px;
        }
        .card-body p {
            margin-top: 1rem;
            text-align: center;
            font-size: 1rem;
        }
        .card-body a {
            text-decoration: none;
            color: #1e90ff;
        }
        .card-body a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        Registro de Usuario
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre completo" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="ejemplo@dominio.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Registrar</button>
        </form>
        <br>
        <center><p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p></center>
    </div>
</div>
</body>
</html>
