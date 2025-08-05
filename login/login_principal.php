<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Iniciar Sesión</title>
</head>

<body>
    <div class="form-container">
        <h2>FORO VIRTUAL RG</h2>
        <form id="loginForm" method="POST" action="../logica/procesar_login.php">
            <input type="text" id="usernameOrEmail" name="usernameOrEmail" placeholder="Nombre Usuario o email" required>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <div class="capt">
                <div class="captcha-container">
                    <div class="g-recaptcha" data-sitekey="6LdVknMqAAAAAKWuPqraB1YnVFMvAwMJ3zsL_53_"></div>
                </div>
            </div>

            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate</a></p>
        <p><a href="reset_password.php">¿Olvidaste tu contraseña?</a></p>
        
    </div>
    
</body>

</html>