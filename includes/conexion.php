<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "album";

// Crear conexión
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer conjunto de caracteres
$conexion->set_charset("utf8mb4");

// Función para manejar errores SQL de manera más elegante
function manejarError($sql, $error) {
    echo "<div class='alert alert-danger'>
            <strong>Error en la consulta:</strong> $error<br>
            <code>$sql</code>
          </div>";
    // También se podría registrar en un archivo de log
    error_log("Error SQL: $error en la consulta: $sql");
}

// Función para verificar si una licencia está activa
function verificarLicenciaActiva($conexion, $empresa_id) {
    $fecha_actual = date('Y-m-d');
    
    $sql = "SELECT l.* FROM licencia l 
            WHERE l.empresa_id = ? 
            AND l.fecha_inicio <= ? 
            AND l.fecha_fin >= ? 
            AND l.estado = 1 
            LIMIT 1";
            
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iss", $empresa_id, $fecha_actual, $fecha_actual);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return false;
    }
}

// Función para obtener información del usuario por ID
function obtenerUsuarioPorId($conexion, $usuario_id) {
    $sql = "SELECT u.*, r.nombre as rol_nombre, e.nombre as empresa_nombre 
            FROM usuario u 
            JOIN rol r ON u.rol_id = r.id 
            JOIN empresa e ON u.empresa_id = e.id 
            WHERE u.id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return false;
    }
}
?>