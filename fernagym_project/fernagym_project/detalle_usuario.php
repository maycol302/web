<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;
if (!$usuario_id) {
    echo "ID de usuario inválido.";
    exit;
}

// Obtener usuario y membresía activa/pendiente
$stmt = $pdo->prepare("SELECT u.*, m.fecha_inicio, m.fecha_fin, m.estado as estado_membresia, p.nombre as plan_nombre FROM usuarios u LEFT JOIN membresias m ON u.id = m.usuario_id AND m.estado IN ('activa','pendiente') LEFT JOIN planes p ON m.plan_id = p.id WHERE u.id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

// Calcular días restantes
$dias_restantes = null;
if ($usuario['fecha_fin']) {
    $fecha_fin = new DateTime($usuario['fecha_fin']);
    $hoy = new DateTime();
    $intervalo = $hoy->diff($fecha_fin);
    $dias_restantes = (int)$intervalo->format('%r%a');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-yellow-400">Detalle de Usuario</h2>
        <div class="mb-2"><b>Nombre:</b> <?php echo htmlspecialchars($usuario['nombre']); ?></div>
        <div class="mb-2"><b>Email:</b> <?php echo htmlspecialchars($usuario['email']); ?></div>
        <div class="mb-2"><b>RUT:</b> <?php echo htmlspecialchars($usuario['rut']); ?></div>
        <div class="mb-2"><b>Plan:</b> <?php echo $usuario['plan_nombre'] ? htmlspecialchars($usuario['plan_nombre']) : 'Sin membresía'; ?></div>
        <div class="mb-2"><b>Estado membresía:</b> <?php echo $usuario['estado_membresia'] ? htmlspecialchars($usuario['estado_membresia']) : 'Sin membresía'; ?></div>
        <div class="mb-2"><b>Fecha inicio:</b> <?php echo $usuario['fecha_inicio'] ? htmlspecialchars($usuario['fecha_inicio']) : '-'; ?></div>
        <div class="mb-2"><b>Fecha fin:</b> <?php echo $usuario['fecha_fin'] ? htmlspecialchars($usuario['fecha_fin']) : '-'; ?></div>
        <div class="mb-4"><b>Días restantes:</b> <?php echo ($dias_restantes !== null) ? $dias_restantes : '-'; ?></div>
        <a href="admin.php" class="btn-primary px-4 py-2 rounded">Volver</a>
    </div>
</body>
</html>
