<?php
require_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $recaptcha = $_POST['g-recaptcha-response'] ?? '';

    // Valida RECAPTCHA
    $secretKey = '6LdVknMqAAAAAKge_ZujvkGawUMfYBYBLtoIWzjs';
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        die("❌ Captcha no verificado.");
    }

    if (empty($email)) {
        die("❌ El campo de correo está vacío.");
    }

    $db = new Database();
    $conn = $db->obtenerConexion();

    // Busca usuario por email
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = :email AND verificado = 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_usuario = $usuario['id_usuario'];

        // Generar y guardar token
        $token = bin2hex(random_bytes(16));
        $stmtToken = $conn->prepare("UPDATE usuarios SET token_verificacion = :token WHERE id_usuario = :id");
        $stmtToken->bindParam(':token', $token);
        $stmtToken->bindParam(':id', $id_usuario);
        $stmtToken->execute();

        // EnviaA correo
        $db->enviarCorreoRecuperacion($email, $token);

        // Registra log
        $db->registrarLog("Solicitud de recuperación de contraseña enviada", $id_usuario);

        echo "✅ Enlace de recuperación enviado.";
    } else {
        // Log como anónimo si no se encuentra el email
        $db->registrarLog("Intento de recuperación con email no registrado: $email", null);

        echo "❌ No se encontró una cuenta con ese correo electrónico.";
    }
}
