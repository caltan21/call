<?php
require_once("../config/conexion.php"); 

class Usuario extends Conexion {

    // Método para registrar un nuevo usuario
    public function registrar_usuario($nombre, $email, $usuario, $password) {
        $conectar = parent::Conectar();
        parent::set_names();

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, usuario, password, fecha_registro) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $nombre);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, $usuario);
        $stmt->bindValue(4, $password_hash);
        $stmt->execute();
        return $stmt->rowCount(); // Devuelve el número de filas afectadas 
    }

    // Método para verificar si un usuario existe para el login
    public function login_usuario($usuario_ingresado, $password_ingresada) {
        $conectar = parent::Conectar();
        parent::set_names();

        $sql = "SELECT * FROM usuarios WHERE usuario = ? OR email = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usuario_ingresado);
        $stmt->bindValue(2, $usuario_ingresado); 
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC); 

        if ($resultado) {
            // Verificar la contraseña cifrada
            if (password_verify($password_ingresada, $resultado["password"])) {
                return $resultado; // Devuelve los datos del usuario si las credenciales son correctas
            } else {
                return false; // Contraseña incorrecta
            }
        } else {
            return false; // Usuario no encontrado
        }
    }

    // Método para verificar si un usuario o email ya existen (para evitar duplicados al registrar)
    public function verificar_existencia($usuario, $email) {
        $conectar = parent::Conectar();
        parent::set_names();

        $sql = "SELECT * FROM usuarios WHERE usuario = ? OR email = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usuario);
        $stmt->bindValue(2, $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); }}
?>