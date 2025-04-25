<?php
session_start();
include '../../includes/conexion.php'; // Incluimos la conexión a la base de datos

// Verificar si el usuario es Administrador (id_Rol = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header("Location: ../../auth/login.php");
    exit;
}

// Mostrar todos los usuarios normales (id_Rol = 3)
$query = "SELECT * FROM usuario WHERE id_Rol = 3";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
            background-color: #007bff;
            padding: 5px 10px;
            border-radius: 4px;
        }
        a.delete {
            background-color: #dc3545;
        }
        a.edit {
            background-color: #ffc107;
        }
        .add-button {
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Gestión de Usuarios Normales</h1>

    <!-- Enlace para agregar un nuevo usuario -->
    <div class="add-button">
        <a href="crear_usuario.php">Agregar Usuario</a>
    </div>

    <!-- Tabla para mostrar usuarios -->
    <table>
        <tr>
            <th>Documento</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Documento']) ?></td>
            <td><?= htmlspecialchars($row['Nombre']) ?></td>
            <td><?= htmlspecialchars($row['Apellido']) ?></td>
            <td><?= htmlspecialchars($row['Correo']) ?></td>
            <td>
                <a class="edit" href="editar_usuario.php?documento=<?= urlencode($row['Documento']) ?>">Editar</a>
                <a class="delete" href="eliminar_usuario.php?documento=<?= urlencode($row['Documento']) ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Enlace para cerrar sesión -->
    <a href="../../auth/logout.php">Cerrar Sesión</a>
</body>
</html>