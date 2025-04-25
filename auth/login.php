<?php
session_start();
include '../includes/conexion.php'; // Incluimos la conexión a la base de datos

// Verificamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contrasena = md5($_POST['contrasena']); // Mejor usar password_hash() en producción

    // Consulta para verificar las credenciales del usuario
    $query = "SELECT * FROM usuario WHERE Correo = ? AND Contrasena = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ss", $correo, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si las credenciales son correctas, obtenemos los datos del usuario
        $user = $result->fetch_assoc();

        // Iniciamos la sesión con los datos del usuario
        $_SESSION['user_id'] = $user['Documento']; // Usamos Documento como identificador único
        $_SESSION['role_id'] = $user['id_Rol']; // Guardamos el ID del rol
        $_SESSION['licencia_id'] = $user['licencia_id']; // Suponemos que hay un campo licencia_id en la tabla usuario

        // Redirigimos según el rol del usuario
        switch ($user['id_Rol']) {
            case 1: // SuperAdministrador
                header("Location: ../admin/dashboard.php");
                break;
            case 2: // Administrador
                header("Location: ../usuario/dashboard.php");
                break;
            case 3: // Usuario normal
                header("Location: ../superadmin/dashboard.php");
                break;
            default:
                echo "Rol no válido.";
                exit;
        }
    } else {
        // Si las credenciales son incorrectas, mostramos un mensaje de error
        echo "<p style='color:red;'>Credenciales incorrectas. Inténtalo de nuevo.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h1 {
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <form method="POST" action="">
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>