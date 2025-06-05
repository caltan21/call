<?php
session_start();
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
    <title>Nueva Boleta - Sistema de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #FFDAB9; /* Durazno Suave */
        }
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
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
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
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="nueva_boleta.php">Nueva Boleta</a>
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
    <div class="container mt-4">
        <h2 class="mb-4">Crear Nueva Boleta</h2>

        <form id="form_nueva_boleta" method="POST" action="../controllers/BoletaController.php?op=guardar_boleta">
            <div class="card shadow-sm mb-4">
                <div class="card-header">Datos del Cliente (Opcional por ahora)</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="cliente_nombre" class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" placeholder="Nombre del Cliente">
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">Agregar Artículos</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="id_articulo" class="form-label">Seleccionar Artículo</label>
                            <select class="form-control" id="id_articulo" name="id_articulo" style="width: 100%;">
                                <option value="">Seleccione un artículo</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="precio_unitario_display" class="form-label">Precio Unitario</label>
                            <input type="text" class="form-control" id="precio_unitario_display" readonly>
                            <input type="hidden" id="precio_unitario_hidden">
                        </div>
                        <div class="col-md-2">
                            <label for="stock_display" class="form-label">Stock Actual</label>
                            <input type="text" class="form-control" id="stock_display" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1">
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-info" id="btn_agregar_articulo"><i class="bi bi-cart-plus"></i> Añadir al Carrito</button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">Detalle de Artículos en Boleta</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="carrito_articulos_body">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aún no hay artículos en el carrito.</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">TOTAL A PAGAR:</th>
                                    <th colspan="2">S/ <span id="total_boleta_display">0.00</span></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" name="articulos" id="articulos_json">
                </div>
            </div>

            <div class="text-end mb-4">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Generar Boleta</button>
                <a href="boletas.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

    <script>
        <?php if (isset($_SESSION['mensaje'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['mensaje']['tipo']; ?>',
                title: '<?php echo $_SESSION['mensaje']['texto']; ?>',
                showConfirmButton: false,
                timer: 3000
            });
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
    </script>

    <script src="../assets/js/nueva_boleta.js"></script>
</body>
</html>