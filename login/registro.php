<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Registro de Usuario</h2>
        <form method="POST" action="../logica/procesar_registro.php">
            
            <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre de Usuario" required>
            
            <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>

            <input type="email" id="email" name="email" placeholder="Email" required>
            
            
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            
            <div class="capt">
                <div class="captcha-container">
                    <div class="g-recaptcha" data-sitekey="6LdVknMqAAAAAKWuPqraB1YnVFMvAwMJ3zsL_53_"></div>
                </div>
            </div>

            <button type="submit">Registrar Usuario</button> <br></br>
            <a href="login_principal.php">¿Ya tienes cuenta?</a>
        </form>
    </div>

    
</body>
</html>

