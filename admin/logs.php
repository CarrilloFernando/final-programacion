<?php
// logs.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header("Location: ../login/login_principal.php");
    exit();
}
require_once '../database/db.php';

$db = new Database();
$conn = $db->obtenerConexion();

$rol = $_SESSION['rol'];

// Obtener logs con nombre de usuario si existe
$stmt = $conn->prepare("SELECT l.*, u.nombre_usuario 
                        FROM logs l 
                        LEFT JOIN usuarios u ON l.id_usuario = u.id_usuario 
                        ORDER BY l.fecha_hora DESC");
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Logs del Sistema</title>
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
                <a href="../persona.php">👥 Ver perfil</a>
                <a href="../perfil.php">✏️ Editar Perfil</a>
                <a href="../logout.php">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>

<main class="contenido">
    <h2>Registros de Auditoría</h2>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Fecha y Hora</th>
                <th>IP Origen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['id_log'] ?></td>
                    <td><?= $log['nombre_usuario'] ?? 'Anónimo' ?></td>
                    <td><?= htmlspecialchars($log['accion']) ?></td>
                    <td><?= $log['fecha_hora'] ?></td>
                    <td><?= $log['ip_origen'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
