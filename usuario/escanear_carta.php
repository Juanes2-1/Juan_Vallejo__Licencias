<?php
session_start();
include '../../includes/conexion.php'; // Incluimos la conexión a la base de datos

// Verificar si el usuario es un Usuario Normal (id_Rol = 3)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 3) {
    header("Location: ../../auth/login.php");
    exit;
}

// Obtener el Documento del usuario actual
$documento_usuario = $_SESSION['user_id'];

// Manejar la acción de escanear una carta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_barras = $_POST['codigo_barras'];

    // Verificar si la carta existe en la tabla `carta`
    $query_carta = "SELECT * FROM carta WHERE CodigoBarras = ?";
    $stmt_carta = $conn->prepare($query_carta);
    $stmt_carta->bind_param("s", $codigo_barras);
    $stmt_carta->execute();
    $result_carta = $stmt_carta->get_result();

    if ($result_carta->num_rows > 0) {
        $carta = $result_carta->fetch_assoc();

        // Verificar si la carta ya está en el álbum del usuario
        $query_detalle = "
            SELECT * FROM album 
            WHERE Documento = ? AND CodigoBarras = ?
        ";
        $stmt_detalle = $conn->prepare($query_detalle);
        $stmt_detalle->bind_param("ss", $documento_usuario, $codigo_barras);
        $stmt_detalle->execute();
        $result_detalle = $stmt_detalle->get_result();

        if ($result_detalle->num_rows > 0) {
            echo "<p style='color:red;'>La carta ya está en tu álbum.</p>";
        } else {
            // Agregar la carta al álbum del usuario
            $query_insert = "
                INSERT INTO album (Documento, CodigoBarras) VALUES (?, ?)
            ";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("ss", $documento_usuario, $codigo_barras);

            if ($stmt_insert->execute()) {
                echo "<p style='color:green;'>Carta agregada exitosamente al álbum.</p>";
            } else {
                echo "<p style='color:red;'>Error al agregar la carta: " . $stmt_insert->error . "</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>El código de barras no corresponde a ninguna carta válida.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escanea una Carta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <h1>Escanea una Carta</h1>

    <!-- Formulario para escanear una carta -->
    <form method="POST" action="">
        <label for="codigo_barras">Código de Barras:</label>
        <input type="text" id="codigo_barras" name="codigo_barras" placeholder="Ingresa el código de barras" required>
        <button type="submit">Agregar Carta</button>
    </form>

    <!-- Enlace para regresar al dashboard -->
    <a href="dashboard.php">Regresar al Dashboard</a>
</body>
</html>