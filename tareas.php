<?php
session_start();
require 'conexion_bbdd.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Añadir una tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descripcion'])) {
    $descripcion = $_POST['descripcion'];
    $fecha_limite = $_POST['fecha_limite'];

    // Preparar y ejecutar la consulta de inserción
    $sql = "INSERT INTO tareas (id_usuario, descripcion, fecha_limite) VALUES ('$id_usuario', '$descripcion', '$fecha_limite')";
    if (ejecutarConsulta($sql)) {
        // Establecer una variable de sesión para el mensaje
        $_SESSION['mensaje'] = 'Tarea añadida correctamente.';
        // Redirigir para evitar que se vuelva a enviar el formulario
        header('Location: tareas.php');
        exit();
    } else {
        $_SESSION['mensaje'] = 'Error al añadir la tarea.';
        header('Location: tareas.php');
        exit();
    }
}

// Eliminar una tarea
if (isset($_GET['eliminar'])) {
    $id_tarea = $_GET['eliminar'];
    // Preparar y ejecutar la consulta de eliminación
    $sql = "DELETE FROM tareas WHERE id_tarea = '$id_tarea' AND id_usuario = '$id_usuario'";
    if (ejecutarConsulta($sql)) {
        $_SESSION['mensaje'] = 'Tarea eliminada correctamente.';
    } else {
        $_SESSION['mensaje'] = 'Error al eliminar la tarea.';
    }
    // Redirigir después de la eliminación
    header('Location: tareas.php');
    exit();
}

// Obtener tareas
$sql = "SELECT * FROM tareas WHERE id_usuario = '$id_usuario'";
$resultado = ejecutarConsulta($sql);
$tareas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// Liberar resultado y cerrar conexión (opcional, depende del flujo de tu aplicación)
mysqli_free_result($resultado);
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Confirmación de eliminación con SweetAlert
            $('.btn-danger').click(function(e) {
                e.preventDefault(); // Evitar el comportamiento por defecto del enlace
                var href = $(this).attr('href'); // Obtener el href del enlace
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, redirigir al enlace
                        window.location.href = href;
                    }
                });
            });

            // Mostrar mensaje de SweetAlert si hay un mensaje en la sesión
            <?php if (isset($_SESSION['mensaje'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '<?= $_SESSION['mensaje'] ?>',
                    confirmButtonText: 'Aceptar'
                });
                <?php unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo 
                ?>
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
        <h2 class="text-center">Tus Tareas</h2>

        <!-- Formulario para añadir tarea -->
        <form action="tareas.php" method="post" class="mb-3">
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción de la tarea</label>
                <input type="text" name="descripcion" id="descripcion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha_limite" class="form-label">Fecha límite</label>
                <input type="date" name="fecha_limite" id="fecha_limite" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Añadir Tarea</button>
        </form>

        <!-- Listado de tareas -->
        <ul class="list-group">
            <?php foreach ($tareas as $tarea): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($tarea['descripcion']) ?> - <?= htmlspecialchars($tarea['fecha_limite']) ?>
                    <a href="tareas.php?eliminar=<?= $tarea['id_tarea'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>