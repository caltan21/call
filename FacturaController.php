<?php
session_start();
require_once("../config/conexion.php");
require_once("../models/Factura.php");
require_once("../models/Articulo.php"); 

$factura = new Factura();
$articulo = new Articulo(); 

$op = isset($_GET["op"]) ? $_GET["op"] : $_POST["op"];

// Verificar si el usuario está logueado para acceder a estas funciones
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

switch ($op) {
    case "guardar_factura":
        // Este caso recibirá los datos de la nueva factura desde el formulario
        $id_usuario = $_SESSION["id_usuario"];
        $articulos_vendidos_json = $_POST["articulos"];

        if (empty($articulos_vendidos_json)) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'No hay artículos en el carrito para facturar.'];
            header("Location: ../views/nueva_factura.php");
            exit();
        }

        $articulos_vendidos = json_decode($articulos_vendidos_json, true);

        if (!is_array($articulos_vendidos) || count($articulos_vendidos) === 0) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Formato de artículos no válido o carrito vacío.'];
            header("Location: ../views/nueva_factura.php");
            exit();
        }

        $id_factura_creada = $factura->insert_factura($id_usuario, $articulos_vendidos);

        if ($id_factura_creada) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Factura creada exitosamente con ID: ' . $id_factura_creada];
            header("Location: ../views/facturas.php"); // Redirigir a la lista de facturas
            exit();
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Hubo un error al crear la factura. Por favor, verifica el stock y los datos.'];
            header("Location: ../views/nueva_factura.php"); // Volver al formulario si falla
            exit();
        }
        break;

    case "listar_facturas":
        // Este caso devolverá la lista de facturas para la tabla DataTables
        $datos = $factura->get_facturas();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = $row["nombre_usuario"]; // Nombre del usuario que emitió la factura
            $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fecha_emision"]));
            $sub_array[] = number_format($row["total"], 2, '.', ',');
            $sub_array[] = $row["estado"];
            $sub_array[] = '
                <button type="button" onClick="verDetalleFactura(' . $row["id"] . ');" class="btn btn-info btn-sm">Ver Detalles</button>
                ';
            $data[] = $sub_array;
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case "listar_articulos_para_venta":
        // Devuelve solo los artículos activos para el select en el formulario de venta
        $datos = $articulo->get_articulos(); // Ya filtra por estado = 1
        echo json_encode($datos);
        break;

    case "get_articulo_por_id":
        // Devuelve la información de un artículo específico para agregar al carrito
        $id_articulo = $_POST["id_articulo"];
        $datos = $articulo->get_articulo_por_id($id_articulo); // Ya filtra por estado = 1
        echo json_encode($datos);
        break;

    case "mostrar_detalle_factura":
        // Devuelve la información de la cabecera y los detalles de una factura específica
        $id_factura = $_POST["id_factura"];
        $cabecera = $factura->get_factura_por_id($id_factura);
        $detalles = $factura->get_detalles_factura($id_factura);

        echo json_encode([
            'cabecera' => $cabecera,
            'detalles' => $detalles
        ]);
        break;
}
?>