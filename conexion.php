<?php
class Conexion {
    protected $dbh;

    protected function Conectar() {
        try {
            // Nombre de la base de datos
            $dbname = "db_ventas_senati";
            // Host de la base de datos
            $host = "localhost";
            // Usuario de la base de datos
            $user = "root"; // Generalmente 'root' para desarrollo local
            // Contraseña de la base de datos
            $pass = "";  

            $conectar = $this->dbh = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8",
                $user,
                $pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" 
                )
            );
            return $conectar;
        } catch (PDOException $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    public function set_names() {
        return $this->dbh->query("SET NAMES 'utf8'");
    }
}
?>