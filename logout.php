<?php
session_start();
session_unset();
session_destroy();

// Evitar que el navegador vuelva a páginas protegidas
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

header("Location: login/login_principal.php");
exit();

