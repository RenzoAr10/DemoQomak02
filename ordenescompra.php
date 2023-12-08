<?php
session_start();

$host = 'localhost';  // Reemplaza con tu host
$dbname = 'KOMAK';  // Reemplaza con tu nombre de base de datos
$port = "5433";
$user = 'postgres';  // Reemplaza con tu nombre de usuario
$password = '1234';  // Reemplaza con tu contraseña

// Conexión a PostgreSQL
$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verificar la conexión
if (!$conexion) {
    die('Error de conexión: ' . pg_last_error());
}

// Sustituye '1' con el ID de usuario correspondiente (puedes obtenerlo de tu sistema de autenticación)
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener las órdenes de compra del usuario
$sql = "SELECT id_orden_compra, fecha_oc, estado_oc FROM OrdenCompra WHERE id_usuario = $id_usuario";

$resultado = pg_query($conexion, $sql);

// Cierra la conexión después de la consulta
pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Encabezado con los enlaces necesarios -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleOrdenescompra.css">
    <title>Órdenes de Compra - Qomaq Service</title>
</head>
<body>
    <section class="interfaz">
        <!-- Barra lateral con subpáginas -->
        <nav class="sidebar">
            <ul>
                <li><a href="interfaz.html">Inicio</a></li>
                <li><a href="datos_personales.php">Datos personales</a></li>
                <li><a href="ordenescompra.php">Órdenes de compra</a></li>
                <li><a href="solicitar.html">Solicitar Orden de Compra</a></li>
                <li><a href="#facturas">Facturas</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="content">
            <!-- Contenido de la subpágina seleccionada -->
            <h2 class="title">Órdenes de Compra</h2>
            <img src="img/LogoKomaq.png" alt="Logo de Qomaq Service" class="logo">
            <!-- Tabla para mostrar las órdenes de compra -->
            <table id="tablaOrdenes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha de Orden</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Llenar la tabla con los resultados de la consulta
                    while ($fila = pg_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $fila['id_orden_compra'] . "</td>";
                        echo "<td>" . $fila['fecha_oc'] . "</td>";
                        echo "<td>" . $fila['estado_oc'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <!-- Agrega cualquier otro script o enlace necesario aquí -->
        </main>
    </section>
</body>
</html>
