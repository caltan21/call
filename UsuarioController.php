<?php
session_start();
require_once("../config/conexion.php");
require_once("../models/Usuario.php");

$usuario = new Usuario();

// Obtener la operación a realizar
$op = isset($_GET["op"]) ? $_GET["op"] : $_POST["op"];

switch ($op) {
    case "registrar":
        // Recuperar datos del formulario de registro
        $nombre = $_POST["nombre"];
        $email = $_POST["email"];
        $user_name = $_POST["usuario"]; 
        $password = $_POST["password"];
        $password_confirm = $_POST["password_confirm"];

        // Validaciones
        if (empty($nombre) || empty($email) || empty($user_name) || empty($password) || empty($password_confirm)) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Todos los campos son obligatorios.'];
            header("Location: ../views/registro.php");
            exit();
        }

        if ($password !== $password_confirm) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Las contraseñas no coinciden.'];
            header("Location: ../views/registro.php");
            exit();
        }

        // Verificar si el usuario o email ya existen
        $existe_usuario = $usuario->verificar_existencia($user_name, $email);
        if ($existe_usuario) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'El nombre de usuario o correo electrónico ya están registrados.'];
            header("Location: ../views/registro.php");
            exit();
        }

        // Si todo es válido, registrar el usuario
        if ($usuario->registrar_usuario($nombre, $email, $user_name, $password)) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '¡Registro exitoso! Ahora puedes iniciar sesión.'];
            header("Location: ../views/login.php");
            exit();
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Hubo un error al registrar el usuario.'];
            header("Location: ../views/registro.php");
            exit();
        }
        break;

    case "login":
        // Recuperar datos del formulario de login
        $usuario_email = $_POST["usuario_email"];
        $password_ingresada = $_POST["password"];

        // Validar campos
        if (empty($usuario_email) || empty($password_ingresada)) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Usuario/Email y contraseña son obligatorios.'];
            header("Location: ../views/login.php");
            exit();
        }

        // Intentar loguear al usuario
        $data = $usuario->login_usuario($usuario_email, $password_ingresada);

        if ($data) {
            // Usuario autenticado, iniciar sesión
            $_SESSION["id_usuario"] = $data["id"];
            $_SESSION["nombre"] = $data["nombre"];
            $_SESSION["usuario"] = $data["usuario"];
            $_SESSION["email"] = $data["email"];

            // Redirigir a una página de bienvenida o al dashboard
            header("Location: ../views/dashboard.php"); // Necesitarás crear esta vista
            exit();
        } else {
            // Credenciales incorrectas
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Usuario o contraseña incorrectos.'];
            header("Location: ../views/login.php");
            exit();
        }
        break;

    case "logout":
        // Destruir todas las variables de sesión
        session_destroy();
        // Redirigir al login
        header("Location: ../views/login.php");
        exit();
        break;
}
?>