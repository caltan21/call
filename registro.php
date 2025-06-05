<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Sistema de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    
    <style>
        body {
            background-color: #E0FFFF; /* Un color azul cielo pálido, puedes cambiarlo por el que quieras */
            /* Propiedades para centrar el contenido vertical y horizontalmente */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Asegura que ocupe al menos el alto completo de la ventana */
            margin: 0; /* Elimina el margen por defecto del body */
        }
        /* Opcional: Si quieres un fondo blanco para la tarjeta del formulario para que resalte más */
        .card { 
            background-color: #fff; /* Fondo blanco para la tarjeta del formulario */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>
<body > <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
            <h2 class="card-title text-center mb-4">Registrarse</h2>
            <form action="../controllers/UsuarioController.php?op=registrar" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu Nombre Completo" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@dominio.com" required>
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Crea tu usuario" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Tu contraseña" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Repite tu contraseña" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Registrarme</button>
                </div>
            </form>
            <p class="mt-3 text-center">
                ¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión aquí</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
    <script>
        <?php if (isset($_SESSION['mensaje'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['mensaje']['tipo']; ?>', 
                title: '<?php echo $_SESSION['mensaje']['texto']; ?>',
                showConfirmButton: false,
                timer: 3000 
            });
            <?php unset($_SESSION['mensaje']);  ?>
        <?php endif; ?>
    </script>
</body>
</html>