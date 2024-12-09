<?php
// Verificar que el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir el usuario seleccionado y el archivo
    $usuarioSeleccionado = $_POST['nombreComputadora']; // Nombre o identificador de la computadora
    $archivoSubido = $_FILES['archivo']; // Archivo subido desde el formulario

    // Validar que el archivo se haya cargado correctamente
    if ($archivoSubido['error'] !== UPLOAD_ERR_OK) {
        die("Error al cargar el archivo. Código de error: " . $archivoSubido['error']);
    }

    // Conectar a la base de datos para obtener la información del usuario
    $host = "127.0.0.1"; 
    $db = "ssh"; 
    $user = "antonio"; 
    $password = "ByAlain1234!"; 

    $conn = new mysqli($host, $user, $password, $db);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener la IP, contraseña y carpeta de la computadora del usuario
    $sql = "SELECT ipComputadora, contrasenaComputadora, carpetaComputadora FROM usuarios WHERE nombreComputadora = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuarioSeleccionado);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($ipComputadora, $contrasenaComputadora, $carpetaComputadora);

    if ($stmt->fetch()) {
        // Datos del usuario obtenidos correctamente
        echo "Conectando a: " . $ipComputadora . "<br>";
        echo "Carpeta remota: " . $carpetaComputadora . "<br>";

        // Ruta temporal del archivo cargado en el servidor
        $rutaArchivoTemporal = $archivoSubido['tmp_name'];

        // Conexión SSH al servidor remoto usando la IP
        $conexionSSH = ssh2_connect($ipComputadora, 22);

        if ($conexionSSH) {
            // Autenticación con la contraseña
            if (ssh2_auth_password($conexionSSH, $usuarioSeleccionado, $contrasenaComputadora)) {
                echo "Autenticación exitosa en la computadora remota.<br>";

                // Ruta remota completa donde se enviará el archivo
                $rutaRemota = $carpetaComputadora . '/' . basename($archivoSubido['name']);

                // Subir el archivo con SCP
                if (ssh2_scp_send($conexionSSH, $rutaArchivoTemporal, $rutaRemota)) {
                    echo "Archivo enviado exitosamente a la carpeta remota: " . $rutaRemota . "<br>";
                } else {
                    echo "Error al enviar el archivo.<br>";
                }
            } else {
                echo "Error de autenticación SSH.<br>";
            }
        } else {
            echo "No se pudo establecer la conexión SSH con la IP: " . $ipComputadora . "<br>";
        }
    } else {
        echo "No se encontró información de la computadora seleccionada.<br>";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    echo "No se ha enviado el formulario.<br>";
}
?>
