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
        // Crear un nuevo tipo de licencia
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        $query = "INSERT INTO tipo (nombre, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nombre, $descripcion);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Tipo de licencia creado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al crear el tipo de licencia: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'editar') {
        // Editar un tipo de licencia existente
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        $query = "UPDATE tipo SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Tipo de licencia actualizado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al actualizar el tipo de licencia: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'eliminar') {
        // Eliminar un tipo de licencia
        $id = $_POST['id'];

        $query = "DELETE FROM tipo WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Tipo de licencia eliminado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al eliminar el tipo de licencia: " . $stmt->error . "</p>";
        }
    }
}

// Consulta para obtener todos los tipos de licencias
$query = "SELECT * FROM tipo";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Licencias</title>
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
    <h1>Gestión de Tipos de Licencias</h1>

    <!-- Formulario para Crear/Editar Tipo de Licencia -->
    <?php if (isset($_GET['editar'])): ?>
        <?php
        // Obtener los datos del tipo de licencia a editar
        $id_editar = $_GET['editar'];
        $query_editar = "SELECT * FROM tipo WHERE id = ?";
        $stmt_editar = $conn->prepare($query_editar);
        $stmt_editar->bind_param("i", $id_editar);
        $stmt_editar->execute();
        $result_editar = $stmt_editar->get_result();
        $tipo_editar = $result_editar->fetch_assoc();
        ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" value="<?= htmlspecialchars($tipo_editar['id']) ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($tipo_editar['nombre']) ?>" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" value="<?= htmlspecialchars($tipo_editar['descripcion']) ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="crear">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required>

            <button type="submit">Crear Tipo de Licencia</button>
        </form>
    <?php endif; ?>

    <!-- Tabla para mostrar tipos de licencias -->
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['descripcion']) ?></td>
            <td class="action-buttons">
                <a href="?editar=<?= urlencode($row['id']) ?>">Editar</a>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este tipo de licencia?')">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Enlace para regresar al dashboard -->
    <a href="dashboard.php">Regresar al Dashboard</a>
</body>
</html>