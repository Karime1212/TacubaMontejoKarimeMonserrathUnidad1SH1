<?php
session_start();
require_once 'bdconexion.php';

$msg = "";
$modo_actual = "registro"; 

if (!isset($_SESSION['captcha_letras']) || isset($_GET['refresh_captcha'])) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $_SESSION['captcha_letras'] = substr(str_shuffle($caracteres), 0, 5);
    if (isset($_GET['refresh_captcha'])) {
        header("Location: index.php");
        exit();
    }
}

if(isset($_POST['registro'])){
    $modo_actual = "registro";
    $nom = $_POST['nombre'];
    $cor = $_POST['correo'];
    $cla = $_POST['clave'];
    
    $captcha_usuario = isset($_POST['captcha_ans']) ? trim($_POST['captcha_ans']) : '';
    
    if (strtolower($captcha_usuario) !== strtolower($_SESSION['captcha_letras'])) {
        $msg = "Error: La verificación de letras es incorrecta.";
    } else if(!empty($nom) && !empty($cor) && !empty($cla)){
        $stmt = $cnnPDO->prepare("
            INSERT INTO usuarixs(nombre,correo,password)
            VALUES(:nom,:cor,:cla)
        ");

        $stmt->bindParam(':nom',$nom);
        $stmt->bindParam(':cor',$cor);
        $stmt->bindParam(':cla',$cla);
        $stmt->execute();

        $msg = "Usuario registrado correctamente. ¡Ya puedes iniciar sesión!";
        $modo_actual = "login"; 
        
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $_SESSION['captcha_letras'] = substr(str_shuffle($caracteres), 0, 5);
    } else {
        $msg = "Por favor, llena todos los campos para el registro.";
    }
}

if(isset($_POST['login'])){
    $modo_actual = "login";
    $usu = isset($_POST['correo']) ? $_POST['correo'] : '';
    $cla = isset($_POST['clave']) ? $_POST['clave'] : '';

    if(!empty($usu) && !empty($cla)){
        $stmt = $cnnPDO->prepare("
            SELECT * FROM usuarixs
            WHERE correo=:usu AND password=:cla
        ");

        $stmt->bindParam(':usu',$usu);
        $stmt->bindParam(':cla',$cla);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data){
            $_SESSION['nombre'] = $data['nombre'];
            header("Location: 404.php");
            exit();
        }else{
            $msg = "Usuario o contraseña incorrectos";
        }
    } else {
        $msg = "Por favor, ingresa tu correo y contraseña.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureAuth - Sistema de Seguridad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .navbar-moderna .titulo-mapa {
            color: #ffffff !important;
        }
        .navbar-moderna .enlace-moderno {
            color: #ffffff !important;
        }
        .navbar-moderna .enlace-moderno i {
            color: #ffffff !important;
        }
        .navbar-moderna .enlace-moderno:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body style="display: block; min-height: 100vh; background: #d9d9d9; margin: 0;">

<div class="navbar-moderna">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-dark p-2 rounded-3 me-2" style="border: 1px solid #374151;">
                        <i class="fa-solid fa-sitemap text-white fs-5"></i>
                    </div>
                    <h6 class="titulo-mapa">Mapa del Sitio<br><span class="text-white-50 fw-normal" style="font-size: 12px;">Estructurado</span></h6>
                </div>
            </div>
            
            <div class="col-md-4 col-6 mb-2 mb-md-0">
                <ul>
                    <li><a class="enlace-moderno" href="index.php"><i class="fa-solid fa-house"></i> Inicio (Index)</a></li>
                    <li><a class="enlace-moderno" href="#" data-bs-toggle="modal" data-bs-target="#modalAyuda"><i class="fa-solid fa-circle-question"></i> Sección Ayuda</a></li>
                </ul>
            </div>
            
            <div class="col-md-4 col-6 mb-2 mb-md-0">
                <ul>
                    <li><a class="enlace-moderno" href="#" data-bs-toggle="modal" data-bs-target="#modalBuzon"><i class="fa-solid fa-comment-dots"></i> Sección Buzón</a></li>
                    <li><a class="enlace-moderno" href="#" data-bs-toggle="modal" data-bs-target="#modalContacto"><i class="fa-solid fa-address-book"></i> Sección Contacto</a></li>
                </ul>
            </div>

        </div>
    </div>
</div>

<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 75vh; margin-top: 20px; margin-bottom: 40px;">
    <div class="principal">

        <?php if($msg != ""){ ?>
            <div class="alerta text-center mb-3 p-2 bg-danger text-white rounded"><?php echo $msg; ?></div>
        <?php } ?>

        <div class="caja">
            <h1 class="titulo" id="form-titulo">Registro</h1>
            <div class="linea"></div>

            <form method="POST" id="auth-form" class="needs-validation" novalidate>
                
                <div class="inputBox" id="grupo-nombre">
                    <i class="fa-solid fa-user icono"></i>
                    <input type="text" name="nombre" id="input-nombre" class="form-control" placeholder="Nombre" minlength="3">
                </div>

                <div class="inputBox">
                    <i class="fa-solid fa-envelope icono"></i>
                    <input type="email" name="correo" class="form-control" placeholder="Correo" required>
                </div>

                <div class="inputBox">
                    <i class="fa-solid fa-lock icono"></i>
                    <input type="password" name="clave" class="form-control" placeholder="Contraseña" minlength="6" required>
                </div>

                <div class="inputBox" id="grupo-captcha">
                    <i class="fa-solid fa-font icono"></i>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="form-control text-center fw-bold unselectable" style="background: #111827; color: #ffffff; letter-spacing: 5px; font-size: 20px; width: 40%; user-select: none; line-height: 40px; border: 1px solid #374151;"><?php echo $_SESSION['captcha_letras']; ?></span>
                        <a href="index.php?refresh_captcha=1" class="btn btn-outline-light d-flex align-items-center justify-content-center" style="height: 55px; width: 15%; border-radius: 10px;" title="Cambiar captcha">
                            <i class="fa-solid fa-rotate-right m-0 p-0" style="color: #ffffff; font-size: 16px;"></i>
                        </a>
                        <input type="text" name="captcha_ans" id="input-captcha" class="form-control" style="width: 45%;" placeholder="Letras" autocomplete="off">
                    </div>
                </div>

                <div class="botones text-center mt-4">
                    <button type="submit" name="registro" id="btn-enviar-registro" class="btnreg btn w-100">Regístrate</button>
                    <button type="submit" name="login" id="btn-enviar-login" class="btnreg btn w-100 d-none">Ingresar</button>
                </div>

                <p class="text-center text-white mt-4 mb-0 info-alternar">
                    <span id="texto-alternar">¿Ya tienes cuenta?</span>  
                    <a href="#" id="enlace-alternar" class="ms-1" style="color: #0ac18e; text-decoration: none; font-weight: bold;">Inicia Sesión</a>
                </p>
            </form>
        </div>
    </div>

    <div class="text-center mt-4 p-3 rounded-3" style="background: #252934; border: 1px solid #374151; width: 100%; max-width: 500px; box-shadow: 0px 10px 30px rgba(0,0,0,0.15);">
        <span class="fw-bold text-white" style="font-size: 1.1rem; letter-spacing: 0.3px;">SecureAuth ID <span style="color: #ffffff;">© 2026</span></span>
        <div style="width: 40px; height: 3px; background: #ffffff; margin: 8px auto; border-radius: 10px;"></div>
        <span class="text-white d-block" style="font-size: 13px; opacity: 0.85;">Desarrollo de Web Profesional</span>
    </div>
</div>

<div class="modal fade" id="modalBuzon" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-success"><i class="fa-solid fa-inbox"></i> Buzón de Sugerencias</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control bg-secondary text-white border-0 mb-3" rows="4" placeholder="Escribe tus comentarios académicos aquí..."></textarea>
                <button type="button" class="btn btnreg text-white" style="background: #0ac18e;" data-bs-dismiss="modal">Enviar Mensaje</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAyuda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-success"><i class="fa-solid fa-circle-info"></i> Centro de Ayuda</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Para interactuar con la plataforma, sigue estas pautas:</p>
                <ul>
                    <li><strong>Registro:</strong> Ingresa tu nombre, correo y genera una contraseña segura. Verifica que no eres un bot.</li>
                    <li><strong>Inicio de Sesión:</strong> Escribe tus datos, correo y contraseña que hayas registrado anteriorme,te y accede de forma segura.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalContacto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-success"><i class="fa-solid fa-envelope"></i> Contáctanos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2">¿Tienes alguna duda?</p>
                <p class="fw-bold text-info">Soporte: Karumi@support.com.mx</p>
                <p class="text-muted small">Tecnologías de la Información Support</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const grupoNombre = document.getElementById('grupo-nombre');
    const inputNombre = document.getElementById('input-nombre');
    const grupoCaptcha = document.getElementById('grupo-captcha');
    const inputCaptcha = document.getElementById('input-captcha');
    const formTitulo = document.getElementById('form-titulo');
    const btnEnviarRegistro = document.getElementById('btn-enviar-registro');
    const btnEnviarLogin = document.getElementById('btn-enviar-login');
    const textoAlternar = document.getElementById('texto-alternar');
    const enlaceAlternar = document.getElementById('enlace-alternar');
    let modo = "<?php echo $modo_actual; ?>";

    function aplicarModo() {
        if (modo === "login") {
            formTitulo.textContent = "Inicia sesión";
            grupoNombre.classList.add('oculto'); 
            inputNombre.removeAttribute('required'); 
            grupoCaptcha.classList.add('oculto');
            inputCaptcha.removeAttribute('required');
            btnEnviarRegistro.classList.add('d-none');
            btnEnviarLogin.classList.remove('d-none');
            textoAlternar.textContent = "¿No tienes una cuenta?";
            enlaceAlternar.textContent = "Regístrate aquí";
        } else {
            formTitulo.textContent = "Regístrate";
            grupoNombre.classList.remove('oculto');
            inputNombre.setAttribute('required', 'required');
            grupoCaptcha.classList.remove('oculto');
            inputCaptcha.setAttribute('required', 'required');
            btnEnviarRegistro.classList.remove('d-none');
            btnEnviarLogin.classList.add('d-none');
            textoAlternar.textContent = "¿Ya tienes cuenta?";
            enlaceAlternar.textContent = "Inicia Sesión";
        }
    }

    enlaceAlternar.addEventListener('click', (e) => {
        e.preventDefault();
        modo = (modo === "registro") ? "login" : "registro";
        aplicarModo();
    });

    aplicarModo();
</script>

</body>
</html>