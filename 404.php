<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página No Encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body style="background: #d9d9d9; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;">

<div class="principal text-center">

    <div class="caja p-5 text-white">
        <div class="mb-4">
            <i class="fa-solid fa-triangle-exclamation display-1" style="color: #0ac18e;"></i>
        </div>
        
        <h1 class="fw-bold mb-2" style="font-size: 60px; color: white;">404</h1>
        <h2 class="h4 mb-4 text-muted text-uppercase tracking-wider">Página No Encontrada</h2>
        
        <div class="linea"></div>
        
        <p class="mb-5 text-light" style="font-size: 16px; line-height: 1.6;">
            Lo sentimos, el recurso al que intentas acceder no está disponible, 
            ha sido movido o no se encuentra en el mapa de navegación del sitio.
        </p>
        
        <div class="botones">
            <a href="index.php" class="btnreg d-flex align-items-center justify-content-center text-decoration-none">
                <i class="fa-solid fa-arrow-left me-2"></i> Volver al Inicio
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>