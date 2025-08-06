<?php
// perfil.php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login/login_principal.php");
    exit();
}

require_once 'database/db.php';

$db = new Database();
$conn = $db->obtenerConexion();

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

// Obtener datos del usuario actual
$stmt = $conn->prepare("SELECT nombre_usuario, apellido, email FROM usuarios WHERE id_usuario = :id");
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "<script>alert('Usuario no encontrado.'); window.location.href = 'index.php';</script>";
    exit();
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $apellido = $_POST['apellido'];

    $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = :nombre_usuario, apellido = :apellido WHERE id_usuario = :id");
    $stmt->bindParam(':nombre_usuario', $nombre_usuario);
    $stmt->bindParam(':apellido', $apellido);
    
    $stmt->bindParam(':id', $id_usuario);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Perfil actualizado correctamente'); window.location.href = 'perfil.php';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Error al actualizar perfil');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
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

    <main class="contenido">
        <h2>Perfil de Usuario</h2>
        <form method="POST" class="formulario-editar">
            <label>Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

            
            <button type="submit">Actualizar Perfil</button>
        </form>
    </main>
</body>
</html>
