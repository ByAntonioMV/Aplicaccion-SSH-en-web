<?php
// Iniciar sesión para manejar mensajes
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Conectar a la base de datos
    $host = "127.0.0.1";
    $db = "ssh";
    $user = "antonio";
    $password = "ByAlain1234!";

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $nombreComputadora = $_POST['nombreComputadora'];
    $ipComputadora = $_POST['ipComputadora']; // Capturar la IP de la computadora
    $contrasenaComputadora = $_POST['contrasenaComputadora'];
    $carpetaComputadora = $_POST['carpetaComputadora'];

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO usuarios (nombre, apellido, usuario, contrasena, nombreComputadora, ipComputadora, contrasenaComputadora, carpetaComputadora) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $nombre, $apellido, $usuario, $contrasena, $nombreComputadora, $ipComputadora, $contrasenaComputadora, $carpetaComputadora);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Usuario registrado exitosamente.";
    } else {
        $_SESSION['mensaje'] = "Error al registrar usuario: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: registrar.php");
    exit();
}
?>
