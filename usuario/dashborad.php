<?php
session_start();
include '../../includes/conexion.php'; // Incluimos la conexión a la base de datos

// Verificar si el usuario es un Usuario Normal (id_Rol = 3)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 3) {
    header("Location: ../../auth/login.php");
    exit;
}

// Obtener el nombre del usuario actual
$documento_usuario = $_SESSION['user_id'];
$query_usuario = "SELECT Nombre, Apellido FROM usuario WHERE Documento = ?";
$stmt_usuario = $conn->prepare($query_usuario);
$stmt_usuario->bind_param("s", $documento_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($result_usuario->num_rows > 0) {
    $usuario = $result_usuario->fetch_assoc();
    $nombre_completo = htmlspecialchars($usuario['Nombre'] . ' ' . $usuario['Apellido']);
} else {
    $nombre_completo = "Usuario";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 15px;
            border-radius: 4px;
            display: inline-block;
        }
        a.logout {
            background-color: #6c757d;
        }
        a:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Bienvenido, <?= $nombre_completo ?></h1>

    <!-- Menú de opciones -->
    <ul>
        <li><a href="album.php">Mi Álbum Virtual</a></li>
        <li><a href="escanear_carta.php">Escanea una Carta</a></li>
        <li><a class="logout" href="../../auth/logout.php">Cerrar Sesión</a></li>
    </ul>
</body>
</html>