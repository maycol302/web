<?php
session_start();
require_once 'conexion.php';

// Verificar si es admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pago_id'])) {
    $pago_id = intval($_POST['pago_id']);
    
    try {
        // Actualizar estado del pago
        $stmt = $pdo->prepare("UPDATE pagos SET estado = 'aprobado' WHERE id = ?");
        $stmt->execute([$pago_id]);
        
        // Obtener información del pago y membresía
        $stmt = $pdo->prepare("
            SELECT m.id as membresia_id, m.usuario_id, m.plan_id, m.fecha_fin,
                   u.email, u.nombre,
                   p.nombre as plan_nombre
            FROM pagos p
            JOIN membresias m ON p.membresia_id = m.id
            JOIN usuarios u ON m.usuario_id = u.id
            JOIN planes p ON m.plan_id = p.id
            WHERE p.id = ?
        ");
        $stmt->execute([$pago_id]);
        $info = $stmt->fetch();
        
        if ($info) {
            // Activar membresía
            $stmt = $pdo->prepare("UPDATE membresias SET estado = 'activa' WHERE id = ?");
            $stmt->execute([$info['membresia_id']]);
            
            // Enviar email de confirmación al cliente
            $subject = "Pago Aprobado - Fernagym";
            $message = "<h2>¡Tu pago ha sido aprobado!</h2>"
                . "<p>Hola {$info['nombre']},</p>"
                . "<p>Tu pago por la membresía {$info['plan_nombre']} ha sido aprobado.</p>"
                . "<p>Tu membresía está ahora activa y vence el " . date('d/m/Y', strtotime($info['fecha_fin'])) . ".</p>"
                . "<p>¡Bienvenido a Fernagym!</p>";
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: Fernagym <no-reply@fernagym.cl>\r\n";
            
            mail($info['email'], $subject, $message, $headers);
        }
        
        header('Location: admin.php?success=1');
        exit;
    } catch (PDOException $e) {
        header('Location: admin.php?error=1');
        exit;
    }
}

header('Location: admin.php');
exit;
?>
