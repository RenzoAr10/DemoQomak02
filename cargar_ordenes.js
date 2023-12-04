$(document).ready(function() {
    // Configura el calendario
    $('#tablaOrdenes').DataTable({
        // Configuraciones de la tabla (si las necesitas)
    });

    // Llamada AJAX para obtener órdenes de compra
    $.ajax({
        url: 'cargar_ordenes.php',
        type: 'GET',
        dataType: 'json',
        success: function(ordenes) {
            // Llena dinámicamente la tabla con las órdenes recibidas
            const tbody = $('#tablaOrdenes tbody');
            tbody.empty(); // Limpia el contenido existente

            ordenes.forEach(orden => {
                const row = `<tr>
                    <td>${orden.id_orden_compra}</td>
                    <td>${orden.fecha_oc}</td>
                    <td>${orden.estado_oc}</td>
                </tr>`;
                tbody.append(row);
            });
        },
        error: function(error) {
            console.error('Error al obtener órdenes:', error);
        }
    });
});
