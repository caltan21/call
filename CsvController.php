<?php
session_start();

// Seguridad: Verificar si el usuario está logueado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once("../config/conexion.php"); // Necesitamos la conexión a la base de datos
require_once("../models/Articulo.php"); // Necesitamos el modelo Articulo para obtener los datos

$op = isset($_GET["op"]) ? $_GET["op"] : null; // Obtener la operación

if ($op == "exportar_articulos_csv") { 
    $articulo = new Articulo();
    $datos = $articulo->get_articulos(); // Obtener todos los artículos desde la base de datos

    // Configurar las cabeceras HTTP para forzar la descarga del archivo CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="Articulos_Sistema_Ventas.csv"');

    // Abrir la salida estándar para escribir en ella (esto será el contenido del archivo CSV)
    $output = fopen('php://output', 'w');

    // Escribir las cabeceras del CSV (la primera fila)
    // Asegúrate de que los nombres de las columnas coincidan con los nombres de tus campos en la base de datos
    fputcsv($output, array('ID', 'Nombre', 'Descripcion', 'Precio', 'Stock', 'Fecha Creacion'));

    // Escribir los datos de los artículos en el CSV
    foreach ($datos as $row) {
        // Asegúrate de que los nombres de las claves del array $row coincidan con los nombres de tus columnas en la BD
        fputcsv($output, array(
            $row['id'],
            $row['nombre'],
            $row['descripcion'],
            $row['precio'],
            $row['stock'],
            date("d/m/Y H:i:s", strtotime($row["fecha_creacion"])) // Formatear la fecha para que sea legible
        ));
    }

    // Cerrar el archivo (buffer de salida)
    fclose($output);
    exit(); // Terminar la ejecución del script
}
?>