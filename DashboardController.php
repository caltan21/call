<?php
session_start();

// 1. Seguridad: Verificar si el usuario está logueado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

// 2. Incluir archivos necesarios
require_once("../config/conexion.php"); 
require_once("../models/Venta.php"); // Incluimos el nuevo modelo de Venta

// 3. Crear una instancia del modelo Venta
$venta = new Venta();

// 4. Obtener la operación solicitada
$op = isset($_GET["op"]) ? $_GET["op"] : null; 

// 5. Lógica según la operación
switch ($op) {
    case "get_total_ventas":
        $data = $venta->get_total_ventas_desde_documentos(); // Llama al método del modelo Venta

        // Asegurarse de que si el total es NULL (no hay ventas), se devuelva 0
        $total = ($data && $data['total_general'] !== null) ? $data['total_general'] : 0;
        echo json_encode(array("total_ventas" => $total));
        break;

    // Puedes añadir más casos aquí para otras estadísticas del dashboard en el futuro
}
?>