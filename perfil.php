<?php
session_start();

// Protección de sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login/login_principal.php");
    exit();
}


// Cargar datos del usuario
$id_usuario = $_SESSION['id_usuario'];
$nombre_usuario = $_SESSION['nombre_usuario'];
$rol = $_SESSION['rol'];

// Requiere el archivo de conexión y obtiene los datos actualizados
require_once 'database/db.php';
$db = new Database();
$conn = $db->obtenerConexion();

// Obtener datos actuales del usuario
$stmt = $conn->prepare("SELECT nombre_usuario, apellido, email FROM usuarios WHERE id_usuario = :id");
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

// Variables para mostrar mensajes
$mensaje = "";
$error = "";

// Actualización de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = $_POST['nombre_usuario'];
    $nuevo_apellido = $_POST['apellido'];
    $nuevo_email = $_POST['email'];

    // Validación simple
    if (!empty($nuevo_nombre) && !empty($nuevo_apellido) && !empty($nuevo_email)) {
        $stmtUpdate = $conn->prepare("UPDATE usuarios SET nombre_usuario = :nombre, apellido = :apellido, email = :email, fecha_modificacion = NOW() WHERE id_usuario = :id");
        $stmtUpdate->bindParam(':nombre', $nuevo_nombre);
        $stmtUpdate->bindParam(':apellido', $nuevo_apellido);
        $stmtUpdate->bindParam(':email', $nuevo_email);
        $stmtUpdate->bindParam(':id', $id_usuario);

        if ($stmtUpdate->execute()) {
            $mensaje = "✅ Datos actualizados correctamente.";
            $_SESSION['nombre_usuario'] = $nuevo_nombre; // actualizar sesión
            // recargar datos
            $datos = [
                "nombre_usuario" => $nuevo_nombre,
                "apellido" => $nuevo_apellido,
                "email" => $nuevo_email
            ];
        } else {
            $error = "❌ Error al actualizar los datos.";
        }
    } else {
        $error = "❌ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="form-container">
    <h2>🧑 Mi Perfil</h2>

    <?php if ($mensaje): ?>
        <p style="color: green;"><?= $mensaje ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($datos['nombre_usuario']) ?>" placeholder="Nombre" required>
        <input type="text" name="apellido" value="<?= htmlspecialchars($datos['apellido']) ?>" placeholder="Apellido" required>
        <input type="email" name="email" value="<?= htmlspecialchars($datos['email']) ?>" placeholder="Email" required>

        <button type="submit">Actualizar Datos</button>
    </form>

    <br>
    <a href="index.php">⬅ Volver al Dashboard</a>
</div>
</body>
</html>
