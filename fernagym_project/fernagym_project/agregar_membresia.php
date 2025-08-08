<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener usuario
$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;
if (!$usuario_id) {
    echo "ID de usuario inválido.";
    exit;
}

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

// Obtener planes
$planes = $pdo->query("SELECT * FROM planes WHERE estado = 'activo'")->fetchAll();

// Procesar formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_id = intval($_POST['plan_id'] ?? 0);
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $monto_pago = floatval($_POST['monto_pago'] ?? 0);
    $estado = $_POST['estado'] ?? 'activa';

    if ($plan_id && $fecha_inicio && $fecha_fin && $monto_pago) {
        $stmt = $pdo->prepare("INSERT INTO membresias (usuario_id, plan_id, fecha_inicio, fecha_fin, monto_pago, estado) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $plan_id, $fecha_inicio, $fecha_fin, $monto_pago, $estado]);
        $membresia_id = $pdo->lastInsertId();
        // Si la membresía es activa, registrar pago aprobado automáticamente
        if ($estado === 'activa') {
            $stmtPago = $pdo->prepare("INSERT INTO pagos (membresia_id, monto, estado, metodo_pago) VALUES (?, ?, 'aprobado', 'manual-admin')");
            $stmtPago->execute([$membresia_id, $monto_pago]);
        }
        $mensaje = 'Membresía agregada correctamente.';
    } else {
        $mensaje = 'Completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Membresía</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-yellow-400">Agregar Membresía a <?php echo htmlspecialchars($usuario['nombre']); ?></h2>
        <?php if ($mensaje): ?>
            <div class="mb-4 text-green-400 font-semibold"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <form method="post">
            <label class="block mb-2">Plan:
                <select name="plan_id" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
                    <option value="">Selecciona un plan</option>
                    <?php foreach ($planes as $plan): ?>
                        <option value="<?php echo $plan['id']; ?>"><?php echo htmlspecialchars($plan['nombre']); ?> (<?php echo number_format($plan['precio'],0,',','.'); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="block mb-2">Fecha inicio:
                <input type="date" name="fecha_inicio" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
            </label>
            <label class="block mb-2">Fecha fin:
                <input type="date" name="fecha_fin" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
            </label>
            <label class="block mb-2">Monto pago:
                <input type="number" name="monto_pago" step="0.01" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
            </label>
            <label class="block mb-4">Estado:
                <select name="estado" class="w-full px-2 py-1 rounded bg-gray-700 text-white">
                    <option value="activa">Activa</option>
                    <option value="pendiente">Pendiente</option>
                </select>
            </label>
            <button type="submit" class="btn-primary px-4 py-2 rounded">Agregar Membresía</button>
            <a href="admin.php" class="ml-4 text-yellow-400">Volver</a>
        </form>
    </div>
</body>
</html>
