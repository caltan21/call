<?php
session_start();

require_once("../config/conexion.php");
require_once("../models/Articulo.php");

$articulo = new Articulo();

// Obtener la operación a realizar
$op = isset($_GET["op"]) ? $_GET["op"] : $_POST["op"];

// Verificar si el usuario está logueado para acceder a estas funciones (seguridad)
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

switch ($op) {
    case "listar":
        // Este caso se usará para devolver los datos de los artículos (ej. para una tabla con DataTables)
        $datos = $articulo->get_articulos();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["nombre"];
            $sub_array[] = $row["descripcion"];
            $sub_array[] = number_format($row["precio"], 2, '.', ','); // Formatear precio
            $sub_array[] = $row["stock"];
            $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fecha_creacion"]));

            // Botones para acciones (editar, eliminar)
            // *** CAMBIO AQUÍ: btn-warning a btn-success para Editar ***
            // *** CAMBIO AQUÍ: btn-danger a btn-secondary (o btn-info) para Eliminar ***
            $sub_array[] = '
                <button type="button" onClick="editar(' . $row["id"] . ');" id="' . $row["id"] . '" class="btn btn-success btn-sm">Editar</button>
                <button type="button" onClick="eliminar(' . $row["id"] . ');" id="' . $row["id"] . '" class="btn btn-secondary btn-sm">Eliminar</button>
            ';
            // Si prefieres el botón de eliminar en un tono azul claro, usa btn-info:
            // <button type="button" onClick="eliminar(' . $row["id"] . ');" id="' . $row["id"] . '" class="btn btn-info btn-sm">Eliminar</button>


            $data[] = $sub_array;
        }

        $results = array(
            "sEcho" => 1, // Información para el DataTables
            "iTotalRecords" => count($data), // Total de registros
            "iTotalDisplayRecords" => count($data), // Total a mostrar
            "aaData" => $data // Datos
        );
        echo json_encode($results);
        break;

    case "guardar_o_actualizar":
        // Recuperar datos del formulario
        $id = $_POST["id_articulo"]; // Este campo puede venir vacío si es nuevo
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];
        $stock = $_POST["stock"];

        // Validaciones básicas
        if (empty($nombre) || empty($precio) || empty($stock)) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Los campos Nombre, Precio y Stock son obligatorios.'];
            // Dependiendo de cómo manejes el modal/redirección, podrías necesitar un manejo diferente.
            // Para fines de ejemplo, redirigimos a la lista de artículos.
            header("Location: ../views/articulos.php");
            exit();
        }
        if (!is_numeric($precio) || $precio < 0) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'El precio debe ser un número válido y positivo.'];
            header("Location: ../views/articulos.php");
            exit();
        }
        if (!is_numeric($stock) || $stock < 0 || floor($stock) != $stock) { // Stock debe ser entero no negativo
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'El stock debe ser un número entero válido y positivo.'];
            header("Location: ../views/articulos.php");
            exit();
        }

        if (empty($id)) {
            // Es un nuevo artículo
            if ($articulo->insert_articulo($nombre, $descripcion, $precio, $stock)) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Artículo registrado exitosamente.'];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Hubo un error al registrar el artículo.'];
            }
        } else {
            // Es una actualización de artículo existente
            if ($articulo->update_articulo($id, $nombre, $descripcion, $precio, $stock)) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Artículo actualizado exitosamente.'];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Hubo un error al actualizar el artículo.'];
            }
        }
        // Redirigir a la vista de artículos después de guardar/actualizar
        header("Location: ../views/articulos.php");
        exit();
        break;

    case "mostrar":
        // Para cargar los datos de un artículo en el formulario de edición
        $id_articulo = $_POST["id_articulo"];
        $datos = $articulo->get_articulo_por_id($id_articulo);
        if ($datos) {
            echo json_encode($datos); // Devolver los datos del artículo en formato JSON
        } else {
            echo json_encode(["error" => "Artículo no encontrado."]);
        }
        break;

    case "eliminar":
        // Eliminar (borrado lógico) un artículo
        $id_articulo = $_POST["id_articulo"];
        if ($articulo->delete_articulo($id_articulo)) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Artículo eliminado exitosamente.'];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Hubo un error al eliminar el artículo.'];
        }

        header("Location: ../views/articulos.php");
        exit();
        break;
}
?>