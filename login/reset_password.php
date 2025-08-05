<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="form-container">
        <h2>Recuperar Contraseña</h2>
        <form method="POST" action="../logica/procesar_reset_password.php">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <div class="g-recaptcha" data-sitekey="6LdVknMqAAAAAKWuPqraB1YnVFMvAwMJ3zsL_53_"></div>
            <button type="submit">Enviar enlace de recuperación</button>
        </form>
        <p><a href="login_principal.php">Volver al login</a></p>
    </div>
</body>
</html>
