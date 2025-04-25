<?php
session_start();
include '../../includes/conexion.php'; // Incluimos la conexión a la base de datos

// Verificar si el usuario es SuperAdministrador (id_Rol = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header("Location: ../../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SuperAdministrador</title>
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
    <h1>Bienvenido, SuperAdministrador</h1>

    <!-- Menú de opciones -->
    <ul>
        <li><a href="empresas.php">Gestionar Empresas</a></li>
        <li><a href="licencias.php">Gestionar Licencias</a></li>
        <li><a href="tipos_licencias.php">Gestionar Tipos de Licencias</a></li>
        <li><a href="crud_usuarios_admin.php">Gestionar Usuarios Administrativos</a></li>
        <li><a class="logout" href="../../auth/logout.php">Cerrar Sesión</a></li>
    </ul>
</body>
</html>