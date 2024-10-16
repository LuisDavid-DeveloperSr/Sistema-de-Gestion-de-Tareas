<?php
session_start();
require 'conexion_bbdd.php';

$mensaje = ''; // Inicializamos la variable mensaje

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar si el correo ya está registrado
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = ejecutarConsulta($sql);

    if (mysqli_num_rows($resultado) > 0) {
        // Mensaje de error si el correo ya está registrado
        $mensaje = 'El correo ya está registrado.';
    } else {
        // Insertar nuevo usuario
        $sql = "INSERT INTO usuarios (nombre_usuario, email, password) VALUES ('$nombre_usuario', '$email', '$password')";
        if (ejecutarConsulta($sql)) {
            // Mensaje de éxito
            $mensaje = 'Usuario registrado exitosamente.';
        } else {
            // Mensaje de error si falla el registro
            $mensaje = 'Error al registrar el usuario.';
        }
    }

    // Liberar resultado y cerrar conexión (opcional, depende del flujo de tu aplicación)
    mysqli_free_result($resultado);
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Si hay un mensaje, mostrarlo con SweetAlert
            <?php if (!empty($mensaje)): ?>
                Swal.fire({
                    icon: '<?= strpos($mensaje, 'Error') === false ? 'success' : 'error' ?>',
                    title: '<?= strpos($mensaje, 'Error') === false ? 'Éxito' : 'Error' ?>',
                    text: '<?= $mensaje ?>',
                    confirmButtonText: 'Aceptar'
                });
            <?php endif; ?>
        });
    </script>
</head>

<body>
    <!-- Barra Inicio -->
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h5>Sistema de Gestión de Tareas</h5>
            </div>
            <div class="col text-end">
                <a href="login.php">Inicio</a>
                <a href="registrase.php">Registrarse</a>
                <a href="tareas.php">Gestionar tareas</a>
            </div>
        </div>
    </div>

    <!-- registrase -->
    <div class="container mt-5">
        <h2 class="text-center">Registro de Usuario</h2>
        <form action="registrase.php" method="post">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" name="nombre_usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
        <p class="mt-3">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>

</html>