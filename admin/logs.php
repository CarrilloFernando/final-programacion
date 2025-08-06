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
$stmt = $conn->query("SELECT logs.id_log, usuarios.nombre_usuario, logs.accion, logs.fecha_hora, logs.ip_origen FROM logs LEFT JOIN usuarios ON logs.id_usuario = usuarios.id_usuario ORDER BY logs.fecha_hora DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Logs</title>
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
            <tr>
                <th>ID Log</th>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Fecha y Hora</th>
                <th>IP Origen</th>
            </tr>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['id_log'] ?></td>
                    <td><?= htmlspecialchars($log['nombre_usuario'] ?? 'Desconocido') ?></td>
                    <td><?= htmlspecialchars($log['accion']) ?></td>
                    <td><?= $log['fecha_hora'] ?></td>
                    <td><?= $log['ip_origen'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
