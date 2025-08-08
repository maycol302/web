<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['user_id'])) {
        die('Debes iniciar sesión para inscribirte.');
    }

    $usuario_id = $_SESSION['user_id'];
    $plan_nombre = trim($_POST['plan'] ?? '');
    $comprobante = $_FILES['comprobante'] ?? null;

    if (!$plan_nombre || !$comprobante) {
        die('Faltan datos requeridos.');
    }

    // Obtener información del plan
    $stmt = $pdo->prepare("SELECT id, duracion_dias, precio FROM planes WHERE nombre = ? AND estado = 'activo'");
    $stmt->execute([$plan_nombre]);
    $plan = $stmt->fetch();

    if (!$plan) {
        die('Plan no válido.');
    }

    // Guardar comprobante de pago
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $file_ext = pathinfo($comprobante['name'], PATHINFO_EXTENSION);
    $file_name = uniqid('comprobante_') . '.' . $file_ext;
    $file_path = $upload_dir . $file_name;
    
    if (!move_uploaded_file($comprobante['tmp_name'], $file_path)) {
        die('Error al subir el comprobante.');
    }

    // Calcular fechas de membresía
    $fecha_inicio = new DateTime();
    $fecha_fin = (clone $fecha_inicio)->modify("+{$plan['duracion_dias']} days");

    // Insertar membresía
    $stmt = $pdo->prepare("INSERT INTO membresias (usuario_id, plan_id, fecha_inicio, fecha_fin, fecha_pago, monto_pago, comprobante, estado) VALUES (?, ?, ?, ?, NOW(), ?, ?, 'pendiente')");
    $stmt->execute([
        $usuario_id,
        $plan['id'],
        $fecha_inicio->format('Y-m-d'),
        $fecha_fin->format('Y-m-d'),
        $plan['precio'],
        $file_name
    ]);

    $membresia_id = $pdo->lastInsertId();

    // Insertar pago (sin método de pago editable por usuario, solo admin lo gestiona luego)
    $stmt = $pdo->prepare("INSERT INTO pagos (membresia_id, monto, comprobante, estado) VALUES (?, ?, ?, 'pendiente')");
    $stmt->execute([$membresia_id, $plan['precio'], $file_name]);

    // Obtener información del usuario
    $stmt = $pdo->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $user = $stmt->fetch();

    $admin_email = 'contacto@fernagym.cl';

    // Email para el administrador
    $subject_admin = "Nueva inscripción en Fernagym";
    $message_admin = "<h2>Nueva inscripción</h2>"
        . "<p><b>Usuario:</b> {$user['nombre']}</p>"
        . "<p><b>Plan:</b> $plan_nombre</p>"
        . "<p><b>Monto:</b> $" . number_format($plan['precio'], 0, ',', '.') . "</p>"
        . "<p>Comprobante adjunto.</p>";

    $boundary = md5(uniqid(time()));
    $headers_admin = "MIME-Version: 1.0\r\n";
    $headers_admin .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $headers_admin .= "From: Fernagym <no-reply@fernagym.cl>\r\n";

    $body_admin = "--$boundary\r\n";
    $body_admin .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
    $body_admin .= $message_admin . "\r\n";
    $body_admin .= "--$boundary\r\n";
    $body_admin .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
    $body_admin .= "Content-Transfer-Encoding: base64\r\n";
    $body_admin .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
    $body_admin .= chunk_split(base64_encode(file_get_contents($file_path))) . "\r\n";
    $body_admin .= "--$boundary--";

    mail($admin_email, $subject_admin, $body_admin, $headers_admin);

    // Email para el cliente
    $subject_cliente = "Inscripción recibida - Fernagym";
    $message_cliente = "<h2>¡Gracias por tu inscripción en Fernagym!</h2>"
        . "<p>Hemos recibido tu solicitud de membresía.</p>"
        . "<p><b>Plan seleccionado:</b> $plan_nombre</p>"
        . "<p><b>Fecha de inicio:</b> " . $fecha_inicio->format('d/m/Y') . "</p>"
        . "<p><b>Fecha de vencimiento:</b> " . $fecha_fin->format('d/m/Y') . "</p>"
        . "<p>Tu membresía estará activa una vez que verifiquemos tu pago.</p>"
        . "<p>Si tienes dudas, contáctanos a $admin_email</p>";
    $headers_cliente = "MIME-Version: 1.0\r\n";
    $headers_cliente .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers_cliente .= "From: Fernagym <no-reply@fernagym.cl>\r\n";

    mail($user['email'], $subject_cliente, $message_cliente, $headers_cliente);

    echo '<script>alert("¡Inscripción enviada correctamente! Tu membresía estará activa una vez verificado el pago.");window.location.href="dashboard.php";</script>';
    exit;
}
?>
