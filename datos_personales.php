<?php
// Iniciar la sesión para acceder a la variable $_SESSION['id_usuario']
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Redireccionar a la página de inicio de sesión si no está autenticado
    header("Location: login.html");
    exit();
}

// Obtener el id_usuario de la sesión
$id_usuario = $_SESSION['id_usuario'];

// Conectar a la base de datos y obtener los datos personales del usuario
$host = 'localhost'; // Reemplaza con tu host
$dbname = 'KOMAK'; // Reemplaza con tu nombre de base de datos
$port = '5433'; // Reemplaza con tu puerto si es diferente
$user = 'postgres'; // Reemplaza con tu nombre de usuario
$password = '1234'; // Reemplaza con tu contraseña

// Conectar a PostgreSQL
$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verificar la conexión
if (!$conexion) {
    die('Error de conexión: ' . pg_last_error());
}

// Consulta SQL para obtener los datos personales del usuario
$sql = "SELECT c.nombre, c.apellido_paterno, c.apellido_materno, c.ruc, c.dni, c.telefono, c.email, c.direccion, c.nombreempresa
        FROM Cliente c
        INNER JOIN Usuario u ON c.id_cliente = u.id_cliente
        WHERE u.id_usuario = $id_usuario";

$resultado = pg_query($conexion, $sql);

// Verificar el resultado de la consulta
if ($resultado) {
    $datos_personales = pg_fetch_assoc($resultado);
} else {
    die("Error en la consulta: " . pg_last_error());
}

// Cerrar la conexión
pg_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleDatosPersonales.css">
    <!-- Otros enlaces a archivos de estilo o scripts si es necesario -->
    <title>Datos Personales - Qomaq Service</title>
</head>
<body>
    <section class="interfaz">
        <nav class="sidebar">
            <ul>
                <li><a href="interfaz.html">Inicio</a></li>
                <li><a href="datos_personales.php">Datos personales</a></li>
                <li><a href="ordenescompra.php">Órdenes de compra</a></li>
                <li><a href="solicitar.html">Solicitar Orden de Compra</a></li>
                <li><a href="#facturas">Facturas</a></li>
            </ul>
        </nav>

        <main class="content">
            <h2 class="title">Datos Personales</h2>
            <img src="img/LogoKomaq.png" alt="Logo de Qomaq Service" class="logo">
            <div class="datos-container">
                <label for="nombre">Nombre:</label>
                <p><?php echo isset($datos_personales['nombre']) ? $datos_personales['nombre'] : ''; ?></p>

                <label for="apellido_paterno">Apellido Paterno:</label>
                <p><?php echo isset($datos_personales['apellido_paterno']) ? $datos_personales['apellido_paterno'] : ''; ?></p>

                <label for="apellido_materno">Apellido Materno:</label>
                <p><?php echo isset($datos_personales['apellido_materno']) ? $datos_personales['apellido_materno'] : ''; ?></p>

                <label for="ruc">RUC:</label>
                <p><?php echo isset($datos_personales['ruc']) ? $datos_personales['ruc'] : ''; ?></p>

                <label for="dni">DNI:</label>
                <p><?php echo isset($datos_personales['dni']) ? $datos_personales['dni'] : ''; ?></p>

                <label for="telefono">Teléfono:</label>
                <p><?php echo isset($datos_personales['telefono']) ? $datos_personales['telefono'] : ''; ?></p>

                <label for="email">Correo electrónico:</label>
                <p><?php echo isset($datos_personales['email']) ? $datos_personales['email'] : ''; ?></p>

                <label for="direccion">Dirección:</label>
                <p><?php echo isset($datos_personales['direccion']) ? $datos_personales['direccion'] : ''; ?></p>

                <label for="nombreempresa">Nombre de la Empresa:</label>
                <p><?php echo isset($datos_personales['nombreempresa']) ? $datos_personales['nombreempresa'] : ''; ?></p>
            </div>
        </main>
    </section>
</body>
</html>
