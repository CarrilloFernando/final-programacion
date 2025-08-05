<?php
session_start();
require_once '../database/db.php';

// Mostrar errores (opcional para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usernameOrEmail'] ?? '';
    $password = $_POST['password'] ?? '';
    $recaptcha = $_POST['g-recaptcha-response'] ?? '';

    // Validar reCAPTCHA
    $secretKey = '6LdVknMqAAAAAKge_ZujvkGawUMfYBYBLtoIWzjs'; // Reemplaza por tu clave secreta real
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        die("❌ Captcha no verificado.");
    }

    // Conectarse y validar usuario
    $db = new Database();
    $conn = $db->obtenerConexion();

    $resultado = $db->validarUsuario($usuario, $password);

    if ($resultado['status'] === "success") {
        // Guardar datos en sesión
        $_SESSION['id_usuario'] = $resultado['user']['id_usuario'];
        $_SESSION['nombre_usuario'] = $resultado['user']['nombre_usuario'];
        $_SESSION['rol'] = $resultado['user']['id_rol'];

        // Redirigir al dashboard o inicio
        header("Location: ../index.php");
        exit();
    } else {
        echo "❌ " . $resultado['message'];
    }
}
?>
