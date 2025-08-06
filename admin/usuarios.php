<?php
session_start();

// Validación de sesión y rol
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header("Location: ../login/login_principal.php");
    exit();
}

require_once '../database/db.php';
$db = new Database();
$conn = $db->obtenerConexion();

// Obtener todos los usuarios
$stmt = $conn->prepare("SELECT u.id_usuario, u.nombre_usuario, u.apellido, u.email, r.nombre_rol, e.nombre_estado 
                        FROM usuarios u
                        JOIN roles r ON u.id_rol = r.id_rol
                        JOIN estado_usuario e ON u.id_estado = e.id_estado");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>👥 Administración de Usuarios</h2>
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
                        <td><?= $usuario['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= $usuario['nombre_rol'] ?></td>
                        <td><?= $usuario['nombre_estado'] ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?= $usuario['id_usuario'] ?>">✏️ Editar</a> |
                            <a href="eliminar_usuario.php?id=<?= $usuario['id_usuario'] ?>" onclick="return confirm('¿Eliminar usuario?')">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="../index.php">⬅ Volver al Dashboard</a>
    </div>
</body>
</html>
