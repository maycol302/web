<?php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ingresos_fernagym.csv');
$output = fopen('php://output', 'w');

// Encabezados
fputcsv($output, ['ID Pago', 'Usuario', 'Plan', 'Monto', 'Fecha Pago', 'Método', 'Estado']);

$stmt = $pdo->query("SELECT p.id, u.nombre as usuario, pl.nombre as plan, p.monto, p.fecha_pago, p.metodo_pago, p.estado FROM pagos p JOIN membresias m ON p.membresia_id = m.id JOIN usuarios u ON m.usuario_id = u.id JOIN planes pl ON m.plan_id = pl.id WHERE p.estado = 'aprobado' ORDER BY p.fecha_pago DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
