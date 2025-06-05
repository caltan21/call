<?php
require_once("../config/conexion.php");
require_once("Articulo.php"); 
class Factura extends Conexion {

    // Método para insertar una nueva factura y sus detalles
    public function insert_factura($id_usuario, $articulos_vendidos) {
        $conectar = parent::Conectar();
        parent::set_names();

        try {
            // Iniciar una transacción
            $conectar->beginTransaction();

            // 1. Insertar la cabecera de la factura
            $sql_factura = "INSERT INTO facturas (id_usuario, fecha_emision, total, estado) VALUES (?, NOW(), ?, 'Pendiente')";
            $stmt_factura = $conectar->prepare($sql_factura);

            // Calcular el total de la factura antes de insertar
            $total_factura = 0;
            foreach ($articulos_vendidos as $item) {
                $total_factura += $item['cantidad'] * $item['precio_unitario'];
            }

            $stmt_factura->bindValue(1, $id_usuario);
            $stmt_factura->bindValue(2, $total_factura);
            $stmt_factura->execute();

            // Obtener el ID de la factura recién insertada
            $id_factura = $conectar->lastInsertId();

            // Insertar los detalles de la factura y actualizar el stock de los artículos
            $sql_detalle = "INSERT INTO detalles_factura (id_factura, id_articulo, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt_detalle = $conectar->prepare($sql_detalle);

            $sql_update_stock = "UPDATE articulos SET stock = stock - ? WHERE id = ?";
            $stmt_update_stock = $conectar->prepare($sql_update_stock);

            foreach ($articulos_vendidos as $item) {
                // Validar que la cantidad no sea negativa o cero
                if ($item['cantidad'] <= 0) {
                    throw new Exception("La cantidad del artículo no puede ser cero o negativa.");
                }

                // Verificar stock disponible antes de restar (opcional pero muy recomendable)
                $articulo_model = new Articulo();
                $articulo_info = $articulo_model->get_articulo_por_id($item['id_articulo']);
                if (!$articulo_info || $articulo_info['stock'] < $item['cantidad']) {
                    throw new Exception("Stock insuficiente para el artículo: " . ($articulo_info ? $articulo_info['nombre'] : 'Desconocido'));
                }

                // Insertar detalle
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $stmt_detalle->bindValue(1, $id_factura);
                $stmt_detalle->bindValue(2, $item['id_articulo']);
                $stmt_detalle->bindValue(3, $item['cantidad']);
                $stmt_detalle->bindValue(4, $item['precio_unitario']);
                $stmt_detalle->bindValue(5, $subtotal);
                $stmt_detalle->execute();

                // Actualizar stock
                $stmt_update_stock->bindValue(1, $item['cantidad']);
                $stmt_update_stock->bindValue(2, $item['id_articulo']);
                $stmt_update_stock->execute();
            }

            // 3. Confirmar la transacción si todo fue exitoso
            $conectar->commit();
            return $id_factura; // Devolver el ID de la factura creada
        } catch (Exception $e) {
            // Si algo falla, revertir la transacción
            $conectar->rollBack();
            error_log("Error al crear factura: " . $e->getMessage()); // Registrar el error en el log
            return false; // Indicar que la operación falló
        }
    }

    // Método para obtener todas las facturas
    public function get_facturas() {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT f.*, u.nombre AS nombre_usuario
                FROM facturas f
                INNER JOIN usuarios u ON f.id_usuario = u.id
                ORDER BY f.fecha_emision DESC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener los detalles de una factura específica
    public function get_detalles_factura($id_factura) {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT df.*, a.nombre AS nombre_articulo, a.descripcion AS descripcion_articulo
                FROM detalles_factura df
                INNER JOIN articulos a ON df.id_articulo = a.id
                WHERE df.id_factura = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_factura);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener una factura específica (cabecera)
    public function get_factura_por_id($id_factura) {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT f.*, u.nombre AS nombre_usuario, u.email AS email_usuario
                FROM facturas f
                INNER JOIN usuarios u ON f.id_usuario = u.id
                WHERE f.id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_factura);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Puedes agregar métodos para actualizar estado de factura, anular, etc. si lo necesitas más adelante.
}
?>