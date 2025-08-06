<?php
require_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $db = new Database();
    $conn = $db->obtenerConexion();

    // Busca si el email ya existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $existe = $stmt->fetchColumn();

    // Responde en formato JSON
    header('Content-Type: application/json');
    if ($existe > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
    exit();
} else {
    // Si no es una solicitud válida
    http_response_code(400);
    echo json_encode(['error' => 'Solicitud no válida']);
}

?>