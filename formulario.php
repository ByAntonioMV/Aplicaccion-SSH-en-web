<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar archivo por SSH</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Enviar archivo por SSH</h1>

        <form action="enviar_archivo.php" method="POST" enctype="multipart/form-data">
            <label for="nombreComputadora">Selecciona un usuario:</label>
            <select name="nombreComputadora" id="nombreComputadora" required>
                <?php
                // Conectar a la base de datos
                $host = "127.0.0.1"; 
                $db = "ssh"; 
                $user = "antonio"; 
                $password = "ByAlain1234!"; 

                // Crear la conexión
                $conn = new mysqli($host, $user, $password, $db);
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Consultar usuarios
                $sql = "SELECT id, nombre, apellido, nombreComputadora FROM usuarios";
                $result = $conn->query($sql);

                // Mostrar los usuarios en el select
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['nombreComputadora'] . "'>" . $row['nombre'] . " " . $row['apellido'] . " (" . $row['nombreComputadora'] . ")</option>";
                    }
                } else {
                    echo "<option value=''>No hay usuarios</option>";
                }

                // Cerrar conexión
                $conn->close();
                ?>
            </select>

            <label for="archivo">Selecciona el archivo a enviar:</label>
            <input type="file" name="archivo" id="archivo" required>

            <button type="submit" class="btn">Enviar archivo</button>
        </form>
    </div>
</body>
</html>
