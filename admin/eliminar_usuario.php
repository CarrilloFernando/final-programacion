<?php
// eliminar_usuario.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
    header("Location: ../login/login_principal.php");
    exit();
}
require_once '../database/db.php';

if (!isset($_GET['id'])) {
    header("Location: usuarios.php?mensaje=id_faltante");
    exit();
}

$db = new Database();
$conn = $db->obtenerConexion();
$id = $_GET['id'];

// Verificar si el usuario existe
$stmtCheck = $conn->prepare("SELECT id_estado FROM usuarios WHERE id_usuario = :id");
$stmtCheck->bindParam(':id', $id);
$stmtCheck->execute();

if ($stmtCheck->rowCount() == 0) {
    header("Location: usuarios.php?mensaje=no_encontrado");
    exit();
}

$usuario = $stmtCheck->fetch(PDO::FETCH_ASSOC);
if ($usuario['id_estado'] == 2) {
    header("Location: usuarios.php?mensaje=ya_eliminado");
    exit();
}

// Cambiar el estado del usuario a 'Eliminado' (id_estado = 2)
$stmt = $conn->prepare("UPDATE usuarios SET id_estado = 2 WHERE id_usuario = :id");
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    header("Location: usuarios.php?mensaje=eliminado_ok");
    exit();
} else {
    header("Location: usuarios.php?mensaje=error");
    exit();
}
