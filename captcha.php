<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['num1'] = rand(1, 9);
$_SESSION['num2'] = rand(1, 9);

$_SESSION['captcha_resultado'] = $_SESSION['num1'] + $_SESSION['num2'];
?>