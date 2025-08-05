<?php
require_once '../database/db.php'; // Ajustado a nueva ubicación

// Opcional: Permitir llamadas externas si se quiere usar con fetch/AJAX
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$db = new Database();
$db_con = $db->obtenerConexion();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Buscar usuario con ese token y no verificado
    $query = "SELECT id_usuario FROM usuarios WHERE token_verificacion = :token AND verificado = 0";
    $stmt = $db_con->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Actualizar verificado, fecha y estado
        $queryUpdate = "UPDATE usuarios SET verificado = 1, fecha_verificacion = NOW(), id_estado = 1, token_verificacion = NULL WHERE token_verificacion = :token";
        $stmtUpdate = $db_con->prepare($queryUpdate);
        $stmtUpdate->bindParam(':token', $token);

        if ($stmtUpdate->execute()) {
            // Redirigir al login principal
            header("Location: http://localhost/finalprogramacion/login/login_principal.php");
            exit();
        } else {
            echo "❌ Error al actualizar el estado del usuario.";
        }
    } else {
        echo "❌ Token no válido o cuenta ya verificada.";
    }
} else {
    echo "❌ Token no proporcionado.";
}
