<?php
session_start();
require 'conexion_bbdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta SQL
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = ejecutarConsulta($sql);

    // Verificar si se encontró un usuario
    if (mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        // Verificar la contraseña usando password_verify
        if (password_verify($password, $fila['password'])) {
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            header('Location: tareas.php');
            exit();
        } else {
            // Mensaje de error para contraseña incorrecta
            $mensaje = 'Contraseña incorrecta.';
        }
    } else {
        // Mensaje de error para usuario no encontrado
        $mensaje = 'Usuario no encontrado.';
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
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Si hay un mensaje de error, mostrarlo con SweetAlert
            <?php if (isset($mensaje)): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= $mensaje ?>',
                    confirmButtonText: 'Aceptar'
                });
            <?php endif; ?>
        });
    </script>
</head>

<body>
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
    <div class="container mt-5">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
        <p class="mt-3">¿No tienes cuenta? <a href="registrase.php">Regístrate aquí</a></p>
    </div>
</body>

</html>