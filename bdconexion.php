<?php

$host = "localhost";
$db   = "db_crud_pdo";
$user = "root";
$pass = "";

try {
    // Configuración de la conexión PDO con atributos de seguridad y manejo de errores
    $cnnPDO = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // En producción es mejor no mostrar el mensaje directo, pero para tu entorno académico es ideal para depurar
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

?>