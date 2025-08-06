<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login/login_principal.php");
    exit();
}

$nombre = $_SESSION['nombre_usuario'];
$rol = $_SESSION['rol']; // 1 = admin, 2 = usuario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Foro Virtual</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>

    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="index.php">🏠 Inicio</a>
            <?php if ($rol == 1): ?>
                <div class="dropdown">
                    <button class="dropbtn">🔧 Admin</button>
                    <div class="dropdown-content">
                        <a href="admin/usuarios.php">👥 Usuarios</a>
                        <a href="admin/logs.php">📋 Logs</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="nav-right">
            <div class="dropdown">
                <button class="dropbtn">👤 Perfil</button>
                <div class="dropdown-content">
                    <a href="persona.php">👥 Ver perfil</a>
                    <a href="perfil.php">✏️ Editar Perfil</a>
                    <a href="logout.php">🚪 Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="contenido">
        <h1>Bienvenido/a, <?= htmlspecialchars($nombre) ?> 👋</h1>
        
    </main>

</body>
</html>
