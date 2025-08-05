<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barra de Navegación Fija</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- Barra de Navegación -->
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.php">Inicio</a>
        </div>

        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
        </div>

        <!-- Botones de Crear y Notificaciones -->
        <div class="nav-links">
            <div class="dropdown">
                <button class="icon">Crear +</button>
                <div class="dropdown-content">
                    <a href="crear_post.php">Crear Post</a>
                    <a href="crear_difusion.php">Crear Difusión</a>
                </div>
            </div>

            <div class="icon">🔔 Notificaciones</div>


        </div>

        <!-- Menú de perfil -->
        <div class="profile-menu">
            <div class="dropdown">
                <button class="icon">Perfil</button>
                <div class="dropdown-content">
                    <a href="perfil_usuario.php">Entrar al Perfil</a>
                    <a href="#">Configuración</a>
                    <a href="#">Cerrar Sesión</a>

                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="content">
        <h1>Bienvenido al foro vitual</h1>

    </div>

</body>

</html>