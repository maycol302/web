<?php
// detalle_ingresos_mejorado.php
// Panel mejorado con diseño moderno y visualización de datos
session_start();
require_once 'conexion.php';

// Verificar acceso
if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['admin_id']) && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
    header('Location: login.php');
    exit;
}

// Configuración de meses y años
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

$anio_actual = date('Y');
$anio_seleccionado = isset($_GET['anio']) ? intval($_GET['anio']) : $anio_actual;
$mes_seleccionado = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');
$nombre_filtro = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';

// Consultas mejoradas con estadísticas
$sql = "
    SELECT 
        p.id, p.fecha_pago, p.monto, p.estado, 
        u.nombre AS usuario, u.email, u.rut,
        pl.nombre AS plan_nombre, pl.precio AS plan_precio,
        m.fecha_inicio, m.fecha_fin
    FROM pagos p
    JOIN membresias m ON p.membresia_id = m.id
    JOIN usuarios u ON m.usuario_id = u.id
    JOIN planes pl ON m.plan_id = pl.id
    WHERE MONTH(p.fecha_pago) = :mes 
    AND YEAR(p.fecha_pago) = :anio 
    AND p.estado = 'aprobado'
";
if ($nombre_filtro !== '') {
    $sql .= " AND u.nombre LIKE :nombre ";
}
$sql .= " ORDER BY p.fecha_pago DESC";
$params = ['mes' => $mes_seleccionado, 'anio' => $anio_seleccionado];
if ($nombre_filtro !== '') {
    $params['nombre'] = "%$nombre_filtro%";
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ingresos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas mejoradas
$total_ingresos = array_sum(array_column($ingresos, 'monto'));
$promedio_ingreso = count($ingresos) > 0 ? $total_ingresos / count($ingresos) : 0;
$max_ingreso = count($ingresos) > 0 ? max(array_column($ingresos, 'monto')) : 0;

// Datos para gráficos
$ingresos_por_dia = [];
foreach ($ingresos as $ingreso) {
    $dia = date('d', strtotime($ingreso['fecha_pago']));
    if (!isset($ingresos_por_dia[$dia])) {
        $ingresos_por_dia[$dia] = 0;
    }
    $ingresos_por_dia[$dia] += $ingreso['monto'];
}

// Exportar a CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Repetir la consulta para exportar, respetando el filtro de nombre
    $stmt_export = $pdo->prepare($sql);
    $stmt_export->execute($params);
    $ingresos_export = $stmt_export->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="ingresos_'.$meses[$mes_seleccionado].'_'.$anio_seleccionado.'.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Fecha', 'Usuario', 'Email', 'RUT', 'Plan', 'Monto', 'Estado']);
    foreach ($ingresos_export as $row) {
        fputcsv($output, [
            date('d/m/Y', strtotime($row['fecha_pago'])),
            $row['usuario'],
            $row['email'],
            $row['rut'],
            $row['plan_nombre'],
            $row['monto'],
            ucfirst($row['estado'])
        ]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Ingresos - Panel Mejorado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            color: #ffffff;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        .gradient-text {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: translateY(-2px);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 215, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0); }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-black bg-opacity-90 border-b border-yellow-400/20 backdrop-blur-sm">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="assets/img/logo.jpg" alt="Fernagym" class="w-20 h-20 object-contain bg-black p-1 shadow-lg border-2 border-yellow-400 rounded-xl">
                    <div>
                        <h1 class="text-2xl font-bold gradient-text">Panel de Ingresos</h1>
                        <p class="text-gray-400 text-sm">Gestión financiera avanzada</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="admin.php" class="text-yellow-400 hover:text-yellow-300 transition-colors">
                        <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Volver al Admin
                    </a>
                </div>
            </div>
        </div>
    </header>


    <main class="container mx-auto px-6 py-8">
        <!-- Filtros y Exportar -->
        <form method="get" class="glass-card rounded-xl p-6 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-300">Mes:</label>
                        <select name="mes" id="mes" class="bg-gray-800 border border-yellow-400/30 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                            <?php foreach ($meses as $num => $nombre): ?>
                                <option value="<?php echo $num; ?>" <?php if ($mes_seleccionado == $num) echo 'selected'; ?>><?php echo $nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-300">Año:</label>
                        <select name="anio" id="anio" class="bg-gray-800 border border-yellow-400/30 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                            <?php for ($a = $anio_actual; $a >= $anio_actual - 5; $a--): ?>
                                <option value="<?php echo $a; ?>" <?php if ($anio_seleccionado == $a) echo 'selected'; ?>><?php echo $a; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-300">Nombre:</label>
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre_filtro); ?>" placeholder="Buscar nombre..." class="bg-gray-800 border border-yellow-400/30 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-yellow-400 focus:border-transparent" />
                    </div>
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105">
                        Filtrar
                    </button>
                </div>
                <a href="?mes=<?php echo $mes_seleccionado; ?>&anio=<?php echo $anio_seleccionado; ?>&nombre=<?php echo urlencode($nombre_filtro); ?>&export=csv" 
                   class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar CSV
                </a>
            </div>
        </form>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-xl p-6 hover-scale">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Ingresos</p>
                        <p class="text-2xl font-bold text-yellow-400">$<?php echo number_format($total_ingresos, 0, ',', '.'); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-400/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-xl p-6 hover-scale">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Promedio por Pago</p>
                        <p class="text-2xl font-bold text-green-400">$<?php echo number_format($promedio_ingreso, 0, ',', '.'); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-400/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-xl p-6 hover-scale">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Total Pagos</p>
                        <p class="text-2xl font-bold text-blue-400"><?php echo count($ingresos); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-400/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-xl p-6 hover-scale">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Máximo Pago</p>
                        <p class="text-2xl font-bold text-purple-400">$<?php echo number_format($max_ingreso, 0, ',', '.'); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-400/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Ingresos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="glass-card rounded-xl p-6">
                <h3 class="text-xl font-bold text-yellow-400 mb-4">Ingresos por Día</h3>
                <canvas id="ingresosChart" width="400" height="200"></canvas>
            </div>
            <div class="glass-card rounded-xl p-6">
                <h3 class="text-xl font-bold text-yellow-400 mb-4">Distribución de Ingresos</h3>
                <canvas id="distribucionChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Tabla de Ingresos Mejorada -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-yellow-400">Detalle de Ingresos</h3>
                <div class="text-sm text-gray-400">
                    Mostrando <?php echo count($ingresos); ?> registros
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-yellow-400/30">
                            <th class="text-left py-3 px-4 text-yellow-400 font-semibold">Fecha</th>
                            <th class="text-left py-3 px-4 text-yellow-400 font-semibold">Usuario</th>
                            <th class="text-left py-3 px-4 text-yellow-400 font-semibold">Email</th>
                            <th class="text-left py-3 px-4 text-yellow-400 font-semibold">Plan</th>
                            <th class="text-right py-3 px-4 text-yellow-400 font-semibold">Monto</th>
                            <th class="text-center py-3 px-4 text-yellow-400 font-semibold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ingresos as $index => $ingreso): ?>
                            <tr class="border-b border-gray-700 hover:bg-gray-800/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="text-sm"><?php echo date('d/m/Y', strtotime($ingreso['fecha_pago'])); ?></div>
                                    <div class="text-xs text-gray-400"><?php echo date('H:i', strtotime($ingreso['fecha_pago'])); ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium"><?php echo htmlspecialchars($ingreso['usuario']); ?></div>
                                    <div class="text-xs text-gray-400"><?php echo htmlspecialchars($ingreso['rut']); ?></div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-300"><?php echo htmlspecialchars($ingreso['email']); ?></td>
                                <td class="py-3 px-4">
                                    <div class="font-medium"><?php echo htmlspecialchars($ingreso['plan_nombre']); ?></div>
                                    <div class="text-xs text-gray-400">$<?php echo number_format($ingreso['plan_precio'], 0, ',', '.'); ?></div>
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-green-400">
                                    $<?php echo number_format($ingreso['monto'], 0, ',', '.'); ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">
                                        <?php echo ucfirst($ingreso['estado']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php if (empty($ingresos)): ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-400 text-lg">No hay ingresos registrados para este período</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script>
        // Datos para gráficos
        const dias = <?php echo json_encode(array_keys($ingresos_por_dia)); ?>;
        const valores = <?php echo json_encode(array_values($ingresos_por_dia)); ?>;

        // Gráfico de barras
        const ctx1 = document.getElementById('ingresosChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: dias,
                datasets: [{
                    label: 'Ingresos por día',
                    data: valores,
                    backgroundColor: 'rgba(255, 215, 0, 0.6)',
                    borderColor: 'rgba(255, 215, 0, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // Gráfico de línea para tendencia
        const ctx2 = document.getElementById('distribucionChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: dias,
                datasets: [{
                    label: 'Tendencia de Ingresos',
                    data: valores,
                    borderColor: 'rgba(34, 197, 94, 1)',
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // Auto-submit al cambiar filtros
        document.getElementById('mes').addEventListener('change', () => {
            document.querySelector('form').submit();
        });
        document.getElementById('anio').addEventListener('change', () => {
            document.querySelector('form').submit();
        });
    </script>
</body>
</html>
