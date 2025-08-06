<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header("Location: ../login/login_principal.php");
    exit();
}

require_once '../database/db.php';

$db = new Database();
$conn = $db->obtenerConexion();

$rol = $_SESSION['rol'];
$busqueda = $_GET['busqueda'] ?? '';

// Registra log de visualización
$db->registrarLog("Visualización de página de logs", $_SESSION['id_usuario']);

// Consulta con filtro por búsqueda
$sql = "SELECT l.*, u.nombre_usuario 
        FROM logs l 
        LEFT JOIN usuarios u ON l.id_usuario = u.id_usuario 
        WHERE u.nombre_usuario LIKE :busqueda 
        OR l.accion LIKE :busqueda 
        ORDER BY l.fecha_hora DESC";

$stmt = $conn->prepare($sql);
$param = "%" . $busqueda . "%";
$stmt->bindParam(':busqueda', $param);
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

    <!-- Formulario de búsqueda -->
    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="busqueda" placeholder="Buscar por usuario o acción" value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit">🔍 Buscar</button>
        <?php if (!empty($busqueda)): ?>
            <a href="logs.php" style="margin-left: 10px;">❌ Limpiar</a>
        <?php endif; ?>
    </form>

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
            <?php if (count($logs) > 0): ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $log['id_log'] ?></td>
                        <td><?= $log['nombre_usuario'] ?? 'Anónimo' ?></td>
                        <td><?= htmlspecialchars($log['accion']) ?></td>
                        <td><?= $log['fecha_hora'] ?></td>
                        <td><?= $log['ip_origen'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No se encontraron resultados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>

