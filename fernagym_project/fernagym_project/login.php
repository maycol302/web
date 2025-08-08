<?php
session_start();
require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = ? AND estado = 'activo'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['rol'];

                // Generar token de sesión
                $token = bin2hex(random_bytes(32));
                $expiracion = date('Y-m-d H:i:s', strtotime('+30 days'));

                $stmt = $pdo->prepare("INSERT INTO sesiones (usuario_id, token, fecha_expiracion, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $user['id'],
                    $token,
                    $expiracion,
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null
                ]);

                $_SESSION['token'] = $token;

                if ($user['rol'] === 'admin') {
                    header('Location: admin.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
            } else {
                $error = 'Credenciales inválidas o cuenta inactiva.';
            }
        } catch (PDOException $e) {
            $error = 'Error al procesar el login.';
        }
    } else {
        $error = 'Por favor completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Fernagym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            min-height: 100vh;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.3);
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
        .form-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            color: white;
        }
        .form-input:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.3);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="login-container rounded-lg p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <img src="assets/img/logo.jpg" alt="Fernagym" class="w-32 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-yellow-400 mb-2">Iniciar Sesión</h1>
            <p class="text-gray-400">Accede a tu cuenta y gestiona tu membresía</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-300 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Correo Electrónico</label>
                <input type="email" id="email" name="email" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="tu@email.com">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="••••••••">
            </div>
            
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded bg-gray-800 border-gray-600 text-yellow-400">
                    <span class="ml-2 text-sm text-gray-400">Recordarme</span>
                </label>
                <a href="recuperar-contrasena.php" class="text-sm text-yellow-400 hover:text-yellow-300">¿Olvidaste tu contraseña?</a>
            </div>
            
            <button type="submit" class="btn-primary w-full py-3 rounded-lg font-semibold">
                Iniciar Sesión
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-400">¿No tienes cuenta?</p>
            <a href="registro.php" class="text-yellow-400 hover:text-yellow-300 font-semibold">Regístrate aquí</a>
        </div>
        
        <div class="mt-4 text-center">
            <a href="index.php" class="text-gray-400 hover:text-yellow-400 text-sm">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
