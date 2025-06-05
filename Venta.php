<?php
require_once("../config/conexion.php"); 

class Venta extends Conexion {

    public function get_total_ventas_desde_documentos() {
        // CAMBIA esta línea:
        $conectar = parent::Conectar(); // AHORA con 'C' mayúscula para que coincida con tu conexion.php
        parent::set_names();  

        // Sumar los totales de la tabla 'boletas'
        // Usamos la columna 'total' de la tabla 'boletas'
        // Asumo que 'estado' es 'Pendiente' o ajusta según tu campo de estado
        $sql_boletas = "SELECT SUM(total) as total_boletas FROM boletas WHERE estado = 'Pendiente'"; 
        $stmt_boletas = $conectar->prepare($sql_boletas);
        $stmt_boletas->execute();
        $total_boletas = $stmt_boletas->fetch(PDO::FETCH_ASSOC)['total_boletas'];

        // Sumar los totales de la tabla 'facturas'
        // Usamos la columna 'total' de la tabla 'facturas'
        // Asumo que 'estado' es 'Pendiente' o ajusta según tu campo de estado
        $sql_facturas = "SELECT SUM(total) as total_facturas FROM facturas WHERE estado = 'Pendiente'"; 
        $stmt_facturas = $conectar->prepare($sql_facturas);
        $stmt_facturas->execute();
        $total_facturas = $stmt_facturas->fetch(PDO::FETCH_ASSOC)['total_facturas'];

        // Calcular el total general
        $total_general = ($total_boletas !== null ? $total_boletas : 0) + 
                         ($total_facturas !== null ? $total_facturas : 0);

        return array("total_general" => $total_general);
    }

    // Si en el futuro necesitas registrar boletas, facturas o detalles, irían aquí.
}
?>