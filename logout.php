<?php
session_start();
require_once 'database/db.php';

// Registrar log antes de cerrar sesión
if (isset($_SESSION['id_usuario'])) {
    $db = new Database();
    $conn = $db->obtenerConexion();
    $db->registrarLog("Cierre de sesión", $_SESSION['id_usuario']);
}

// Destruir sesión
session_unset();
session_destroy();

// Redirigir al login
header("Location: login/login_principal.php");
exit();
