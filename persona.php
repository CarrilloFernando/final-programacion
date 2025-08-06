<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login/login_principal.php");
    exit();
}

require_once 'database/db.php';

$db = new Database();
$conn = $db->obtenerConexion();

$id = $_SESSION['id_usuario'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="index.php">🏠 Inicio</a>
            <?php if ($_SESSION['rol'] == 1): ?>
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

    <main class="perfil-container">
        <h2>Mi Perfil</h2>
        <div class="perfil-info"><strong>Nombre de Usuario:</strong> <?= htmlspecialchars($usuario['nombre_usuario']) ?></div>
        <div class="perfil-info"><strong>Apellido:</strong> <?= htmlspecialchars($usuario['apellido']) ?></div>
        <div class="perfil-info"><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></div>
        <div class="perfil-info"><strong>Rol:</strong> <?= $usuario['id_rol'] == 1 ? 'Administrador' : 'Usuario' ?></div>
        <div class="perfil-info"><strong>Estado:</strong> <?php
            $estados = [1 => 'Habilitado', 2 => 'Eliminado', 3 => 'Suspendido', 4 => 'Pendiente'];
            echo $estados[$usuario['id_estado']] ?? 'Desconocido';
        ?></div>
        <div class="perfil-info"><strong>Fecha de Registro:</strong> <?= $usuario['fecha_registro'] ?></div>

        <a class="btn-editar" href="perfil.php">✏️ Editar Perfil</a>
    </main>
</body>
</html>
