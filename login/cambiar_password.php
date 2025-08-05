<?php
$token = $_GET['token'] ?? '';
if (empty($token)) {
    die("❌ Token inválido.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Establecer nueva contraseña</h2>
        <form method="POST" action="../logica/procesar_cambio_password.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <input type="password" name="nueva_password" placeholder="Nueva contraseña" required>
            <input type="password" name="confirmar_password" placeholder="Confirmar contraseña" required>

            <button type="submit">Actualizar contraseña</button>
        </form>
        <p><a href="login_principal.php">Volver al login</a></p>
    </div>
</body>
</html>

