<?php
// Iniciamos la sesión
session_start();

// Destruimos todas las variables de sesión
session_destroy();

// Redirigimos al usuario al formulario de inicio de sesión
header("Location: login.php");
exit;
?>