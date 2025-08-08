<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$membresia_id = isset($_GET['membresia_id']) ? intval($_GET['membresia_id']) : 0;
if (!$membresia_id) {
    echo "ID de membresía inválido.";
    exit;
}

// Obtener membresía y usuario
$stmt = $pdo->prepare("SELECT m.*, u.nombre as usuario_nombre, p.nombre as plan_nombre, p.precio FROM membresias m JOIN usuarios u ON m.usuario_id = u.id JOIN planes p ON m.plan_id = p.id WHERE m.id = ?");
$stmt->execute([$membresia_id]);
$membresia = $stmt->fetch();
if (!$membresia) {
    echo "Membresía no encontrada.";
    exit;
}

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monto = floatval($_POST['monto'] ?? $membresia['monto_pago']);
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    $estado = $_POST['estado'] ?? 'aprobado';
    if ($monto && $metodo_pago) {
        $stmt = $pdo->prepare("INSERT INTO pagos (membresia_id, monto, metodo_pago, estado) VALUES (?, ?, ?, ?)");
        $stmt->execute([$membresia_id, $monto, $metodo_pago, $estado]);
        $mensaje = 'Pago registrado correctamente.';
    } else {
        $mensaje = 'Completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-yellow-400">Registrar Pago para <?php echo htmlspecialchars($membresia['usuario_nombre']); ?></h2>
        <div class="mb-2 text-gray-300">Plan: <b><?php echo htmlspecialchars($membresia['plan_nombre']); ?></b></div>
        <div class="mb-2 text-gray-300">Monto sugerido: $<?php echo number_format($membresia['monto_pago'],0,',','.'); ?></div>
        <?php if ($mensaje): ?>
            <div class="mb-4 text-green-400 font-semibold"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <form method="post">
            <label class="block mb-2">Monto:
                <input type="number" name="monto" step="0.01" value="<?php echo $membresia['monto_pago']; ?>" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
            </label>
            <label class="block mb-2">Método de pago:
                <input type="text" name="metodo_pago" placeholder="Ej: Efectivo" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
            </label>
            <label class="block mb-4">Estado:
                <select name="estado" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
                    <option value="aprobado">Aprobado</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </label>
            <button type="submit" class="btn-primary px-4 py-2 rounded">Registrar Pago</button>
            <a href="admin.php" class="ml-4 text-yellow-400">Volver</a>
        </form>
    </div>
</body>
</html>
