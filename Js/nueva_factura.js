var lista_articulos_carrito = []; // Array para almacenar los artículos en el carrito

$(document).ready(function() {
    // Inicializar Select2 para el selector de artículos
    $('#id_articulo').select2({
        placeholder: 'Seleccione un artículo',
        allowClear: true,
        // La línea 'dropdownParent' ha sido eliminada o comentada.
        // Era la causa del error porque el modal al que hacía referencia no existe en esta vista.
        ajax: {
            url: '../controllers/FacturaController.php?op=listar_articulos_para_venta', // URL que devuelve los artículos
            dataType: 'json',
            delay: 250, // Retraso en milisegundos para esperar a que el usuario termine de escribir
            data: function(params) {
                return {
                    q: params.term // Parámetro de búsqueda (lo que el usuario escribe en el select)
                };
            },
            processResults: function(data) {
                // 'data' es el array de artículos que viene del controlador PHP
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.nombre + ' (Stock: ' + item.stock + ')', // Texto que se muestra en la lista
                            nombre: item.nombre,
                            descripcion: item.descripcion,
                            precio: item.precio,
                            stock: item.stock
                        };
                    })
                };
            },
            cache: true // Permite que Select2 almacene en caché las búsquedas
        },
        minimumInputLength: 0 // Permite cargar todos los artículos al abrir, o al menos no requiere escribir para buscar
    });

    // Evento al seleccionar un artículo en Select2
    $('#id_articulo').on('select2:select', function(e) {
        var data = e.params.data;
        if (data) {
            $('#precio_unitario_display').val(parseFloat(data.precio).toFixed(2));
            $('#precio_unitario_hidden').val(data.precio); // Guarda el precio real para cálculos
            $('#stock_display').val(data.stock);
            $('#cantidad').val(1); // Resetear cantidad a 1 por defecto
            $('#cantidad').attr('max', data.stock); // Establecer el máximo de cantidad basado en el stock disponible
        } else {
            // Limpiar campos si no hay selección (ej. si el usuario borra la selección)
            $('#precio_unitario_display').val('');
            $('#precio_unitario_hidden').val('');
            $('#stock_display').val('');
            $('#cantidad').val(1);
            $('#cantidad').removeAttr('max'); // Remover el atributo max
        }
    });

    // Evento al borrar la selección en Select2 (cuando se hace clic en la "x" en el campo)
    $('#id_articulo').on('select2:unselect', function(e) {
        $('#precio_unitario_display').val('');
        $('#precio_unitario_hidden').val('');
        $('#stock_display').val('');
        $('#cantidad').val(1);
        $('#cantidad').removeAttr('max');
    });

    // Función para añadir un artículo al carrito
    $('#btn_agregar_articulo').on('click', function() {
        var id_articulo = $('#id_articulo').val();
        var nombre_articulo_full_text = $('#id_articulo option:selected').text();
        var nombre_articulo = nombre_articulo_full_text.split('(')[0].trim(); // Quitar el stock del nombre
        var precio_unitario = parseFloat($('#precio_unitario_hidden').val());
        var cantidad = parseInt($('#cantidad').val());
        var stock_disponible = parseInt($('#stock_display').val());

        // Validaciones básicas antes de añadir al carrito
        if (!id_articulo) {
            Swal.fire('Atención', 'Por favor, seleccione un artículo.', 'warning');
            return;
        }
        if (isNaN(precio_unitario) || precio_unitario <= 0) {
            Swal.fire('Atención', 'El precio unitario no es válido o es cero.', 'warning');
            return;
        }
        if (isNaN(cantidad) || cantidad <= 0) {
            Swal.fire('Atención', 'La cantidad debe ser un número positivo.', 'warning');
            return;
        }
        if (cantidad > stock_disponible) {
            Swal.fire('Atención', 'La cantidad solicitada (' + cantidad + ') supera el stock disponible (' + stock_disponible + ').', 'warning');
            return;
        }

        // Verificar si el artículo ya está en el carrito para actualizar su cantidad
        var articulo_existente_index = lista_articulos_carrito.findIndex(item => item.id_articulo == id_articulo);

        if (articulo_existente_index !== -1) {
            // Si ya existe, actualizar cantidad y recalcular subtotal
            let articulo_existente = lista_articulos_carrito[articulo_existente_index];
            let nueva_cantidad = articulo_existente.cantidad + cantidad;
            if (nueva_cantidad > stock_disponible) {
                Swal.fire('Atención', 'No puedes añadir más de la cantidad disponible en stock para este artículo.', 'warning');
                return;
            }
            articulo_existente.cantidad = nueva_cantidad;
            articulo_existente.subtotal = articulo_existente.cantidad * articulo_existente.precio_unitario;
        } else {
            // Si no existe, añadirlo como nuevo
            lista_articulos_carrito.push({
                id_articulo: id_articulo,
                nombre_articulo: nombre_articulo,
                cantidad: cantidad,
                precio_unitario: precio_unitario,
                subtotal: cantidad * precio_unitario
            });
        }

        renderizarCarrito(); // Actualizar la tabla del carrito
        // Restablecer el select y campos después de añadir
        $('#id_articulo').val(null).trigger('change'); // Limpia la selección en Select2
        $('#precio_unitario_display').val('');
        $('#precio_unitario_hidden').val('');
        $('#stock_display').val('');
        $('#cantidad').val(1);
        $('#cantidad').removeAttr('max');
    });

    // Función para renderizar la tabla del carrito (actualiza el HTML y el total)
    function renderizarCarrito() {
        var html = '';
        var total_factura = 0;

        if (lista_articulos_carrito.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted">Aún no hay artículos en el carrito.</td></tr>';
        } else {
            $.each(lista_articulos_carrito, function(index, item) {
                html += '<tr>';
                html += '<td>' + item.nombre_articulo + '</td>';
                html += '<td>' + item.cantidad + '</td>';
                html += '<td>S/ ' + parseFloat(item.precio_unitario).toFixed(2) + '</td>';
                html += '<td>S/ ' + parseFloat(item.subtotal).toFixed(2) + '</td>';
                html += '<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarArticuloCarrito(' + index + ')">Eliminar</button></td>';
                html += '</tr>';
                total_factura += item.subtotal;
            });
        }
        $('#carrito_articulos_body').html(html);
        $('#total_factura_display').text(total_factura.toFixed(2));
        // IMPORTANTE: Convertir el array del carrito a JSON y asignarlo al campo oculto para enviarlo al PHP
        $('#articulos_json').val(JSON.stringify(lista_articulos_carrito));
    }

    // Función global para eliminar un artículo del carrito (llamada desde el HTML)
    window.eliminarArticuloCarrito = function(index) {
        lista_articulos_carrito.splice(index, 1); // Eliminar el artículo del array por su índice
        renderizarCarrito(); // Volver a renderizar la tabla y recalcular el total
    };

    // Manejar el envío del formulario de la factura
    $('#form_nueva_factura').on('submit', function(e) {
        // Antes de enviar, asegurarse de que haya artículos en el carrito
        if (lista_articulos_carrito.length === 0) {
            e.preventDefault(); // Evitar el envío si el carrito está vacío
            Swal.fire('Atención', 'No puedes generar una factura sin artículos en el carrito.', 'warning');
            return;
        }

        // Si todo está bien, el formulario se enviará normalmente,
        // y el campo oculto 'articulos_json' contendrá los datos del carrito en formato JSON.
    });
});