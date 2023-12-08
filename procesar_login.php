<?php
// Configuración de la conexión a PostgreSQL
$host = 'localhost';  // Reemplaza con tu host
$dbname = 'KOMAK';   // Reemplaza con tu nombre de base de datos
$port = '5433';
$user = 'postgres';   // Reemplaza con tu nombre de usuario
$password = '1234';   // Reemplaza con tu contraseña

// Conexión a PostgreSQL
$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conexion) {
    die('Error de conexión: ' . pg_last_error());
}

// Obtener datos del formulario
$username = $_POST['username'];
$password_input = $_POST['password'];

// Consulta SQL para obtener la contraseña hasheada del usuario
$query = "SELECT id_usuario, contrasena_usuario FROM Usuario WHERE nombre_usuario = '$username'";
$result = pg_query($conexion, $query);

if (!$result) {
    die('Error en la consulta: ' . pg_last_error());
}

if (pg_num_rows($result) > 0) {
    // Usuario encontrado, verificar la contraseña
    $row = pg_fetch_assoc($result);
    $id_usuario = $row['id_usuario'];
    $contrasena_hash = $row['contrasena_usuario'];

    // Verificar la contraseña
    if (password_verify($password_input, $contrasena_hash)) {
        // Contraseña válida, iniciar sesión
        
        session_start();
        $_SESSION['id_usuario'] = $id_usuario;

        // Redirigir a interfaz.html
        header('Location: interfaz.html');
        exit();
    } else {
        // Contraseña incorrecta
        echo 'Contraseña incorrecta. <a href="login.html">Volver a intentar</a>';
    }
} else {
    // Usuario no encontrado
    echo 'Usuario no encontrado. <a href="login.html">Volver a intentar</a>';
}

// Cerrar la conexión a PostgreSQL
pg_close($conexion);
?>
