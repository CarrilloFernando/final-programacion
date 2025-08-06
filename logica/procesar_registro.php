<?php
require_once '../database/db.php'; 

// Muestra errores si los hay
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $recaptcha = $_POST['g-recaptcha-response'] ?? '';

    // Validar RECAPTCHA
    $secret = '6LdVknMqAAAAAKge_ZujvkGawUMfYBYBLtoIWzjs';
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptcha");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        die("❌ Captcha no verificado.");
    }

    if (empty($nombre_usuario) || empty($apellido) || empty($email) || empty($password)) {
        die("❌ Todos los campos son obligatorios.");
    }

    // Crea objeto y llamar función de inserción
    $db = new Database();
    $conn = $db->obtenerConexion();

    $resultado = $db->insertarUsuario($nombre_usuario, $apellido, $email, $password);

    // Registra log si fue exitoso
    if ($resultado['status'] === 'success') {
        $db->registrarLog("Registro de nuevo usuario", null);
    }

    echo $resultado['message'];
}

?>