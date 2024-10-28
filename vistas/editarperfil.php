<?php
require "../php/connection.php";
require "../php/sesion_requerida.php";

// Obtener los datos del usuario actual
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$usuarioID]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fecha_nacimiento = $_POST['fecha-nacimiento'];
    $genero = $_POST['genero'];
    $correo = $_POST['correo'];

    // Si el usuario sube una nueva imagen de perfil
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        // Verificar la extensión del archivo
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ["jpg", "jpeg", "png", "gif"];

        if (in_array($extension, $extensiones_permitidas)) {
            // Generar el nombre del archivo con el formato IdUsuario_NombreUsuario.extensión
            $nuevoNombreArchivo = $usuarioID . "_" . $result['username'] . "." . $extension;
            $rutaDestino = "../fotos_perfil/" . $nuevoNombreArchivo;

            // Mover la imagen al directorio de destino
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                // Actualizar la base de datos con el nuevo nombre de la imagen
                $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, genero = ?, email = ?, foto_perfil = ? WHERE id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->execute([$nombre, $apellidos, $fecha_nacimiento, $genero, $correo, $nuevoNombreArchivo, $usuarioID]);

                echo "Perfil actualizado correctamente";
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "Extensión de archivo no permitida.";
        }
    } else {
        // Si no se ha subido una imagen, actualizar solo los demás datos
        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, genero = ?, email = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$nombre, $apellidos, $fecha_nacimiento, $genero, $correo, $usuarioID]);

        echo "Perfil actualizado correctamente.";
    }

    // Redirigir al perfil o mostrar mensaje de éxito
    header("Location: ../vistas/perfil.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylesditarperfil.css">
</head>

<body>
    <div class="card">
        <h1>Editar Perfil</h1>
        <p><?php if (isset($mensaje)) echo $mensaje; ?></p>
        <form class="ingresos" action="" method="post" enctype="multipart/form-data">
            <label for="imagen">Cambia tu foto de perfil:</label>
            <input type="file" name="imagen" id="imagen">
            <label for="nombre">Nombre:</label>
            <input type="text" placeholder="Usuario..." name="nombre" id="nombre" value="<?= htmlspecialchars($result['nombre']) ?>" required>
            <label for="apellidos">Apellidos:</label>
            <input type="text" placeholder="Apellido..." name="apellidos" id="apellidos" value="<?= htmlspecialchars($result['apellidos']) ?>">
            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha-nacimiento" name="fecha-nacimiento" value="<?= htmlspecialchars($result['fecha_nacimiento']) ?>">
            <label>Género:</label>
            <select id="genero" name="genero">
                <option value="O" <?= ($result['genero'] === 'O') ? 'selected' : '' ?>>Selecciona tu género...</option>
                <option value="M" <?= ($result['genero'] === 'M') ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= ($result['genero'] === 'F') ? 'selected' : '' ?>>Femenino</option>
                <option value="X" <?= ($result['genero'] === 'X') ? 'selected' : '' ?>>Prefiero no especificar</option>
            </select>
            <label for="correo">Correo electrónico:</label>
            <input type="email" placeholder="Correo electrónico..." id="correo" name="correo" value="<?= htmlspecialchars($result['email']) ?>" required>
            <div class="cont-btn">
                <button type="button" class="salir" onclick="window.location.href = './perfil.php'">Salir</button>
                <button type="submit" class="guardar">Modificar</button>
            </div>
        </form>
    </div>
</body>

</html>
