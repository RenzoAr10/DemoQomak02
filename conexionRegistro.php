<?php
// Configuración de la conexión a PostgreSQL
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


// Evitar la inyección SQL
$nombre = pg_escape_string($_REQUEST['nombre']);
$apellido_paterno = pg_escape_string($_REQUEST['apellido_paterno']);
$apellido_materno = pg_escape_string($_REQUEST['apellido_materno']);
$ruc = pg_escape_string($_REQUEST['ruc']);
$dni = pg_escape_string($_REQUEST['dni']);
$telefono = pg_escape_string($_REQUEST['telefono']);
$email = pg_escape_string($_REQUEST['email']);
$direccion = pg_escape_string($_REQUEST['direccion']);
$nombre_empresa = pg_escape_string($_REQUEST['nombre_empresa']);

// Consulta SQL para insertar datos en la tabla Cliente
$query_cliente = "INSERT INTO Cliente (nombre, apellido_paterno, apellido_materno, RUC, dni, telefono, email, direccion, NombreEmpresa)
        VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$ruc', '$dni', '$telefono', '$email', '$direccion', '$nombre_empresa') RETURNING id_cliente";

// Ejecutar la consulta y obtener el ID del cliente recién insertado
$result_cliente = pg_query($conexion, $query_cliente);
$id_cliente = pg_fetch_result($result_cliente, 0, 0);

// Verificar el resultado de la consulta Cliente
if ($result_cliente) {
    echo "Registro exitoso en la tabla Cliente";
} else {
    echo "Error en el registro en la tabla Cliente: " . pg_last_error();
}

// Consulta SQL para insertar datos en la tabla Usuario
$nombre_usuario = pg_escape_string($_REQUEST['usuario']);
$contrasena_usuario = password_hash($_REQUEST['contrasena'], PASSWORD_DEFAULT); // Hashear la contraseña

$query_usuario = "INSERT INTO Usuario (nombre_usuario, contrasena_usuario, id_cliente)
        VALUES ('$nombre_usuario', '$contrasena_usuario', $id_cliente)";
// Iniciar la sesión (si aún no está iniciada)

// Ejecutar la consulta Usuario
$result_usuario = pg_query($conexion, $query_usuario);

// Verificar el resultado de la consulta Usuario
if ($result_usuario) {
    echo "Registro exitoso en la tabla Usuario";
} else {
    echo "Error en el registro en la tabla Usuario: " . pg_last_error();
}


// Cerrar la conexión
pg_close($conexion);
?>
