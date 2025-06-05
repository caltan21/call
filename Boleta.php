<?php
require_once("../config/conexion.php");
require_once("Articulo.php"); // Necesitamos el modelo de Artículo para actualizar el stock

class Boleta extends Conexion {

    // Método para insertar una nueva boleta y sus detalles
    public function insert_boleta($id_usuario, $articulos_vendidos) {
        $conectar = parent::Conectar();
        parent::set_names();

        try {
            // Iniciar una transacción
            $conectar->beginTransaction();

            // 1. Insertar la cabecera de la boleta
            $sql_boleta = "INSERT INTO boletas (id_usuario, fecha_emision, total, estado) VALUES (?, NOW(), ?, 'Pendiente')";
            $stmt_boleta = $conectar->prepare($sql_boleta);

            // Calcular el total de la boleta antes de insertar
            $total_boleta = 0;
            foreach ($articulos_vendidos as $item) {
                $total_boleta += $item['cantidad'] * $item['precio_unitario'];
            }

            $stmt_boleta->bindValue(1, $id_usuario);
            $stmt_boleta->bindValue(2, $total_boleta);
            $stmt_boleta->execute();

            // Obtener el ID de la boleta recién insertada
            $id_boleta = $conectar->lastInsertId();

            // 2. Insertar los detalles de la boleta y actualizar el stock de los artículos
            $sql_detalle = "INSERT INTO detalles_boleta (id_boleta, id_articulo, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt_detalle = $conectar->prepare($sql_detalle);

            $sql_update_stock = "UPDATE articulos SET stock = stock - ? WHERE id = ?";
            $stmt_update_stock = $conectar->prepare($sql_update_stock);

            foreach ($articulos_vendidos as $item) {
                // Validar que la cantidad no sea negativa o cero
                if ($item['cantidad'] <= 0) {
                    throw new Exception("La cantidad del artículo no puede ser cero o negativa.");
                }

                // Verificar stock disponible antes de restar
                $articulo_model = new Articulo();
                $articulo_info = $articulo_model->get_articulo_por_id($item['id_articulo']);
                if (!$articulo_info || $articulo_info['stock'] < $item['cantidad']) {
                    throw new Exception("Stock insuficiente para el artículo: " . ($articulo_info ? $articulo_info['nombre'] : 'Desconocido'));
                }

                // Insertar detalle
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $stmt_detalle->bindValue(1, $id_boleta);
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
            return $id_boleta; // Devolver el ID de la boleta creada
        } catch (Exception $e) {
            // Si algo falla, revertir la transacción
            $conectar->rollBack();
            error_log("Error al crear boleta: " . $e->getMessage()); // Registrar el error en el log
            return false; // Indicar que la operación falló
        }
    }

    // Método para obtener todas las boletas
    public function get_boletas() {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT b.*, u.nombre AS nombre_usuario
                FROM boletas b
                INNER JOIN usuarios u ON b.id_usuario = u.id
                ORDER BY b.fecha_emision DESC";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener los detalles de una boleta específica
    public function get_detalles_boleta($id_boleta) {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT db.*, a.nombre AS nombre_articulo, a.descripcion AS descripcion_articulo
                FROM detalles_boleta db
                INNER JOIN articulos a ON db.id_articulo = a.id
                WHERE db.id_boleta = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_boleta);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener una boleta específica (cabecera)
    public function get_boleta_por_id($id_boleta) {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT b.*, u.nombre AS nombre_usuario, u.email AS email_usuario
                FROM boletas b
                INNER JOIN usuarios u ON b.id_usuario = u.id
                WHERE b.id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id_boleta);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>