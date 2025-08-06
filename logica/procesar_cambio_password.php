<?php
require_once '../database/db.php';

$token = $_POST['token'] ?? '';
$nueva_password = $_POST['nueva_password'] ?? '';
$confirmar_password = $_POST['confirmar_password'] ?? '';

if (empty($token) || empty($nueva_password) || empty($confirmar_password)) {
    die("❌ Todos los campos son obligatorios.");
}

if ($nueva_password !== $confirmar_password) {
    die("❌ Las contraseñas no coinciden.");
}

$db = new Database();
$conn = $db->obtenerConexion();

// Buscar el usuario que corresponde al token
$stmtBuscar = $conn->prepare("SELECT id_usuario FROM usuarios WHERE token_verificacion = :token");
$stmtBuscar->bindParam(':token', $token);
$stmtBuscar->execute();

if ($stmtBuscar->rowCount() === 0) {
    die("❌ Token inválido o expirado.");
}

$usuario = $stmtBuscar->fetch(PDO::FETCH_ASSOC);
$id_usuario = $usuario['id_usuario'];

// Hashear y actualizar contraseña
$passwordHash = password_hash($nueva_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE usuarios SET password = :password, token_verificacion = NULL WHERE id_usuario = :id");
$stmt->bindParam(':password', $passwordHash);
$stmt->bindParam(':id', $id_usuario);

if ($stmt->execute()) {
    // Registrar log
    $db->registrarLog("Cambio de contraseña exitoso", $id_usuario);

    echo "<script>
        alert('✅ Contraseña actualizada con éxito');
        window.location.href = '../login/login_principal.php';
    </script>";
    exit();
} else {
    echo "❌ Error al actualizar la contraseña.";
}
?>

