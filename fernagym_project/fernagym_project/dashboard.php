<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener información del usuario
$stmt = $pdo->prepare("SELECT nombre, email, rut, telefono FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Obtener membresía actual
$stmt = $pdo->prepare("
    SELECT m.*, p.nombre as plan_nombre, p.duracion_dias, p.precio
    FROM membresias m
    JOIN planes p ON m.plan_id = p.id
    WHERE m.usuario_id = ?
    ORDER BY m.fecha_inicio DESC
    LIMIT 1
");
$stmt->execute([$user_id]);
$membresia = $stmt->fetch();

// Calcular días restantes
$dias_restantes = 0;
$estado_membresia = 'Sin membresía';
if ($membresia) {
    $fecha_fin = new DateTime($membresia['fecha_fin']);
    $fecha_actual = new DateTime();
    $interval = $fecha_actual->diff($fecha_fin);
    $dias_restantes = max(0, $interval->days);
    
    if ($fecha_fin < $fecha_actual) {
        $estado_membresia = 'Vencida';
    } else {
        $estado_membresia = 'Activa';
    }
}

// Obtener historial de pagos
$stmt = $pdo->prepare("
    SELECT p.fecha_pago, p.monto, p.estado, pl.nombre as plan_nombre
    FROM pagos p
    JOIN membresias m ON p.membresia_id = m.id
    JOIN planes pl ON m.plan_id = pl.id
    WHERE m.usuario_id = ?
    ORDER BY p.fecha_pago DESC
    LIMIT 5
");
$stmt->execute([$user_id]);
$historial_pagos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Fernagym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #000;
            color: #fff;
        }
        .dashboard-card {
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
        .status-active {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid #22c55e;
        }
        .status-expired {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid #ef4444;
        }
        .status-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid #f59e0b;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-black bg-opacity-90 border-b border-yellow-400 py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="assets/img/logo.jpg" alt="Fernagym" class="w-32">
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-300">Hola, <?php echo htmlspecialchars($user['nombre']); ?></span>
                <a href="logout.php" class="text-yellow-400 hover:text-yellow-300">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-yellow-400 mb-8">Mi Perfil</h1>
        
        <!-- Información del Usuario -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="dashboard-card rounded-lg p-6">
                <h3 class="text-xl font-semibold text-yellow-400 mb-4">Información Personal</h3>
                <div class="space-y-2">
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre']); ?></p>
                    <p><strong>RUT:</strong> <?php echo htmlspecialchars($user['rut']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['telefono']); ?></p>
                </div>
            </div>
            
            <!-- Estado de Membresía -->
            <div class="dashboard-card rounded-lg p-6">
                <h3 class="text-xl font-semibold text-yellow-400 mb-4">Estado de Membresía</h3>
                <?php if ($membresia): ?>
                    <div class="space-y-2">
                        <p><strong>Plan:</strong> <?php echo htmlspecialchars($membresia['plan_nombre']); ?></p>
                        <p><strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($membresia['fecha_inicio'])); ?></p>
                        <p><strong>Vencimiento:</strong> <?php echo date('d/m/Y', strtotime($membresia['fecha_fin'])); ?></p>
                        <p><strong>Días restantes:</strong> <?php echo $dias_restantes; ?></p>
                        <div class="mt-4">
                            <span class="px-3 py-1 rounded-full text-sm <?php echo $estado_membresia == 'Activa' ? 'status-active' : 'status-expired'; ?>">
                                <?php echo $estado_membresia; ?>
                            </span>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400">No tienes una membresía activa.</p>
                    <a href="renovar-membresia.php" class="btn-primary inline-block mt-4 px-4 py-2 rounded">
                        Adquirir Membresía
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Próxima Clase -->
            <div class="dashboard-card rounded-lg p-6">
                <h3 class="text-xl font-semibold text-yellow-400 mb-4">Próximas Acciones</h3>
                <div class="space-y-3">
                    <?php if ($membresia && $estado_membresia == 'Activa'): ?>
                        <a href="reservar-clase.php" class="block w-full text-center bg-yellow-400 text-black py-2 rounded hover:bg-yellow-300">
                            Reservar Clase
                        </a>
                        <a href="horarios.php" class="block w-full text-center bg-gray-700 text-white py-2 rounded hover:bg-gray-600">
                            Ver Horarios
                        </a>
                    <?php else: ?>
                        <a href="renovar-membresia.php" class="block w-full text-center bg-yellow-400 text-black py-2 rounded hover:bg-yellow-300">
                            Renovar Membresía
                        </a>
                    <?php endif; ?>
                    <a href="editar-perfil.php" class="block w-full text-center bg-gray-700 text-white py-2 rounded hover:bg-gray-600">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Historial de Pagos -->
        <div class="dashboard-card rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-yellow-400 mb-4">Historial de Pagos</h3>
            <?php if (count($historial_pagos) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-600">
                                <th class="py-2">Fecha</th>
                                <th class="py-2">Plan</th>
                                <th class="py-2">Monto</th>
                                <th class="py-2">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial_pagos as $pago): ?>
                                <tr class="border-b border-gray-700">
                                    <td class="py-2"><?php echo date('d/m/Y', strtotime($pago['fecha_pago'])); ?></td>
                                    <td class="py-2"><?php echo htmlspecialchars($pago['plan_nombre']); ?></td>
                                    <td class="py-2">$<?php echo number_format($pago['monto'], 0, ',', '.'); ?></td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 rounded text-xs <?php 
                                            echo $pago['estado'] == 'aprobado' ? 'status-active' : 
                                                ($pago['estado'] == 'pendiente' ? 'status-pending' : 'status-expired');
                                        ?>">
                                            <?php echo ucfirst($pago['estado']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-400">No tienes pagos registrados.</p>
            <?php endif; ?>
        </div>
        
        <!-- Barra de Progreso de Membresía -->
        <?php if ($membresia): ?>
            <div class="dashboard-card rounded-lg p-6">
                <h3 class="text-xl font-semibold text-yellow-400 mb-4">Progreso de Membresía</h3>
                <?php
                $total_dias = $membresia['duracion_dias'];
                $dias_transcurridos = $total_dias - $dias_restantes;
                $porcentaje = min(100, max(0, ($dias_transcurridos / $total_dias) * 100));
                ?>
                <div class="w-full bg-gray-700 rounded-full h-2.5">
                    <div class="bg-yellow-400 h-2.5 rounded-full" style="width: <?php echo $porcentaje; ?>%"></div>
                </div>
                <p class="text-sm text-gray-400 mt-2">
                    <?php echo $dias_transcurridos; ?> días transcurridos de <?php echo $total_dias; ?> días totales
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
