<?php


require_once '../database/db.php';
$db = new Database();
$conn = $db->obtenerConexion();

// Obtener logs
$stmt = $conn->prepare("SELECT l.id_log, u.nombre_usuario, l.accion, l.fecha_hora, l.ip_origen
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
    <title>Ver Logs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>📋 Registros de Auditoría</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Log</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Fecha</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $log['id_log'] ?></td>
                        <td><?= htmlspecialchars($log['nombre_usuario'] ?? 'Desconocido') ?></td>
                        <td><?= $log['accion'] ?></td>
                        <td><?= $log['fecha_hora'] ?></td>
                        <td><?= $log['ip_origen'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="../index.php">⬅ Volver al Dashboard</a>
    </div>
</body>
</html>


