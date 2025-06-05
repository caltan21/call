<?php
session_start();

require_once("../config/conexion.php");
require_once("../models/Boleta.php"); // Requerimos el modelo de Boleta
require_once("../models/Articulo.php"); // Necesario para buscar artículos

$boleta = new Boleta();
$articulo = new Articulo();

$op = isset($_GET["op"]) ? $_GET["op"] : $_POST["op"];

// Verificar si el usuario está logueado para acceder a estas funciones (seguridad)
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

switch ($op) {
    case "guardar_boleta":
        $id_usuario = $_SESSION["id_usuario"];
        $articulos_vendidos_json = $_POST["articulos"];

        if (empty($articulos_vendidos_json)) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'No hay artículos en el carrito para la boleta.'];
            header("Location: ../views/nueva_boleta.php");
            exit();
        }

        $articulos_vendidos = json_decode($articulos_vendidos_json, true);

        if (!is_array($articulos_vendidos) || count($articulos_vendidos) === 0) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Formato de artículos no válido o carrito vacío.'];
            header("Location: ../views/nueva_boleta.php");
            exit();
        }

        $id_boleta_creada = $boleta->insert_boleta($id_usuario, $articulos_vendidos);

        if ($id_boleta_creada) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Boleta creada exitosamente con ID: ' . $id_boleta_creada];
            header("Location: ../views/boletas.php"); // Redirigir a la lista de boletas
            exit();
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Hubo un error al crear la boleta. Por favor, verifica el stock y los datos.'];
            header("Location: ../views/nueva_boleta.php"); // Volver al formulario si falla
            exit();
        }
        break;

    case "listar_boletas":
        // Este caso devolverá la lista de boletas para la tabla DataTables
        $datos = $boleta->get_boletas();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = $row["nombre_usuario"];
            $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fecha_emision"]));
            $sub_array[] = number_format($row["total"], 2, '.', ',');
            $sub_array[] = $row["estado"];
            $sub_array[] = '
                <button type="button" onClick="verDetalleBoleta(' . $row["id"] . ');" class="btn btn-info btn-sm">Ver Detalles</button>
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
        // Este caso puede ser reutilizado, ya que los artículos son los mismos para facturas y boletas
        $datos = $articulo->get_articulos(); // Ya filtra por estado = 1
        echo json_encode($datos);
        break;

    case "get_articulo_por_id":
        // Este caso también puede ser reutilizado
        $id_articulo = $_POST["id_articulo"];
        $datos = $articulo->get_articulo_por_id($id_articulo); // Ya filtra por estado = 1
        echo json_encode($datos);
        break;

    case "mostrar_detalle_boleta":
        $id_boleta = $_POST["id_boleta"];
        $cabecera = $boleta->get_boleta_por_id($id_boleta);
        $detalles = $boleta->get_detalles_boleta($id_boleta);

        echo json_encode([
            'cabecera' => $cabecera,
            'detalles' => $detalles
        ]);
        break;
}
?>