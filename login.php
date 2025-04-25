<?php
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Consulta SQL
    $query = "SELECT * FROM usuario WHERE Correo = '$correo' AND Contraseña = '$contraseña'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error en la consulta SQL: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        session_start();
        $_SESSION['id_usuario'] = $usuario['Documento'];
        $_SESSION['nombre_usuario'] = $usuario['Nombre'];
        $_SESSION['apellido_usuario'] = $usuario['Apellido'];
        $_SESSION['rol_usuario'] = $usuario['Fk_Id_Rol'];
        $_SESSION['empresa_usuario'] = $usuario['Fk_Id_Empresa'];

        // Redireccionar según el rol
        if ($usuario['Fk_Id_Rol'] == 3) {
            header('Location: ../superadmin/dashboard.php');
        } elseif ($usuario['Fk_Id_Rol'] == 1) {
            header('Location: ../admin/dashboard.php');
        } elseif ($usuario['Fk_Id_Rol'] == 2) {
            header('Location: ../usuario/dashboard.php');
        } else {
            echo "Rol no reconocido.";
        }
    } else {
        echo "Correo o contraseña incorrectos.";
    }
}
?>
