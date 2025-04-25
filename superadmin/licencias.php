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
        // Crear una nueva licencia
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $estado_id = $_POST['estado_id']; // Estado de la licencia (activo, inactivo, etc.)

        $query = "INSERT INTO licencia (nombre, descripcion, precio, estado_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $estado_id);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Licencia creada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al crear la licencia: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'editar') {
        // Editar una licencia existente
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $estado_id = $_POST['estado_id'];

        $query = "UPDATE licencia SET nombre = ?, descripcion = ?, precio = ?, estado_id = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $estado_id, $id);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Licencia actualizada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al actualizar la licencia: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'eliminar') {
        // Eliminar una licencia
        $id = $_POST['id'];

        $query = "DELETE FROM licencia WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Licencia eliminada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al eliminar la licencia: " . $stmt->error . "</p>";
        }
    }
}

// Consulta para obtener todas las licencias
$query = "
    SELECT l.id, l.nombre, l.descripcion, l.precio, e.nombre AS estado_nombre
    FROM licencia l
    JOIN estado e ON l.estado_id = e.id
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
    <title>Gestión de Licencias</title>
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
        input, select {
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
    <h1>Gestión de Licencias</h1>

    <!-- Formulario para Crear/Editar Licencia -->
    <?php if (isset($_GET['editar'])): ?>
        <?php
        // Obtener los datos de la licencia a editar
        $id_editar = $_GET['editar'];
        $query_editar = "
            SELECT l.id, l.nombre, l.descripcion, l.precio, l.estado_id, e.nombre AS estado_nombre
            FROM licencia l
            JOIN estado e ON l.estado_id = e.id
            WHERE l.id = ?
        ";
        $stmt_editar = $conn->prepare($query_editar);
        $stmt_editar->bind_param("i", $id_editar);
        $stmt_editar->execute();
        $result_editar = $stmt_editar->get_result();
        $licencia_editar = $result_editar->fetch_assoc();
        ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" value="<?= htmlspecialchars($licencia_editar['id']) ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($licencia_editar['nombre']) ?>" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" value="<?= htmlspecialchars($licencia_editar['descripcion']) ?>" required>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" value="<?= htmlspecialchars($licencia_editar['precio']) ?>" required>

            <label for="estado_id">Estado:</label>
            <select id="estado_id" name="estado_id" required>
                <?php
                // Consultar todos los estados disponibles
                $query_estados = "SELECT * FROM estado";
                $stmt_estados = $conn->prepare($query_estados);
                $stmt_estados->execute();
                $result_estados = $stmt_estados->get_result();

                while ($estado = $result_estados->fetch_assoc()):
                    $selected = ($estado['id'] == $licencia_editar['estado_id']) ? 'selected' : '';
                    echo "<option value='{$estado['id']}' $selected>{$estado['nombre']}</option>";
                endwhile;
                ?>
            </select>

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="crear">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" required>

            <label for="estado_id">Estado:</label>
            <select id="estado_id" name="estado_id" required>
                <?php
                // Consultar todos los estados disponibles
                $query_estados = "SELECT * FROM estado";
                $stmt_estados = $conn->prepare($query_estados);
                $stmt_estados->execute();
                $result_estados = $stmt_estados->get_result();

                while ($estado = $result_estados->fetch_assoc()):
                    echo "<option value='{$estado['id']}'>{$estado['nombre']}</option>";
                endwhile;
                ?>
            </select>

            <button type="submit">Crear Licencia</button>
        </form>
    <?php endif; ?>

    <!-- Tabla para mostrar licencias -->
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['descripcion']) ?></td>
            <td><?= htmlspecialchars($row['precio']) ?></td>
            <td><?= htmlspecialchars($row['estado_nombre']) ?></td>
            <td class="action-buttons">
                <a href="?editar=<?= urlencode($row['id']) ?>">Editar</a>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta licencia?')">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Enlace para regresar al dashboard -->
    <a href="dashboard.php">Regresar al Dashboard</a>
</body>
</html>