<?php

$host = "localhost";
$db   = "db_crud_pdo";
$user = "root";
$pass = "";

try {
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
    echo "Error de conexión: " . $e->getMessage();
    exit();
}

?>