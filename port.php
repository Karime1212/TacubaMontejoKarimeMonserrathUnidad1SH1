<?php
session_start();

// Validación de sesión: si no existe la sesión del usuario, se le regresa al index
if(!isset($_SESSION['nombre'])){
    header("Location: index.php");
    exit();
}

require_once 'bdconexion.php';

try {
    // Consulta para obtener todos los registros de la tabla usuarixs
    $lista = $cnnPDO->query("SELECT * FROM usuarixs");
} catch (PDOException $e) {
    echo "Error al consultar los datos: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Hashes - SecureAuth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="estilos.css?v=<?php echo time(); ?>">
    <script>
        // Evitar que el usuario regrese con el botón "Atrás" del navegador después de cerrar sesión
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
</head>
<body style="background: #d9d9d9; min-height: 100vh;">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4" style="background: linear-gradient(to right, #111827, #1e3a5f) !important;">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold text-white">
            <i class="fa-solid fa-shield-halved text-success me-2"></i> Hashes Seguros e Inseguros
        </span>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-inline">
                <i class="fa-solid fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?>
            </span>
            <a href="logout.php" class="btn btn-light fw-bold text-dark px-3" style="border-radius: 10px; text-decoration: none;">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Salir
            </a>
        </div>
    </div>
</nav>

<div class="container my-5">
    
    <div class="alert alert-dark text-center shadow-sm p-4 mb-4" style="background: #252934; border: none; border-radius: 20px;">
        <h2 class="text-white fw-bold mb-0">
            <i class="fa-solid fa-hand-wave text-warning me-2"></i> ¡Bienvenido(a) de nuevo, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!
        </h2>
        <p class="text-muted mt-2 mb-0 small">Demostración académica del comportamiento criptográfico de almacenamiento en bases de datos.</p>
    </div>

    <div class="card shadow-sm border-0 p-4 bg-white" style="border-radius: 20px;">
        <h3 class="mb-4 text-dark fw-bold d-flex align-items-center">
            <i class="fa-solid fa-database text-success me-2"></i> Registros de la Tabla `usuarixs`
        </h3>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 text-center" style="border-top-left-radius: 10px;">Nombre</th>
                        <th class="py-3 text-center">Correo Electrónico</th>
                        <th class="py-3 text-center">Inseguro (SHA256 - Fijo)</th>
                        <th class="py-3 text-center" style="border-top-right-radius: 10px;">Seguro (Bcrypt - Dinámico)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hay_registros = false;
                    while($row = $lista->fetch(PDO::FETCH_ASSOC)){ 
                        $hay_registros = true;
                    ?>
                    <tr>
                        <td class="fw-bold text-secondary text-center">
                            <?php echo htmlspecialchars($row['nombre']); ?>
                        </td>
                        <td class="text-muted text-center">
                            <?php echo htmlspecialchars($row['correo']); ?>
                        </td>
                        <td class="font-monospace text-break small p-2" style="max-width: 200px; color: #b22222; background-color: #fff5f5;">
                            <?php echo htmlspecialchars($row['sha']); ?>
                        </td>
                        <td class="font-monospace text-break small p-2 fw-bold" style="max-width: 300px; color: #1e7e34; background-color: #f4fbf7;">
                            <?php echo htmlspecialchars($row['bcrypt']); ?>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(!$hay_registros){ ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No se encontraron usuarios registrados en la base de datos.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>