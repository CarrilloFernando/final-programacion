<?php
session_start();

// Redirigir al login si no hay sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login/login_principal.php");
    exit();
}

$nombre = $_SESSION['nombre_usuario'];
$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Foro Virtual</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Bienvenido/a, <?php echo htmlspecialchars($nombre); ?> 👋</h2>

        <p>Has iniciado sesión correctamente.</p>

        <hr>

        <h3>Menú</h3>
        <ul>
            <li><a href="perfil.php">🧑 Perfil</a></li>

            <?php if ($rol == 1): ?>
                <li><a href="admin/usuarios.php">👥 Administración de Usuarios</a></li>
                <li><a href="admin/logs.php">📋 Ver Logs</a></li>
            <?php endif; ?>

            <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>
</body>
</html>
