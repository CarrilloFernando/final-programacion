<?php
require_once '../database/db.php';

$secretKey = '6LdVknMqAAAAAKge_ZujvkGawUMfYBYBLtoIWzjs'; // Tu clave secreta
$recaptcha = $_POST['g-recaptcha-response'] ?? '';
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha");
$responseKeys = json_decode($response, true);

if (!$responseKeys["success"]) {
    die("❌ Captcha no verificado.");
}

$email = $_POST['email'] ?? '';
if (empty($email)) {
    die("❌ Debes ingresar tu email.");
}

$db = new Database();
$conn = $db->obtenerConexion();

// Verificar si el email existe
$stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Generar token de recuperación
    $token = bin2hex(random_bytes(16));

    // Guardar el token en la BD (puede usarse una nueva columna `token_recuperacion`)
    $stmtToken = $conn->prepare("UPDATE usuarios SET token_verificacion = :token WHERE email = :email");
    $stmtToken->bindParam(':token', $token);
    $stmtToken->bindParam(':email', $email);
    $stmtToken->execute();

    // Enviar email con enlace
    $db->enviarCorreoRecuperacion($email, $token);

    echo "✅ Se ha enviado un enlace de recuperación a tu correo.";
} else {
    echo "❌ El correo no está registrado.";
}
