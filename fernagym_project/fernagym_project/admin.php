<?php
session_start();
require_once 'conexion.php';

// Verificar si es admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}


// Obtener estadísticas
$stmt = $pdo->query("SELECT COUNT(*) as total_usuarios FROM usuarios WHERE estado = 'activo'");
$total_usuarios = $stmt->fetch()['total_usuarios'];

$stmt = $pdo->query("SELECT COUNT(*) as total_membresias FROM membresias WHERE estado = 'activa'");
$total_membresias = $stmt->fetch()['total_membresias'];

$stmt = $pdo->query("SELECT COUNT(*) as total_pagos FROM pagos WHERE estado = 'pendiente'");
$total_pagos_pendientes = $stmt->fetch()['total_pagos'];

// Ingresos mensuales y anuales (solo pagos aprobados)
$stmt = $pdo->query("SELECT SUM(monto) as total_mes FROM pagos WHERE estado = 'aprobado' AND MONTH(fecha_pago) = MONTH(CURDATE()) AND YEAR(fecha_pago) = YEAR(CURDATE())");
$ingresos_mes = $stmt->fetch()['total_mes'] ?? 0;
$stmt = $pdo->query("SELECT SUM(monto) as total_anio FROM pagos WHERE estado = 'aprobado' AND YEAR(fecha_pago) = YEAR(CURDATE())");
$ingresos_anio = $stmt->fetch()['total_anio'] ?? 0;

// Filtros para usuarios
$filtros = [
    'nombre' => trim($_GET['nombre'] ?? ''),
    'email' => trim($_GET['email'] ?? ''),
    'rut' => trim($_GET['rut'] ?? ''),
    'estado_membresia' => trim($_GET['estado_membresia'] ?? ''),
    'plan_nombre' => trim($_GET['plan_nombre'] ?? ''),
];

$where = [];
$params = [];
if ($filtros['nombre'] !== '') {
    $where[] = 'u.nombre LIKE ?';
    $params[] = '%' . $filtros['nombre'] . '%';
}
if ($filtros['email'] !== '') {
    $where[] = 'u.email LIKE ?';
    $params[] = '%' . $filtros['email'] . '%';
}
if ($filtros['rut'] !== '') {
    $where[] = 'u.rut LIKE ?';
    $params[] = '%' . $filtros['rut'] . '%';
}
if ($filtros['estado_membresia'] !== '') {
    $where[] = 'm.estado = ?';
    $params[] = $filtros['estado_membresia'];
}
if ($filtros['plan_nombre'] !== '') {
    $where[] = 'p.nombre LIKE ?';
    $params[] = '%' . $filtros['plan_nombre'] . '%';
}

$sql = "SELECT u.id, u.nombre, u.email, u.rut, u.fecha_registro,
           m.id as membresia_id, m.fecha_inicio, m.fecha_fin, m.estado as estado_membresia,
           p.nombre as plan_nombre, p.precio
    FROM usuarios u
    LEFT JOIN membresias m ON u.id = m.usuario_id AND m.estado IN ('activa', 'pendiente')
    LEFT JOIN planes p ON m.plan_id = p.id";
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY u.fecha_registro DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();

// Obtener pagos pendientes
$stmt = $pdo->query("
    SELECT p.id, p.monto, p.fecha_pago, p.comprobante, p.estado,
           u.nombre as usuario_nombre, u.email, pl.nombre as plan_nombre
    FROM pagos p
    JOIN membresias m ON p.membresia_id = m.id
    JOIN usuarios u ON m.usuario_id = u.id
    JOIN planes pl ON m.plan_id = pl.id
    WHERE p.estado = 'pendiente'
    ORDER BY p.fecha_pago DESC
");
$pagos_pendientes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Fernagym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #000;
            color: #fff;
        }
        .admin-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 215, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        .btn-primary {
            background-color: #FFD700;
            color: #000;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.7);
        }
        .btn-danger {
            background-color: #ef4444;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-danger:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.7);
        }
        .btn-success {
            background-color: #22c55e;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-success:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.7);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-black bg-opacity-90 border-b border-yellow-400 py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="assets/img/logo.jpg" alt="Fernagym" class="w-32">
                <span class="ml-4 text-yellow-400 font-bold">Panel de Administración</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-300">Administrador</span>
                <a href="dashboard.php" class="text-yellow-400 hover:text-yellow-300">Dashboard</a>
                <a href="logout.php" class="text-yellow-400 hover:text-yellow-300">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-yellow-400 mb-8">Panel de Administración</h1>
        <div class="mb-8 flex flex-wrap gap-4">
            <a href="exportar_clientes.php" class="btn-primary px-4 py-2 rounded">Exportar Clientes CSV</a>
            <a href="exportar_ingresos.php" class="btn-success px-4 py-2 rounded">Exportar Ingresos CSV</a>
        </div>
        
        <!-- Estadísticas e Ingresos -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="admin-card rounded-lg p-6 text-center">
                <h3 class="text-2xl font-bold text-yellow-400 mb-2"><?php echo $total_usuarios; ?></h3>
                <p class="text-gray-300">Usuarios Activos</p>
            </div>
            <div class="admin-card rounded-lg p-6 text-center">
                <h3 class="text-2xl font-bold text-yellow-400 mb-2"><?php echo $total_membresias; ?></h3>
                <p class="text-gray-300">Membresías Activas</p>
            </div>
            <div class="admin-card rounded-lg p-6 text-center">
                <h3 class="text-2xl font-bold text-yellow-400 mb-2"><?php echo $total_pagos_pendientes; ?></h3>
                <p class="text-gray-300">Pagos Pendientes</p>
            </div>
            <div class="admin-card rounded-lg p-6 text-center">
                <h3 class="text-2xl font-bold text-green-400 mb-2">$<?php echo number_format($ingresos_mes, 0, ',', '.'); ?></h3>
                <p class="text-gray-300">Ingresos del Mes</p>
            </div>
            <div class="admin-card rounded-lg p-6 text-center">
                <h3 class="text-2xl font-bold text-green-400 mb-2">$<?php echo number_format($ingresos_anio, 0, ',', '.'); ?></h3>
                <p class="text-gray-300">Ingresos del Año</p>
            </div>
            <div class="flex justify-end mt-4 w-full">
                <a href="detalle_ingresos.php" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded shadow transition-all duration-200">
                    Ver/Descargar Ingresos Detallados
                </a>
            </div>
        </div>

        <!-- Pagos Pendientes -->
        <div class="admin-card rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-yellow-400 mb-4">Pagos Pendientes</h2>
            <?php if (count($pagos_pendientes) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-600">
                                <th class="py-2">Usuario</th>
                                <th class="py-2">Plan</th>
                                <th class="py-2">Monto</th>
                                <th class="py-2">Fecha</th>
                                <th class="py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagos_pendientes as $pago): ?>
                                <tr class="border-b border-gray-700">
                                    <td class="py-2"><?php echo htmlspecialchars($pago['usuario_nombre']); ?></td>
                                    <td class="py-2"><?php echo htmlspecialchars($pago['plan_nombre']); ?></td>
                                    <td class="py-2">$<?php echo number_format($pago['monto'], 0, ',', '.'); ?></td>
                                    <td class="py-2"><?php echo date('d/m/Y', strtotime($pago['fecha_pago'])); ?></td>
                                    <td class="py-2">
                                        <a href="uploads/<?php echo $pago['comprobante']; ?>" target="_blank" class="text-yellow-400 hover:text-yellow-300 mr-2">Ver Comprobante</a>
                                        <form method="POST" action="aprobar-pago.php" class="inline">
                                            <input type="hidden" name="pago_id" value="<?php echo $pago['id']; ?>">
                                            <button type="submit" class="btn-success px-2 py-1 rounded text-sm">Aprobar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-400">No hay pagos pendientes.</p>
            <?php endif; ?>
        </div>

        <!-- Lista de Usuarios con Filtros -->
        <div class="admin-card rounded-lg p-6">
            <h2 class="text-2xl font-bold text-yellow-400 mb-4">Usuarios Registrados</h2>
            <!-- Formulario de filtros -->
            <form method="get" class="mb-4 grid grid-cols-1 md:grid-cols-6 gap-2">
                <input type="text" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($filtros['nombre']); ?>" class="form-input px-2 py-1 rounded bg-gray-700 text-white">
                <input type="text" name="email" placeholder="Email" value="<?php echo htmlspecialchars($filtros['email']); ?>" class="form-input px-2 py-1 rounded bg-gray-700 text-white">
                <input type="text" name="rut" placeholder="RUT" value="<?php echo htmlspecialchars($filtros['rut']); ?>" class="form-input px-2 py-1 rounded bg-gray-700 text-white">
                <select name="estado_membresia" class="form-input px-2 py-1 rounded bg-gray-700 text-white">
                    <option value="">Estado Membresía</option>
                    <option value="activa" <?php if($filtros['estado_membresia']==='activa') echo 'selected'; ?>>Activa</option>
                    <option value="pendiente" <?php if($filtros['estado_membresia']==='pendiente') echo 'selected'; ?>>Pendiente</option>
                </select>
                <input type="text" name="plan_nombre" placeholder="Plan" value="<?php echo htmlspecialchars($filtros['plan_nombre']); ?>" class="form-input px-2 py-1 rounded bg-gray-700 text-white">
                <button type="submit" class="btn-primary px-2 py-1 rounded">Filtrar</button>
            </form>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="py-2">Nombre</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">RUT</th>
                            <th class="py-2">Fecha Registro</th>
                            <th class="py-2">Membresía</th>
                            <th class="py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <?php
                            // Cambiar estado a 'vencida' si la fecha_fin ya pasó y la membresía está activa o pendiente
                            if ($usuario['membresia_id'] && in_array($usuario['estado_membresia'], ['activa','pendiente']) && $usuario['fecha_fin']) {
                                $fecha_fin = strtotime($usuario['fecha_fin']);
                                if ($fecha_fin < strtotime(date('Y-m-d'))) {
                                    // Actualizar en BD solo si aún no está vencida
                                    $stmtV = $pdo->prepare("UPDATE membresias SET estado = 'vencida' WHERE id = ?");
                                    $stmtV->execute([$usuario['membresia_id']]);
                                    $usuario['estado_membresia'] = 'vencida';
                                }
                            }
                            ?>
                            <tr class="border-b border-gray-700">
                                <td class="py-2">
                                    <?php echo htmlspecialchars($usuario['nombre']); ?>
                                    <a href="detalle_usuario.php?usuario_id=<?php echo $usuario['id']; ?>" class="ml-2 btn-primary px-2 py-1 rounded text-sm">Ver detalle</a>
                                </td>
                                <td class="py-2"><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($usuario['rut']); ?></td>
                                <td class="py-2"><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                                <td class="py-2">
                                    <?php if ($usuario['plan_nombre']): ?>
                                        <?php echo htmlspecialchars($usuario['plan_nombre']); ?>
                                        <br>
                                        <small class="text-gray-400">
                                            <?php echo date('d/m/Y', strtotime($usuario['fecha_inicio'])); ?> - 
                                            <?php echo date('d/m/Y', strtotime($usuario['fecha_fin'])); ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-gray-400">Sin membresía</span>
                                        <a href="agregar_membresia.php?usuario_id=<?php echo $usuario['id']; ?>" class="ml-2 btn-primary px-2 py-1 rounded text-sm">Agregar membresía</a>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2">
                                    <?php if ($usuario['membresia_id']): ?>
                                        <form method="POST" action="actualizar_estado_membresia.php" class="inline">
                                            <input type="hidden" name="membresia_id" value="<?php echo $usuario['membresia_id']; ?>">
                                            <select name="estado" class="form-select px-2 py-1 rounded bg-gray-700 text-white text-xs" onchange="this.form.submit()">
                                                <option value="activa" <?php echo $usuario['estado_membresia'] == 'activa' ? 'selected' : ''; ?>>Activa</option>
                                                <option value="pendiente" <?php echo $usuario['estado_membresia'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                                <option value="vencida" <?php echo $usuario['estado_membresia'] == 'vencida' ? 'selected' : ''; ?>>Vencida</option>
                                                <option value="cancelada" <?php echo $usuario['estado_membresia'] == 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                            </select>
                                        </form>
                                    <?php else: ?>
                                        <span class="px-2 py-1 rounded text-xs bg-gray-500 text-white">Sin membresía</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
