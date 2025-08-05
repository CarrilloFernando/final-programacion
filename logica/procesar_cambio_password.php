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

$passwordHash = password_hash($nueva_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE usuarios SET password = :password, token_verificacion = NULL WHERE token_verificacion = :token");
$stmt->bindParam(':password', $passwordHash);
$stmt->bindParam(':token', $token);

if ($stmt->execute()) {
    echo "✅ Contraseña actualizada con éxito. <a href='../login/login_principal.php'>Iniciar sesión</a>";
} else {
    echo "❌ Error al actualizar la contraseña.";
}
