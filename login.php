<?php
// Iniciar sesión para manejar mensajes
session_start();

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $host = "127.0.0.1"; // Cambiar según sea necesario
    $db = "ssh"; // Cambiar al nombre de tu base de datos
    $user = "antonio"; // Cambiar con tu usuario de la base de datos
    $password = "ByAlain1234!"; // Cambiar con tu contraseña

    // Crear la conexión
    $conn = new mysqli($host, $user, $password, $db);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los valores del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena']; // Contraseña en texto plano

    // Consultar la base de datos para verificar el usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El usuario existe, verificar la contraseña (en texto plano)
        $row = $result->fetch_assoc();
        if ($contrasena === $row['contrasena']) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['usuario'] = $row['usuario']; // Guardar el nombre de usuario en la sesión
            $_SESSION['mensaje'] = "¡Bienvenido, " . $row['nombre'] . "!";
            header("Location: formulario.php"); // Redirigir al dashboard o página principal
            exit();
        } else {
            // Contraseña incorrecta
            $_SESSION['mensaje'] = "Contraseña incorrecta.";
            header("Location: index.html"); // Redirigir al formulario de login
            exit();
        }
    } else {
        // Usuario no encontrado
        $_SESSION['mensaje'] = "El usuario no existe.";
        header("Location: index.html"); // Redirigir al formulario de login
        exit();
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
