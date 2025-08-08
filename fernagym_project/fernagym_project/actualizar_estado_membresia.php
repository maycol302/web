<?php
session_start();
require_once 'conexion.php';

// Verificar si es admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Verificar que se reciban los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $membresia_id = isset($_POST['membresia_id']) ? intval($_POST['membresia_id']) : 0;
    $nuevo_estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    
    // Validar el estado
    $estados_validos = ['activa', 'pendiente', 'vencida', 'cancelada'];
    
    if ($membresia_id > 0 && in_array($nuevo_estado, $estados_validos)) {
        try {
            // Actualizar el estado de la membresía
            $stmt = $pdo->prepare("UPDATE membresias SET estado = ? WHERE id = ?");
            $stmt->execute([$nuevo_estado, $membresia_id]);
            
            // Redirigir de vuelta al admin con mensaje de éxito
            $_SESSION['mensaje'] = 'Estado de membresía actualizado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: admin.php');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['mensaje'] = 'Error al actualizar el estado: ' . $e->getMessage();
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: admin.php');
            exit;
        }
    } else {
        $_SESSION['mensaje'] = 'Datos inválidos';
        $_SESSION['tipo_mensaje'] = 'error';
        header('Location: admin.php');
        exit;
    }
} else {
    // Si no es POST, redirigir al admin
    header('Location: admin.php');
    exit;
}
?>
