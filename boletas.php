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
    <title>Gestión de Boletas - Sistema de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #ADD8E6; /* Celeste claro */
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
                        <a class="nav-link active" aria-current="page" href="boletas.php">Boletas</a>
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
        <h2 class="mb-4">Listado de Boletas</h2>

        <a href="nueva_boleta.php" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Nueva Boleta
        </a>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="boletas_data" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>ID Boleta</th>
                                <th>Usuario</th>
                                <th>Fecha Emisión</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalleBoleta" tabindex="-1" aria-labelledby="modalLabelDetalleBoleta" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelDetalleBoleta">Detalles de Boleta #<span id="boleta_id_modal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Información General:</h6>
                    <p><strong>Emisor:</strong> <span id="boleta_usuario_modal"></span></p>
                    <p><strong>Fecha:</strong> <span id="boleta_fecha_modal"></span></p>
                    <p><strong>Estado:</strong> <span id="boleta_estado_modal"></span></p>
                    <hr>
                    <h6>Artículos Vendidos:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Artículo</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detalles_boleta_body">
                                </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total de Boleta:</th>
                                    <th><span id="boleta_total_modal"></span></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
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

    <script type="text/javascript">
        var tablaBoletas;

        $(document).ready(function() {
            tablaBoletas = $('#boletas_data').DataTable({
                "aProcessing": true,
                "aServerSide": true,
                "ajax": {
                    url: '../controllers/BoletaController.php?op=listar_boletas',
                    type: "POST",
                    dataType: "json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "responsive": true,
                "bInfo": true,
                "iDisplayLength": 10,
                "order": [[ 2, "desc" ]], // Ordenar por fecha de emisión descendente
                "language": {
                    "sProcessing":      "Procesando...",
                    "sLengthMenu":      "Mostrar _MENU_ registros",
                    "sZeroRecords":     "No se encontraron resultados",
                    "sEmptyTable":      "Ningún dato disponible en esta tabla",
                    "sInfo":            "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":       "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":    "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":     "",
                    "sSearch":          "Buscar:",
                    "sUrl":             "",
                    "sInfoThousands":   ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":       "Primero",
                        "sLast":        "Último",
                        "sNext":        "Siguiente",
                        "sPrevious":    "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });

        function verDetalleBoleta(id_boleta) {
            $.post("../controllers/BoletaController.php?op=mostrar_detalle_boleta", { id_boleta: id_boleta }, function(data) {
                var response = JSON.parse(data);

                // Llenar datos de la cabecera
                $('#boleta_id_modal').text(response.cabecera.id);
                $('#boleta_usuario_modal').text(response.cabecera.nombre_usuario);
                $('#boleta_fecha_modal').text(new Date(response.cabecera.fecha_emision).toLocaleString());
                $('#boleta_estado_modal').text(response.cabecera.estado);
                $('#boleta_total_modal').text('S/ ' + parseFloat(response.cabecera.total).toFixed(2));

                // Llenar tabla de detalles
                var detallesHtml = '';
                $.each(response.detalles, function(index, item) {
                    detallesHtml += '<tr>';
                    detallesHtml += '<td>' + item.nombre_articulo + '</td>';
                    detallesHtml += '<td>' + item.descripcion_articulo + '</td>';
                    detallesHtml += '<td>' + item.cantidad + '</td>';
                    detallesHtml += '<td>S/ ' + parseFloat(item.precio_unitario).toFixed(2) + '</td>';
                    detallesHtml += '<td>S/ ' + parseFloat(item.subtotal).toFixed(2) + '</td>';
                    detallesHtml += '</tr>';
                });
                $('#detalles_boleta_body').html(detallesHtml);

                // Mostrar el modal
                $('#modalDetalleBoleta').modal('show');
            });
        }
    </script>
</body>
</html>