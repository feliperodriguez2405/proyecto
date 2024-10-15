<?php
include('db/conet.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];  // Usar la contraseña en texto plano

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de email inválido.";
        exit;
    }

    // Consulta segura usando Prepared Statements
    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Comparar la contraseña ingresada con la almacenada (en texto plano)
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['id'];

            header('Location: index.php');
            exit();
        } else {
            echo "Credenciales incorrectas.";
            exit();
        }
    } else {
        echo "Usuario no encontrado.";
        exit();
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="img/viajar.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('img/4.jpg') no-repeat center center fixed; /* Ruta a la imagen en la carpeta img */
         
            background-size: cover; /* Asegura que la imagen cubra todo el fondo */
            color: #333; /* Color de texto */
            height: 100vh; /* Altura completa de la ventana */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco semi-transparente para la tarjeta */
            width: 100%;
            max-width: 400px; /* Ajusta el tamaño máximo de la tarjeta */
        }
        .card-header {
            background-color: #20b2aa; /* Verde agua */
            color: white;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }
        .btn-danger {
            background-color: #1e90ff; /* Azul viaje */
            border: none;
            border-radius: 20px;
        }
        .btn-danger:hover {
            background-color: #0077b6; /* Azul oscuro */
        }
        .form-control {
            border-radius: 20px;
        }
        /* Estilos para eliminar el subrayado del enlace y ajustar el color */
        .card-body p {
            margin-top: 1rem;
            text-align: center;
            font-size: 1rem;
        }
        .card-body a {
            text-decoration: none; /* Elimina el subrayado */
            color: #1e90ff; /* Color del enlace */
        }
        .card-body a:hover {
            text-decoration: underline; /* Subrayado en hover para mejorar la accesibilidad */
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-header">
            Sistema Login
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="new-email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" autocomplete="new-password" required>
                </div>
                <button type="submit" class="btn btn-danger w-100" name="iniciar_sesion">Login</button>
            </form>
            <br>
            <p>No tienes cuenta? <a href="registro.php">Regístrate aquí</a>.</p>
        </div>
    </div>

</body>

</html>