<?php
// conexion.php - usar PDO y parámetros configurables
$db_host = 'localhost';
$db_name = 'fernagym';
$db_user = 'root';
$db_pass = '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, $options);
} catch (Exception $e) {
    // En producción no mostrar detalles, solo registrar
    die('Error de conexión a la base de datos.');
}
?>
