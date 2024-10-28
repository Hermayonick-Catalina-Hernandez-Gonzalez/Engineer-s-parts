<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID del usuario de la URL
$usuario_id = filter_input(INPUT_GET, "usuario_id", FILTER_SANITIZE_NUMBER_INT);

// Obtener la información del usuario de la base de datos
$sql = "SELECT * FROM usuarios WHERE id = :usuario_id";
$stmt = $connection->prepare($sql);
$stmt->execute([':usuario_id' => $usuario_id]);

// Verificar si se encontró el usuario
if ($stmt->rowCount() > 0) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Consultas para obtener información relevante
    $sql_publicaciones = "SELECT COUNT(*) AS num_publicaciones FROM fotos WHERE usuario_subio_id = :usuario_id AND eliminado = 0";
    $stmt_publicaciones = $connection->prepare($sql_publicaciones);
    $stmt_publicaciones->execute([':usuario_id' => $usuario_id]);
    $num_publicaciones = $stmt_publicaciones->fetchColumn();

    $sql_seguidores = "SELECT COUNT(*) AS num_seguidores FROM seguidores WHERE usuario_siguiendo_id = :usuario_id AND eliminado = 0";
    $stmt_seguidores = $connection->prepare($sql_seguidores);
    $stmt_seguidores->execute([':usuario_id' => $usuario_id]);
    $num_seguidores = $stmt_seguidores->fetchColumn();

    $sql_seguidos = "SELECT COUNT(*) AS num_seguidos FROM seguidores WHERE usuario_seguidor_id = :usuario_id AND eliminado = 0";
    $stmt_seguidos = $connection->prepare($sql_seguidos);
    $stmt_seguidos->execute([':usuario_id' => $usuario_id]);
    $num_seguidos = $stmt_seguidos->fetchColumn();

    // Consultar las publicaciones del usuario
    $sql_publicaciones_usuario = "SELECT * FROM fotos WHERE usuario_subio_id = :usuario_id AND eliminado = 0";
    $stmt_publicaciones_usuario = $connection->prepare($sql_publicaciones_usuario);
    $stmt_publicaciones_usuario->execute([':usuario_id' => $usuario_id]);
    $publicaciones_usuario = $stmt_publicaciones_usuario->fetchAll(PDO::FETCH_ASSOC);

    // Obtener la ruta de la imagen del usuario
    $imagen_usuario = "../fotos_perfil/" . $usuario['foto_perfil'];
} else {
    // Manejar el caso en que no se encuentra el usuario
    die("Usuario no encontrado.");
}

// Función para verificar si el usuario actual sigue a otro usuario
function esta_siguiendo_usuario($usuario_id, $usuario_seguir_id)
{
    global $connection;
    $sql = "SELECT COUNT(*) AS num_filas FROM seguidores WHERE usuario_seguidor_id = :usuario_id AND usuario_siguiendo_id = :usuario_seguir_id";
    $stmt = $connection->prepare($sql);
    $stmt->execute([':usuario_id' => $usuario_id, ':usuario_seguir_id' => $usuario_seguir_id]);
    return $stmt->fetchColumn() > 0;
}

// Manejar la acción de seguir o dejar de seguir a un usuario
if (isset($_POST['toggle_seguir'])) {
    $usuario_seguir_id = $usuario['id'];

    if (esta_siguiendo_usuario($usuarioID, $usuario_seguir_id)) {
        // Dejar de seguir
        $sql_dejar_seguir_usuario = "DELETE FROM seguidores WHERE usuario_seguidor_id = :usuario_id AND usuario_siguiendo_id = :usuario_seguir_id";
        $stmt_dejar_seguir_usuario = $connection->prepare($sql_dejar_seguir_usuario);
        $stmt_dejar_seguir_usuario->execute([':usuario_id' => $usuarioID, ':usuario_seguir_id' => $usuario_seguir_id]);
    } else {
        // Seguir
        $sql_seguir_usuario = "INSERT INTO seguidores (usuario_seguidor_id, usuario_siguiendo_id, fecha_hora) VALUES (:usuario_id, :usuario_seguir_id, NOW())";
        $stmt_seguir_usuario = $connection->prepare($sql_seguir_usuario);
        $stmt_seguir_usuario->execute([':usuario_id' => $usuarioID, ':usuario_seguir_id' => $usuario_seguir_id]);
    }
    header("Location: {$_SERVER['PHP_SELF']}?usuario_id=$usuario_seguir_id");
    exit;
}

// Manejar la acción de eliminar una publicación
if (isset($_POST['eliminar_publicacion'])) {
    $publicacion_id = $_POST['publicacion_id'];

    // Eliminar la publicación
    $sql_eliminar_publicacion = "UPDATE fotos SET eliminado = 1 WHERE id = :publicacion_id";
    $stmt_eliminar_publicacion = $connection->prepare($sql_eliminar_publicacion);
    $stmt_eliminar_publicacion->execute([':publicacion_id' => $publicacion_id]);

    header("Location: {$_SERVER['PHP_SELF']}?usuario_id=$usuario_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil buscado</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylesPerfil.css">
</head>

<body>
    <div class="panel">
        <div class="opcion" id="lydch"><a href="#"><img src="../img/Logo.png" alt="LYDCH" style="width: 60px; height: 60px;"><span style="font-size: larger; font-weight: bold;">UPVBOOKS</span></a></div>
        <div class="espacio"></div>
        <div class="opcion"><a href="../index.php"><img src="../img/Inicio.png" alt="Inicio"><span>Inicio</span></a></div>
        <div class="opcion"><a href="./buscador.html"><img src="../img/Buscador.png" alt="Buscador"><span>Buscador</span></a></div>
        <div class="opcion"><a href="./crear.php"><img src="../img/Crear.png" alt="Crear"><span>Crear</span></a></div>
        <div class="opcion" id="perfil"><a href="./perfil.php"><img src="../img/usuario.png" alt="Perfil"><span>Perfil</span></a></div>
        <div class="opcion"><a href="../php/logout.php"><img src="../img/Salir.png" alt="Salir"><span>Salir</span></a></div>
    </div>

    <div class="contenedor">
        <div class="perfil">
            <div class="foto-usuario">
                <img src="<?php echo isset($imagen_usuario) && file_exists($imagen_usuario) ? $imagen_usuario : '../fotos_perfil/image.png'; ?>" alt="Foto de Usuario" class="foto-usuario">
            </div>

            <div class="info-usuario">
                <div class="nombre-usuario"><?php echo htmlspecialchars($usuario['username']); ?></div>
                <form method="post">
                    <button class="editar-perfil" type="submit" name="toggle_seguir">
                        <?php echo esta_siguiendo_usuario($usuarioID, $usuario['id']) ? 'Dejar de seguir' : 'Seguir'; ?>
                    </button>
                </form>
            </div>

            <div class="datos-usuario">
                <span class="informacion-detallada"><?php echo $num_publicaciones; ?> publicaciones</span>
                <span class="informacion-detallada"><?php echo $num_seguidores; ?> seguidores</span>
                <span class="informacion-detallada"><?php echo $num_seguidos; ?> seguidos</span>
            </div>

            <?php if (isset($publicaciones_usuario) && !empty($publicaciones_usuario)) : ?>
                <div class="galeria">
                    <?php foreach ($publicaciones_usuario as $publicacion) : ?>
                        <div class="publicacion">
                            <img src="../fotos/<?php echo htmlspecialchars($publicacion['secure_id'] . "." . $publicacion['extension']); ?>" alt="<?php echo htmlspecialchars($publicacion['descripcion']); ?>">
                            <p><?php echo htmlspecialchars($publicacion['descripcion']); ?></p> <!-- Descripción debajo de la imagen -->
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php else : ?>
                <p>No hay publicaciones disponibles.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>