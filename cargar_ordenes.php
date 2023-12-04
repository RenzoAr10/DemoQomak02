<?php
// Configuración de la conexión a PostgreSQL
$host = 'localhost';  // Reemplaza con tu host
$dbname = 'KOMAK';   // Reemplaza con tu nombre de base de datos
$port = '5433';
$user = 'postgres';   // Reemplaza con tu nombre de usuario
$password = '1234';   // Reemplaza con tu contraseña

// Conexión a PostgreSQL
$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");


session_start(); // Inicia la sesión para acceder al id_usuario

// Obtener el id_usuario de la sesión (deberías validar y sanitizar esta variable)
$id_usuario = $_SESSION['id_usuario'];

// Consulta SQL para obtener órdenes de compra para un usuario específico
$sql = "SELECT id_orden_compra, fecha_oc, estado_oc FROM OrdenCompra WHERE id_usuario = :id_usuario";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

// Obtener los resultados
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los resultados como JSON
echo json_encode($ordenes);
?>