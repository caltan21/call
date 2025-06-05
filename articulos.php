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
    <title>Gestión de Artículos - Sistema de Ventas</title>
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
                        <a class="nav-link active" aria-current="page" href="articulos.php">Artículos</a>
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
        <h2 class="mb-4">Gestión de Artículos</h2>

        <div class="d-flex justify-content-between mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMantenimientoArticulo" onclick="nuevoArticulo()">
                <i class="bi bi-plus-circle"></i> Nuevo Artículo
            </button>
            <!-- BOTÓN PARA EXPORTAR A CSV -->
            <button id="btnExportarCsv" class="btn btn-info">Exportar a CSV</button>
        </div>


        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="articulos_data" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Fecha Creación</th>
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

    <div class="modal fade" id="modalMantenimientoArticulo" tabindex="-1" aria-labelledby="modalLabelArticulo" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelArticulo">Nuevo Artículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="form_articulo">
                    <div class="modal-body">
                        <input type="hidden" name="id_articulo" id="id_articulo"> 
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Artículo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="action" id="#" value="add" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
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
        var tabla; // Variable global para la instancia de DataTables

        $(document).ready(function() {
            // Inicializar DataTables
            tabla = $('#articulos_data').DataTable({
                "aProcessing": true, // Activar procesamiento
                "aServerSide": true, // Paginación y filtrado por servidor
                "ajax": {
                    url: '../controllers/ArticuloController.php?op=listar',
                    type: "POST", // Se recomienda POST para DataTables server-side
                    dataType: "json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "responsive": true, // Tabla responsiva
                "bInfo": true, // Muestra información de registros
                "iDisplayLength": 10, // Cantidad de registros por página
                "order": [[ 0, "desc" ]], // Ordenar por la primera columna (Nombre) de forma descendente por defecto
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

            // Manejar el envío del formulario del modal (Agregar/Editar)
            $('#form_articulo').on('submit', function(e) {
                e.preventDefault(); // Evitar el envío normal del formulario

                // Validaciones adicionales en el cliente si es necesario
                var nombre = $('#nombre').val();
                var precio = parseFloat($('#precio').val());
                var stock = parseInt($('#stock').val());

                if (nombre.trim() === '' || isNaN(precio) || precio < 0 || isNaN(stock) || stock < 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: 'Por favor, completa todos los campos obligatorios y asegúrate que Precio y Stock sean valores válidos.'
                    });
                    return; // Detener el envío
                }

                var formData = new FormData(this); // Obtener todos los datos del formulario

                $.ajax({
                    url: '../controllers/ArticuloController.php?op=guardar_o_actualizar',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(datos) {
                        // El controlador ya maneja la redirección y el mensaje de SweetAlert
                        // Simplemente recargamos la tabla y cerramos el modal
                        $('#modalMantenimientoArticulo').modal('hide'); // Cierra el modal
                        tabla.ajax.reload(); // Recarga los datos de la tabla

                        // Opcional: Si quieres un mensaje de éxito instantáneo aquí,
                        // en lugar de depender de la sesión y recarga de página:
                        // Swal.fire('Éxito', 'Operación realizada correctamente', 'success');
                    },
                    error: function(e) {
                        console.log(e.responseText);
                        Swal.fire('Error', 'Hubo un problema en la operación.', 'error');
                    }
                });
            });

            // --- INICIO DEL CÓDIGO PARA EL BOTÓN "EXPORTAR A CSV" ---
            $("#btnExportarCsv").on("click", function() {
                // Redirigir al controlador que genera el CSV
                // La ruta "../controllers/CsvController.php" es relativa desde views/articulos.php
                // "?op=exportar_articulos_csv" es el parámetro que le dice al controlador qué hacer.
                window.location.href = "../controllers/CsvController.php?op=exportar_articulos_csv";
            });
            // --- FIN DEL CÓDIGO PARA EL BOTÓN "EXPORTAR A CSV" ---
        });

        // Función para abrir el modal para un nuevo artículo
        function nuevoArticulo() {
            $('#modalLabelArticulo').text('Nuevo Artículo');
            $('#form_articulo')[0].reset(); // Limpiar el formulario
            $('#id_articulo').val(''); // Asegurar que el ID esté vacío
            // $('#modalMantenimientoArticulo').modal('show'); // El botón ya lo abre
        }

        // Función para editar un artículo
        function editar(id_articulo) {
            $('#modalLabelArticulo').text('Editar Artículo');
            $('#form_articulo')[0].reset(); // Limpiar el formulario
            $('#id_articulo').val(id_articulo); // Establecer el ID del artículo a editar

            // Realizar una petición AJAX para obtener los datos del artículo
            $.post("../controllers/ArticuloController.php?op=mostrar", { id_articulo: id_articulo }, function(data) {
                data = JSON.parse(data); // Parsear la respuesta JSON

                $('#nombre').val(data.nombre);
                $('#descripcion').val(data.descripcion);
                $('#precio').val(data.precio);
                $('#stock').val(data.stock);

                // Abrir el modal
                $('#modalMantenimientoArticulo').modal('show');
            });
        }

        // Función para eliminar un artículo
        function eliminar(id_articulo) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("../controllers/ArticuloController.php?op=eliminar", { id_articulo: id_articulo }, function(data) {
                        // El controlador ya maneja el mensaje de SweetAlert via sesión
                        tabla.ajax.reload(); // Recargar la tabla después de eliminar
                        Swal.fire(
                            '¡Eliminado!',
                            'El artículo ha sido eliminado.',
                            'success'
                        );
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        Swal.fire('Error', 'Hubo un problema al eliminar el artículo.', 'error');
                        console.log("Error: " + textStatus + " " + errorThrown);
                    });
                }
            });
        }
    </script>
</body>
</html>