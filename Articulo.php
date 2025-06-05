<?php
require_once("../config/conexion.php"); 
class Articulo extends Conexion {

    // Método para obtener todos los artículos
    public function get_articulos() {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT * FROM articulos WHERE estado = 1"; 
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un artículo por su ID
    public function get_articulo_por_id($id) {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "SELECT * FROM articulos WHERE id = ? AND estado = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para insertar un nuevo artículo
    public function insert_articulo($nombre, $descripcion, $precio, $stock) {
        $conectar = parent::Conectar();
        parent::set_names();
        // Se agrega 'estado' con valor predeterminado 1 para borrado lógico
        $sql = "INSERT INTO articulos (nombre, descripcion, precio, stock, fecha_creacion, estado) VALUES (?, ?, ?, ?, NOW(), 1)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $nombre);
        $stmt->bindValue(2, $descripcion);
        $stmt->bindValue(3, $precio);
        $stmt->bindValue(4, $stock);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Método para actualizar un artículo existente
    public function update_articulo($id, $nombre, $descripcion, $precio, $stock) {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "UPDATE articulos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $nombre);
        $stmt->bindValue(2, $descripcion);
        $stmt->bindValue(3, $precio);
        $stmt->bindValue(4, $stock);
        $stmt->bindValue(5, $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Método para eliminar (borrado lógico) un artículo
    public function delete_articulo($id) {
        $conectar = parent::Conectar();
        parent::set_names();
        $sql = "UPDATE articulos SET estado = 0 WHERE id = ?"; // Cambia el estado a 0 (inactivo)
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>