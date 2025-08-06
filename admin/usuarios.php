<?php
// usuarios.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header("Location: ../login/login_principal.php");
    exit();
}
require_once '../database/db.php';

$db = new Database();
$conn = $db->obtenerConexion();

// Obtener usuarios
$stmt = $conn->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administración de Usuarios</title>
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
        <h2>Administración de Usuarios</h2>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Nombre Usuario</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id_usuario'] ?></td>
                    <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= $usuario['id_rol'] == 1 ? 'Admin' : 'Usuario' ?></td>
                    <td>
                        <?php
                        $estados = [1 => 'Habilitado', 2 => 'Eliminado', 3 => 'Suspendido', 4 => 'Pendiente'];
                        echo $estados[$usuario['id_estado']] ?? 'Desconocido';
                        ?>
                    </td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $usuario['id_usuario'] ?>">✏️ Editar</a> |
                        <a href="eliminar_usuario.php?id=<?= $usuario['id_usuario'] ?>" onclick="return confirm('¿Estás seguro que deseas eliminar este usuario?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>

</html>