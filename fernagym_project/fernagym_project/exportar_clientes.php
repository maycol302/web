<?php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=clientes_fernagym.csv');
$output = fopen('php://output', 'w');

// Encabezados
fputcsv($output, ['ID', 'Nombre', 'Email', 'RUT', 'Fecha Registro', 'Plan', 'Fecha Inicio', 'Fecha Fin', 'Estado Membresía']);

$stmt = $pdo->query("SELECT u.id, u.nombre, u.email, u.rut, u.fecha_registro, p.nombre as plan, m.fecha_inicio, m.fecha_fin, m.estado as estado_membresia FROM usuarios u LEFT JOIN membresias m ON u.id = m.usuario_id AND m.estado IN ('activa','pendiente') LEFT JOIN planes p ON m.plan_id = p.id ORDER BY u.fecha_registro DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
