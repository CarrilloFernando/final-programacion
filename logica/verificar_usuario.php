<?php
require_once '../database/db.php';
header('Content-Type: application/json');

$usuario = $_POST['nombre_usuario'] ?? '';

if (empty($usuario)) {
    echo json_encode(['exists' => false]);
    exit;
}

$db = new Database();
$conn = $db->obtenerConexion();

$stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :usuario");
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();
$existe = $stmt->fetchColumn() > 0;

echo json_encode(['exists' => $existe]);
