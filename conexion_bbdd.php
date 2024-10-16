<?php
$usuario = "root";
$password = "";
$servidor = "localhost";
$basededatos = "gestion_tareas";

// Establecer conexión
$conexion = mysqli_connect($servidor, $usuario, $password, $basededatos);

// Verificar conexión
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Función para ejecutar consultas
function ejecutarConsulta($sql) {
    global $conexion;
    $resultado = mysqli_query($conexion, $sql);
    if (!$resultado) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }
    return $resultado;
}




