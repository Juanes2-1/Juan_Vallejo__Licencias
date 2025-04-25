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
        // Crear un nuevo usuario administrativo
        $documento = $_POST['documento'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $contrasena = md5($_POST['contrasena']); // Mejor usar password_hash() en producción
        $rol_id = 2; // Rol de Administrador

        $query = "INSERT INTO usuario (Documento, Nombre, Apellido, Correo, Contrasena, id_Rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $documento, $nombre, $apellido, $correo, $contrasena, $rol_id);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Usuario administrativo creado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al crear el usuario administrativo: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'editar') {
        // Editar un usuario administrativo existente
        $documento = $_POST['documento'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $contrasena = !empty($_POST['contrasena']) ? md5($_POST['contrasena']) : null;

        if ($contrasena) {
            $query = "UPDATE usuario SET Nombre = ?, Apellido = ?, Correo = ?, Contrasena = ? WHERE Documento = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $nombre, $apellido, $correo, $contrasena, $documento);
        } else {
            $query = "UPDATE usuario SET Nombre = ?, Apellido = ?, Correo = ? WHERE Documento = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $nombre, $apellido, $correo, $documento);
        }

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Usuario administrativo actualizado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al actualizar el usuario administrativo: " . $stmt->error . "</p>";
        }
    } elseif ($accion === 'eliminar') {
        // Eliminar un usuario administrativo
        $documento = $_POST['documento'];

        $query = "DELETE FROM usuario WHERE Documento = ? AND id_Rol = 2"; // Solo eliminamos usuarios con rol de Administrador
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $documento);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Usuario administrativo eliminado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al eliminar el usuario administrativo: " . $stmt->error . "</p>";
        }
    }
}

// Consulta para obtener todos los usuarios administrativos
$query = "SELECT * FROM usuario WHERE id_Rol = 2";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios Administrativos</title>
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
    <h1>Gestión de Usuarios Administrativos</h1>

    <!-- Formulario para Crear/Editar Usuario Administrativo -->
    <?php if (isset($_GET['editar'])): ?>
        <?php
        // Obtener los datos del usuario administrativo a editar
        $documento_editar = $_GET['editar'];
        $query_editar = "SELECT * FROM usuario WHERE Documento = ? AND id_Rol = 2";
        $stmt_editar = $conn->prepare($query_editar);
        $stmt_editar->bind_param("s", $documento_editar);
        $stmt_editar->execute();
        $result_editar = $stmt_editar->get_result();
        $usuario_editar = $result_editar->fetch_assoc();
        ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="documento" value="<?= htmlspecialchars($usuario_editar['Documento']) ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario_editar['Nombre']) ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($usuario_editar['Apellido']) ?>" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuario_editar['Correo']) ?>" required>

            <label for="contrasena">Contraseña (opcional):</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Dejar en blanco para no cambiar">

            <button type="submit">Guardar Cambios</button>
        </form>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="accion" value="crear">

            <label for="documento">Documento:</label>
            <input type="text" id="documento" name="documento" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Crear Usuario Administrativo</button>
        </form>
    <?php endif; ?>

    <!-- Tabla para mostrar usuarios administrativos -->
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
            <td class="action-buttons">
                <a href="?editar=<?= urlencode($row['Documento']) ?>">Editar</a>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="documento" value="<?= htmlspecialchars($row['Documento']) ?>">
                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este usuario administrativo?')">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Enlace para regresar al dashboard -->
    <a href="dashboard.php">Regresar al Dashboard</a>
</body>
</html>