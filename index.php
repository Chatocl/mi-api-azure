<?php
// Configuración de la conexión a la base de datos SQL Server en Azure usando PDO
$serverName = "tcp:virtualizacion.database.windows.net,1433";
$database = "BaseVirtualizacion"; // Nombre de la base de datos
$username = "virtualizacion"; // Usuario
$password = "U5uw8UV@FSm9tr5"; // Contraseña

try {
    // Crear la conexión con PDO utilizando el controlador sqlsrv
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Determinar la acción basada en el parámetro 'accion' recibido en la solicitud POST
    $accion = isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : '');

    switch ($accion) {
        case 'insertar':
            insertarNota($conn);
            break;
        case 'eliminar':
            eliminarNota($conn);
            break;
        case 'editar':
            editarNota($conn);
            break;
        case 'obtener':
            obtenerEstudiantes($conn);
            break;
        default:
            echo json_encode(["success" => false, "message" => "Acción no válida"]);
            break;
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
}

// Función para insertar una nota en la tabla "estudiantes"
function insertarNota($conn) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $carnet = $_POST['carnet'];
    $curso = $_POST['curso'];
    $nota = $_POST['nota'];

    $sql = "INSERT INTO estudiantes (nombre, apellido, carnet, curso, nota) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([$nombre, $apellido, $carnet, $curso, $nota]);
        echo json_encode(["success" => true, "message" => "Nota insertada exitosamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
}

// Función para eliminar una nota de la tabla "estudiantes"
function eliminarNota($conn) {
    $id = $_POST['id'];
    $sql = "DELETE FROM estudiantes WHERE id = ?";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Nota eliminada exitosamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
}

// Función para editar una nota en la tabla "estudiantes"
function editarNota($conn) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $carnet = $_POST['carnet'];
    $curso = $_POST['curso'];
    $nota = $_POST['nota'];

    $sql = "UPDATE estudiantes SET nombre = ?, apellido = ?, carnet = ?, curso = ?, nota = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([$nombre, $apellido, $carnet, $curso, $nota, $id]);
        echo json_encode(["success" => true, "message" => "Nota actualizada exitosamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
}

// Función para obtener todos los estudiantes de la tabla
function obtenerEstudiantes($conn) {
    $sql = "SELECT * FROM estudiantes";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($resultados);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
}
?>
