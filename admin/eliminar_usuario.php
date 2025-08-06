<?php
// eliminar_usuario.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header("Location: ../login/login_principal.php");
    exit();
}
require_once '../database/db.php';

if (!isset($_GET['id'])) {
    echo "ID no proporcionado.";
    exit();
}

$db = new Database();
$conn = $db->obtenerConexion();
$id = $_GET['id'];

// Cambiar el estado del usuario a 'Eliminado' (id_estado = 2)
$stmt = $conn->prepare("UPDATE usuarios SET id_estado = 2 WHERE id_usuario = :id");
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    header("Location: usuarios.php");
    exit();
} else {
    echo "Error al eliminar el usuario.";
}
