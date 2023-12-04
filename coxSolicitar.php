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



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener datos del formulario
    $maquinas = $_POST['maquinas'];

    // Obtener el ID del usuario almacenado en la sesión



    $problemas = $_POST['problemas'];

    // Iniciar una transacción
    pg_query($conexion, "BEGIN");

    // Realizar la inserción en la tabla OrdenCompra
    $queryOrdenCompra = "INSERT INTO OrdenCompra (estado_oc, fecha_oc, id_usuario) VALUES ('Pendiente', NOW(),10) RETURNING id_orden_compra";
    $resultOrdenCompra = pg_query($conexion, $queryOrdenCompra);

    if (!$resultOrdenCompra) {
        pg_query($conexion, "ROLLBACK");
        die('Error al insertar en la tabla OrdenCompra: ' . pg_last_error());
    }

    // Obtener el ID de la orden de compra recién insertada
    $row = pg_fetch_assoc($resultOrdenCompra);
    $idOrdenCompra = $row['id_orden_compra'];

    // Iterar sobre las máquinas y problemas seleccionados para realizar inserciones
    for ($i = 0; $i < count($maquinas); $i++) {
        $idMaquina = $maquinas[$i];
        $idProblema = $problemas[$i];

        // Insertar en la tabla MaquinaOrdenCompra
        $queryMaquinaOrdenCompra = "INSERT INTO MaquinaOrdenCompra (id_maquina, id_orden_compra) VALUES ($idMaquina, $idOrdenCompra)";
        $resultMaquinaOrdenCompra = pg_query($conexion, $queryMaquinaOrdenCompra);

        if (!$resultMaquinaOrdenCompra) {
            pg_query($conexion, "ROLLBACK");
            die('Error al insertar en la tabla MaquinaOrdenCompra: ' . pg_last_error());
        }

        // Insertar en la tabla ProblemaMaquina
        $queryProblemaMaquina = "INSERT INTO ProblemaMaquina (id_problema, id_maquina) VALUES ($idProblema, $idMaquina)";
        $resultProblemaMaquina = pg_query($conexion, $queryProblemaMaquina);

        if (!$resultProblemaMaquina) {
            pg_query($conexion, "ROLLBACK");
            die('Error al insertar en la tabla ProblemaMaquina: ' . pg_last_error());
        }
    }

    // Confirmar la transacción
    pg_query($conexion, "COMMIT");

    echo 'Orden de compra generada exitosamente.';
} else {
    // Redirigir o mostrar un mensaje de error si se intenta acceder directamente a este script
    echo 'Acceso no autorizado.';
}

// Cerrar la conexión a PostgreSQL
pg_close($conexion);
?>
