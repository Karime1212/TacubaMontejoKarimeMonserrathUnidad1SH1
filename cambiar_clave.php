<?php
session_start();
require_once 'bdconexion.php';

$msg = "";
$tipo_alert = "danger";
$mostrar_formulario = false;

// Verificación estricta de seguridad: el token de la URL debe coincidir con el guardado en la sesión
if(isset($_GET['token']) && isset($_SESSION['token_recuperacion']) && $_GET['token'] === $_SESSION['token_recuperacion']){
    $mostrar_formulario = true;
} else {
    $msg = "Error: El enlace de recuperación ha expirado o es inválido.";
}

if(isset($_POST['actualizar']) && $mostrar_formulario){
    $nueva_clave = $_POST['nueva_clave'];
    $confirmar_clave = $_POST['confirmar_clave'];
    $correo = $_SESSION['correo_recuperacion'];

    if(!empty($nueva_clave) && !empty($confirmar_clave)){
        if($nueva_clave === $confirmar_clave){
            
            // Generación en tiempo real de los hashes estático e híbrido requeridos
            $sha = hash('sha256', $nueva_clave);
            $bcrypt = password_hash($nueva_clave, PASSWORD_DEFAULT);

            // Actualización directa de la base de datos mediante sentencias preparadas de PDO
            $stmt = $cnnPDO->prepare("UPDATE usuarixs SET sha = :sha, bcrypt = :bcrypt WHERE correo = :cor");
            $stmt->bindParam(':sha', $sha);
            $stmt->bindParam(':bcrypt', $bcrypt);
            $stmt->bindParam(':cor', $correo);
            $stmt->execute();

            // Destrucción de las variables de control de la sesión por seguridad
            unset($_SESSION['token_recuperacion']);
            unset($_SESSION['correo_recuperacion']);

            $msg = "¡Contraseña actualizada con éxito! Redirigiendo al inicio de sesión...";
            $tipo_alert = "success";
            $mostrar_formulario = false;

            // Redirección automática al index.php tras 3 segundos
            header("refresh:3;url=index.php");
        } else {
            $msg = "Error: Las contraseñas ingresadas no coinciden.";
        }
    } else {
        $msg = "Por favor, completa todos los campos de verificación.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecer Nueva Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body style="background: #d9d9d9; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;">

<div class="principal">
    <?php if($msg != ""){ ?>
        <div class="alert text-center mb-4 p-3 bg-<?php echo $tipo_alert; ?> text-white rounded shadow-sm fw-bold"><?php echo $msg; ?></div>
    <?php } ?>

    <?php if($mostrar_formulario){ ?>
    <div class="caja">
        <h1 class="titulo" style="font-size: 28px;">Nueva Contraseña</h1>
        <div class="linea mb-4"></div>

        <form method="POST">
            <div class="inputBox">
                <i class="fa-solid fa-lock icono"></i>
                <input type="password" name="nueva_clave" class="form-control" placeholder="Nueva Contraseña" minlength="6" required>
            </div>

            <div class="inputBox">
                <i class="fa-solid fa-circle-check icono"></i>
                <input type="password" name="confirmar_clave" class="form-control" placeholder="Confirmar Nueva Contraseña" minlength="6" required>
            </div>

            <div class="botones text-center mt-4">
                <button type="submit" name="actualizar" class="btnreg btn w-100">Actualizar Credenciales</button>
            </div>
        </form>
    </div>
    <?php } else if($tipo_alert !== "success") { ?>
        <div class="text-center">
            <a href="index.php" class="btn btnreg text-white" style="background: #252934;">Ir al Inicio</a>
        </div>
    <?php } ?>
</div>
</body>
</html>