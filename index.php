<?php
session_start();
if (!isset($_SESSION['rol_id'])) {
    header('Location: auth/login.php');
    exit();
}

// Redirige según el rol
switch ($_SESSION['rol_id']) {
    case 3:
        header('Location: superadmin/dashboard.php');
        break;
    case 1:
        header('Location: admin/dashboard.php');
        break;
    case 2:
        header('Location: usuario/dashboard.php');
        break;
    default:
        echo "Rol no válido.";
        session_destroy();
        exit();
}
?>
