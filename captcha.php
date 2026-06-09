<?php
// Aseguramos que la sesión esté activa para poder guardar los números
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generamos dos números aleatorios del 1 al 9
$_SESSION['num1'] = rand(1, 9);
$_SESSION['num2'] = rand(1, 9);

// Guardamos el resultado correcto en la sesión para validarlo en el index
$_SESSION['captcha_resultado'] = $_SESSION['num1'] + $_SESSION['num2'];
?>