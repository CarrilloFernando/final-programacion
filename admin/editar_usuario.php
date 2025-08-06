<?php
// editar_usuario.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header("Location: ../login/login_principal.php");
    exit();
}
require_once '../database/db.php';

$db = new Database();
$conn = $db->obtenerConexion();

if (!isset($_GET['id'])) {
    echo "ID no proporcionado.";
    exit();
}

$id = $_GET['id'];

// Obtener datos actuales del usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

// Procesar formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $id_rol = $_POST['id_rol'];
    $id_estado = $_POST['id_estado'];

    $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = :nombre_usuario, apellido = :apellido, email = :email, id_rol = :id_rol, id_estado = :id_estado WHERE id_usuario = :id");
    $stmt->bindParam(':nombre_usuario', $nombre_usuario);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_rol', $id_rol);
    $stmt->bindParam(':id_estado', $id_estado);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el usuario.";
    }
}

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="../index.php">🏠 Inicio</a>
            <?php if ($rol == 1): ?>
                <div class="dropdown">
                    <button class="dropbtn">🔧 Admin</button>
                    <div class="dropdown-content">
                        <a href="usuarios.php">👥 Usuarios</a>
                        <a href="logs.php">📋 Logs</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="nav-right">
            <div class="dropdown">
                <button class="dropbtn">👤 Perfil</button>
                <div class="dropdown-content">
                    <a href="../perfil.php">✏️ Editar Perfil</a>
                    <a href="../logout.php">🚪 Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="contenido">
        <h2>Editar Usuario</h2>
        <form method="POST">
            <label>Nombre de Usuario:</label><br>
            <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required><br>

            <label>Apellido:</label><br>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br>

            <label>Rol:</label><br>
            <select name="id_rol">
                <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
            </select><br>

            <label>Estado:</label><br>
            <select name="id_estado">
                <option value="1" <?= $usuario['id_estado'] == 1 ? 'selected' : '' ?>>Habilitado</option>
                <option value="2" <?= $usuario['id_estado'] == 2 ? 'selected' : '' ?>>Eliminado</option>
                <option value="3" <?= $usuario['id_estado'] == 3 ? 'selected' : '' ?>>Suspendido</option>
                <option value="4" <?= $usuario['id_estado'] == 4 ? 'selected' : '' ?>>Pendiente</option>
            </select><br><br>

            <button type="submit">Guardar Cambios</button>
        </form>
    </main>
</body>
</html>

