<?php
function verificarSesion() {
    if (!isset($_SESSION['id_usuario'])) {
        header('Location: ../auth/login.php');
        exit();
    }
}

function verificarRol($rolPermitido) {
    if ($_SESSION['rol_id'] != $rolPermitido) {
        header('Location: ../index.php');
        exit();
    }
}
?>
