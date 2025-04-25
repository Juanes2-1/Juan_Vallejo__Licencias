<?php
session_start();
include '../../includes/conexion.php'; // Incluimos la conexión a la base de datos

// Verificar si el usuario es SuperAdministrador (id_Rol = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header("Location: ../../auth/login.php");
    exit;
}

// Manejar las acciones del CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'crear') {
        // Crear una nueva empresa
        $nit = $_POST['nit'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $query = "INSERT INTO empresa (Nit, Nombre, Direccion, Telefono) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nit, $nombre, $direccion, $telefono);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Empresa creada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al crear la empresa: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'editar') {
        // Editar una empresa existente
        $nit = $_POST['nit'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $query = "UPDATE empresa SET Nombre = ?, Direccion = ?, Telefono = ? WHERE Nit = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nombre, $direccion, $telefono, $nit);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Empresa actualizada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al actualizar la empresa: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'eliminar') {
        // Eliminar una empresa
        $nit = $_POST['nit'];

        $query = "DELETE FROM empresa WHERE Nit = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nit);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Empresa eliminada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al eliminar la empresa: " . $stmt->error . "</p>";
        }
    }
}

// Consulta para obtener todas las empresas
$query = "SELECT * FROM empresa";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empresas</title>
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
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            opacity: 0.9;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <h1>Gestión de Empresas</h1>

    <!-- Formulario para Crear/Editar Empresa -->
    <?php if (isset($_GET['editar'])): ?>
        <?php
        // Obtener los datos de la empresa a editar
        $nit_editar = $_GET['editar'];
        $query_editar = "SELECT * FROM empresa WHERE Nit = ?";
        $stmt_editar = $conn->prepare($query_editar);
        $stmt_editar->bind_param("s", $nit_editar);
        $stmt_editar->execute();
        $result_editar = $stmt_editar->get_result();
        $empresa_editar = $result_editar->fetch_assoc();
        ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="nit" value="<?= htmlspecialchars($empresa_editar['Nit']) ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($empresa_editar['Nombre']) ?>" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($empresa_editar['Direccion']) ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($empresa_editar['Telefono']) ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="crear">

            <label for="nit">NIT:</label>
            <input type="text" id="nit" name="nit" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>

            <button type="submit">Crear Empresa</button>
        </form>
    <?php endif; ?>

    <!-- Tabla para mostrar empresas -->
    <table>
        <tr>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Nit']) ?></td>
            <td><?= htmlspecialchars($row['Nombre']) ?></td>
            <td><?= htmlspecialchars($row['Direccion']) ?></td>
            <td><?= htmlspecialchars($row['Telefono']) ?></td>
            <td class="action-buttons">
                <a href="?editar=<?= urlencode($row['Nit']) ?>">Editar</a>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="nit" value="<?= htmlspecialchars($row['Nit']) ?>">
                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta empresa?')">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Enlace para regresar al dashboard -->
    <a href="dashboard.php">Regresar al Dashboard</a>
</body>
</html>