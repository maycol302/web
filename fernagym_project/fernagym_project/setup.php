<?php
// Script para inicializar la base de datos
require_once 'conexion.php';

try {
    // Ejecutar el script SQL
    $sql = file_get_contents('sql/update_database.sql');
    $pdo->exec($sql);
    
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Setup Fernagym</title>
        <script src='https://cdn.tailwindcss.com'></script>
    </head>
    <body class='bg-black text-white flex items-center justify-center min-h-screen'>
        <div class='text-center'>
            <h1 class='text-3xl font-bold text-yellow-400 mb-4'>✅ Base de datos inicializada</h1>
            <p class='text-gray-300 mb-4'>La base de datos ha sido configurada correctamente.</p>
            <p class='text-gray-400 mb-2'>Usuario admin: admin@fernagym.cl</p>
            <p class='text-gray-400 mb-4'>Contraseña: password</p>
            <a href='index.php' class='bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300'>Ir al inicio</a>
        </div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error Setup Fernagym</title>
        <script src='https://cdn.tailwindcss.com'></script>
    </head>
    <body class='bg-black text-white flex items-center justify-center min-h-screen'>
        <div class='text-center'>
            <h1 class='text-3xl font-bold text-red-400 mb-4'>❌ Error al inicializar</h1>
            <p class='text-gray-300 mb-4'>Error: " . $e->getMessage() . "</p>
            <a href='index.php' class='bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300'>Ir al inicio</a>
        </div>
    </body>
    </html>";
}
?>
