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
$stmt = $conn->prepare("SELECT u.id_usuario, u.nombre_usuario, u.apellido, u.email, r.nombre_rol, e.nombre_estado FROM usuarios u INNER JOIN roles r ON u.id_rol = r.id_rol INNER JOIN estado_usuario e ON u.id_estado = e.id_estado");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rol = $_SESSION['rol'];
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
    <script>
        window.onload = function () {
            const mensaje = "<?php echo $mensaje; ?>";
            if (mensaje === 'eliminado_ok') {
                alert("✅ Usuario eliminado correctamente.");
            } else if (mensaje === 'ya_eliminado') {
                alert("⚠️ El usuario ya está eliminado.");
            } else if (mensaje === 'no_encontrado') {
                alert("❌ Usuario no encontrado.");
            } else if (mensaje === 'error') {
                alert("❌ Error al eliminar el usuario.");
            }
        };
    </script>
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
                    <a href="../persona.php">👥 Ver perfil</a>
                    <a href="../perfil.php">✏️ Editar Perfil</a>
                    <a href="../logout.php">🚪 Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="contenido">
        <h2>Administración de Usuarios</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre_rol']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre_estado']); ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>">✏️ Editar</a>
                            <a href="eliminar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
