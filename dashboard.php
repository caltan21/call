<?php
session_start();
// Verificar si el usuario está logueado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            background-color:rgb(74, 153, 195); /* ¡Verde más intenso pero no oscuro para el fondo del Dashboard! */
        }

        /* Estilo personalizado para el botón Celeste Distinto (se mantiene) */
        .btn-celeste-distinto {
            background-color: #5bc0de; /* Un celeste más vibrante (Bootstrap btn-info original) */
            border-color: #5bc0de;
            color: white; /* Para que el texto se vea bien */
        }

        /* Puedes añadir aquí estilos para el botón plomo si no te gusta el btn-secondary */
        /* Ejemplo de un plomo un poco más oscuro si lo prefieres:
        .btn-plomo-personalizado {
            background-color: #495057;
            border-color: #495057;
            color: white;
        }
        */
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Sistema de Ventas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="articulos.php">Artículos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="facturas.php">Facturas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="boletas.php">Boletas</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../controllers/UsuarioController.php?op=logout">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION["usuario"]); ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="alert alert-success" role="alert">
            ¡Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?>! Has iniciado sesión correctamente.
        </div>
        <p>Tu usuario: <?php echo htmlspecialchars($_SESSION["usuario"]); ?></p>
        <p>Tu email: <?php echo htmlspecialchars($_SESSION["email"]); ?></p>

        <div class="row mt-4 justify-content-center"> <div class="col-md-6 mb-3"> <div class="card text-white bg-primary"> 
                    <div class="card-header">Total de Ventas</div>
                    <div class="card-body">
                        <h5 class="card-title" id="totalVentasDisplay">Cargando...</h5> 
                        <p class="card-text">Monto total acumulado de todas las ventas (Boletas y Facturas).</p>
                    </div>
                </div>
            </div>
            </div>
        <div class="d-grid gap-2 col-6 mx-auto mt-4">
            <a href="articulos.php" class="btn btn-secondary btn-lg">Gestionar Artículos</a>
            
            <a href="facturas.php" class="btn btn-celeste-distinto btn-lg">Gestionar Facturas</a>
            
            <a href="boletas.php" class="btn btn-secondary btn-lg">Gestionar Boletas</a>
            
            <a href="../controllers/UsuarioController.php?op=logout" class="btn btn-danger btn-lg">Cerrar Sesión</a>
        </div>

        <div class="row mt-5"> 
            <div class="col-md-12 text-center"> 
                <img src="../assets/img/logo_senati.jpg.png" alt="Logo de SENATI" class="img-fluid" style="max-width: 350px; height: auto;">
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Realizar una llamada AJAX para obtener el total de ventas
            $.ajax({
                url: '../controllers/DashboardController.php?op=get_total_ventas', // Ruta a tu controlador DashboardController
                type: 'GET', // Método GET porque solo estamos pidiendo datos
                dataType: 'json', // Esperamos una respuesta en formato JSON
                success: function(response) {
                    // Si la llamada es exitosa, actualiza el texto de la tarjeta
                    if (response && response.total_ventas !== null) {
                        // Formatear el número como moneda (ej. "S/ 1234.50")
                        let formattedTotal = parseFloat(response.total_ventas).toLocaleString('es-PE', {
                            style: 'currency',
                            currency: 'PEN', // Moneda peruana (Soles)
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        $('#totalVentasDisplay').text(formattedTotal);
                    } else {
                        $('#totalVentasDisplay').text('S/ 0.00'); // Si no hay ventas, mostrar 0
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // En caso de error, muestra un mensaje en la consola del navegador
                    console.log("Error al cargar el total de ventas:", textStatus, errorThrown);
                    $('#totalVentasDisplay').text('Error al cargar datos');
                }
            });
        });
    </script>
</body>
</html>