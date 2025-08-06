<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="styles.css">

    <style>
        .mensaje {
            font-size: 0.9em;
            margin-top: -8px;
            margin-bottom: 10px;
        }
        .disponible { color: green; }
        .no-disponible { color: red; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Usuario</h2>
        <form method="POST" action="../logica/procesar_registro.php">
            
            <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre de Usuario" required>
            <div id="mensaje-usuario" class="mensaje"></div>

            <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>

            <input type="email" id="email" name="email" placeholder="Email" required>
            <div id="mensaje-email" class="mensaje"></div>

            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            
            <div class="capt">
                <div class="captcha-container">
                    <div class="g-recaptcha" data-sitekey="6LdVknMqAAAAAKWuPqraB1YnVFMvAwMJ3zsL_53_"></div>
                </div>
            </div>

            <button type="submit">Registrar Usuario</button> <br><br>
            <a href="login_principal.php">¿Ya tienes cuenta?</a>
        </form>
    </div>

    <script>
        // Validación de email
        document.getElementById('email').addEventListener('blur', function () {
            const email = this.value;
            const mensajeDiv = document.getElementById('mensaje-email');

            if (email.trim() === '') {
                mensajeDiv.textContent = '';
                return;
            }

            const formData = new FormData();
            formData.append('email', email);

            fetch('../logica/verificar_email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    mensajeDiv.textContent = ' Este correo ya está registrado.';
                    mensajeDiv.className = 'mensaje no-disponible';
                } else {
                    mensajeDiv.textContent = ' Correo disponible.';
                    mensajeDiv.className = 'mensaje disponible';
                }
            })
            .catch(() => {
                mensajeDiv.textContent = ' Error al verificar el correo.';
                mensajeDiv.className = 'mensaje no-disponible';
            });
        });

        // Validación de nombre de usuario
        document.getElementById('nombre_usuario').addEventListener('blur', function () {
            const usuario = this.value;
            const mensajeDiv = document.getElementById('mensaje-usuario');

            if (usuario.trim() === '') {
                mensajeDiv.textContent = '';
                return;
            }

            const formData = new FormData();
            formData.append('nombre_usuario', usuario);

            fetch('../logica/verificar_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    mensajeDiv.textContent = ' Este nombre de usuario ya está en uso.';
                    mensajeDiv.className = 'mensaje no-disponible';
                } else {
                    mensajeDiv.textContent = ' Nombre de usuario disponible.';
                    mensajeDiv.className = 'mensaje disponible';
                }
            })
            .catch(() => {
                mensajeDiv.textContent = ' Error al verificar el nombre de usuario.';
                mensajeDiv.className = 'mensaje no-disponible';
            });
        });
    </script>
</body>
</html>
