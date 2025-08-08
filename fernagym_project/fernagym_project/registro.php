<?php
session_start();
require_once 'conexion.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $rut = trim($_POST['rut'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validaciones
    if (!$nombre || !$rut || !$email || !$telefono || !$password || !$confirm_password) {
        $error = 'Por favor completa todos los campos.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        try {
            // Validar RUT
            function validarRut($rut) {
                $rut = preg_replace('/[^kK0-9]/', '', $rut);
                if (strlen($rut) < 2) return false;
                $dv = substr($rut, -1);
                $numero = substr($rut, 0, -1);
                if (!is_numeric($numero)) return false;
                
                $suma = 0;
                $multiplo = 2;
                for ($i = strlen($numero) - 1; $i >= 0; $i--) {
                    $suma += $numero[$i] * $multiplo;
                    $multiplo = $multiplo == 7 ? 2 : $multiplo + 1;
                }
                $dv_esperado = 11 - ($suma % 11);
                $dv_esperado = $dv_esperado == 11 ? '0' : ($dv_esperado == 10 ? 'K' : strval($dv_esperado));
                
                return strtoupper($dv) === $dv_esperado;
            }
            
            if (!validarRut($rut)) {
                $error = 'RUT inválido.';
            } else {
                // Verificar si el usuario ya existe
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? OR rut = ?");
                $stmt->execute([$email, $rut]);
                
                if ($stmt->fetch()) {
                    $error = 'El email o RUT ya está registrado.';
                } else {
                    // Registrar usuario
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, rut, email, telefono, password_hash) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $rut, $email, $telefono, $password_hash]);
                    
                    $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Error al registrar el usuario.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Fernagym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            min-height: 100vh;
        }
        .register-container {
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
    <div class="register-container rounded-lg p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <img src="assets/img/logo.jpg" alt="Fernagym" class="w-32 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-yellow-400 mb-2">Registro</h1>
            <p class="text-gray-400">Crea tu cuenta y accede a nuestros servicios</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-300 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-300 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-6">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-300 mb-2">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="Juan Pérez">
            </div>
            
            <div>
                <label for="rut" class="block text-sm font-medium text-gray-300 mb-2">RUT</label>
                <input type="text" id="rut" name="rut" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="12.345.678-9">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Correo Electrónico</label>
                <input type="email" id="email" name="email" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="tu@email.com">
            </div>
            
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-300 mb-2">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="+56 9 1234 5678">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="••••••••">
            </div>
            
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-2">Confirmar Contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                       class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                       placeholder="••••••••">
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="terms" required class="rounded bg-gray-800 border-gray-600 text-yellow-400">
                <label class="ml-2 text-sm text-gray-400">
                    Acepto los <a href="#" class="text-yellow-400 hover:text-yellow-300">términos y condiciones</a>
                </label>
            </div>
            
            <button type="submit" class="btn-primary w-full py-3 rounded-lg font-semibold">
                Registrarse
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-400">¿Ya tienes cuenta?</p>
            <a href="login.php" class="text-yellow-400 hover:text-yellow-300 font-semibold">Inicia sesión aquí</a>
        </div>
        
        <div class="mt-4 text-center">
            <a href="index.php" class="text-gray-400 hover:text-yellow-400 text-sm">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
