<?php
session_start();
include '../../includes/conexion.php'; // Incluimos la conexión a la base de datos

// Verificar si el usuario es Administrador (id_Rol = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header("Location: ../../auth/login.php");
    exit;
}

// Consulta para obtener empresas y sus usuarios asociados
$query = "
    SELECT e.Nombre AS Empresa, u.Nombre AS UsuarioNombre, u.Apellido AS UsuarioApellido, u.Correo AS UsuarioCorreo
    FROM empresa e
    LEFT JOIN usuario u ON e.Nit = u.Nit
";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Empresas y Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: #6c757d;
            padding: 5px 10px;
            border-radius: 4px;
        }
        a:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Empresas y Usuarios Asociados</h1>

    <!-- Tabla para mostrar empresas y usuarios -->
    <table>
        <tr>
            <th>Empresa</th>
            <th>Nombre del Usuario</th>
            <th>Apellido del Usuario</th>
            <th>Correo del Usuario</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Empresa']) ?></td>
            <td><?= htmlspecialchars($row['UsuarioNombre']) ?: 'Sin usuario asociado' ?></td>
            <td><?= htmlspecialchars($row['UsuarioApellido']) ?: 'Sin usuario asociado' ?></td>
            <td><?= htmlspecialchars($row['UsuarioCorreo']) ?: 'Sin usuario asociado' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Enlace para regresar al dashboard -->
    <a href="dashboard.php">Regresar al Dashboard</a>
</body>
</html>