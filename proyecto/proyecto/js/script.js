// Calcular totales
$total = 0;
foreach ($productos_carrito as $producto) {
    $total += $producto['precio'];
}
$impuestos = $total * 0.1; // 10% de impuestos
$total_final = $total + $impuestos;

// Asegúrate de que estas variables son números antes de llegar aquí

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('generarFactura').addEventListener('click', function() {
            // Obtener datos del cliente
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const direccion = document.getElementById('direccion').value;

            // Generar contenido de la factura
            let contenidoFactura = `Factura de Compra\n`;
            contenidoFactura += `Nombre: ${nombre}\n`;
            contenidoFactura += `Email: ${email}\n`;
            contenidoFactura += `Dirección: ${direccion}\n\n`;
            contenidoFactura += `Productos Comprados:\n`;

            // Obtener productos del carrito
            const productos = <?php echo json_encode($productos_carrito); ?>; // Esto se ejecuta en el servidor
            productos.forEach(producto => {
                contenidoFactura += `${producto.nombre} - ${formatoMoneda(producto.precio)}\n`;
            });

            // Calcular y añadir totales a la factura
            const subtotal = <?php echo $total; ?>; // Imprimir directamente como número
            const impuestos = <?php echo $impuestos; ?>; // Imprimir directamente como número
            const total_final = <?php echo $total_final; ?>; // Imprimir directamente como número

            contenidoFactura += `\nSubtotal: ${formatoMoneda(subtotal)}\n`;
            contenidoFactura += `Impuestos (10%): ${formatoMoneda(impuestos)}\n`;
            contenidoFactura += `Total: ${formatoMoneda(total_final)}\n`;

            // Mostrar factura en el DOM
            document.getElementById('facturaContent').innerText = contenidoFactura;
            document.getElementById('factura').style.display = 'block';

            // Generar PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text(contenidoFactura.split('\n'), 10, 10); // Cambiado para manejar líneas
            doc.save('factura.pdf');
        });
    });

    // Función para formatear moneda
    function formatoMoneda(numero) {
        return 'R$ ' + numero.toFixed(2).replace('.', ',');
    }
</script>
