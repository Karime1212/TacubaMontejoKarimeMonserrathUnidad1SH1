<?php
session_start();
require_once 'bdconexion.php';

$msg = "";
$tipo_alert = "danger";

if(isset($_POST['recuperar'])){
    $correo = trim($_POST['correo']);

    if(!empty($correo)){
        $stmt = $cnnPDO->prepare("SELECT * FROM usuarixs WHERE correo = :cor");
        $stmt->bindParam(':cor', $correo);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            $token = bin2hex(random_bytes(16));
            $_SESSION['token_recuperacion'] = $token;
            $_SESSION['correo_recuperacion'] = $correo;

            // Construcción automática compatible con XAMPP y con InfinityFree
            $protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
            $enlace = $protocolo . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/cambiar_clave.php?token=" . $token;

            $tipo_alert = "success";
            $msg = "<b>Simulación de Auditoría (Saber Hacer):</b><br>
                    Se ha despachado la orden de recuperación para <b>$correo</b>.<br>
                    <a href='$enlace' class='btn btn-warning btn-sm mt-3 fw-bold text-dark w-100'>
                        <i class='fa-solid fa-envelope-open-text me-1'></i> Abrir Correo de Recuperación
                    </a>";
        } else {
            $msg = "El correo electrónico proporcionado no se encuentra en el sistema.";
        }
    } else {
        $msg = "Por favor introduce un correo válido.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Credenciales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body style="background: #2b3035; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px;">

<div class="principal" style="max-width: 450px; width: 100%;">
    <?php if($msg != ""){ ?>
        <div class="alert text-center mb-4 p-3 bg-<?php echo $tipo_alert; ?> text-white rounded shadow fw-bold" style="font-size: 14px; border: none;">
            <?php echo $msg; ?>
        </div>
    <?php } ?>

    <div class="caja">
        <h1 class="titulo" style="font-size: 32px;">Recuperación</h1>
        <div class="linea mb-4"></div>
        <p class="text-white-50 text-center small mb-4">Ingresa tu correo registrado para enviarte el enlace de restauración.</p>

        <form method="POST" autocomplete="off">
            <div class="inputBox">
                <i class="fa-solid fa-envelope icono"></i>
                <input type="email" name="correo" class="form-control" placeholder="Correo Electrónico" required>
            </div>

            <div class="botones text-center mt-4">
                <button type="submit" name="recuperar" class="btnreg mb-2">Generar Enlace</button>
                <a href="index.php" class="text-muted text-decoration-none d-block mt-2 small">
                    <i class="fa-solid fa-arrow-left me-1"></i>Regresar al inicio
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>