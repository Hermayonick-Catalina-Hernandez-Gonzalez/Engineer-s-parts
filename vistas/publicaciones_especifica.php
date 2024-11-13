<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID de la publicación desde la URL
$publicacion_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar los detalles de la publicación específica
$sql_publicacion = "SELECT f.*, u.username as usuario_subio_username, u.foto_perfil,
                    (SELECT COUNT(*) FROM fotos_likes fl WHERE fl.foto_id = f.id) as likes_count
                    FROM fotos_v f 
                    JOIN usuarios u ON f.usuario_subio_id = u.id 
                    WHERE f.id = :id AND f.eliminado = 0";
$stmt_publicacion = $connection->prepare($sql_publicacion);
$stmt_publicacion->bindParam(':id', $publicacion_id, PDO::PARAM_INT);
$stmt_publicacion->execute();
$publicacion = $stmt_publicacion->fetch(PDO::FETCH_ASSOC);

// Verificar si la publicación existe
if (!$publicacion) {
    echo "Publicación no encontrada.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Publicación</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylesInicio.css">
</head>

<body>
    <header class="navbar">
        <div class="logo">
            <img src="../img/Logo.png" alt="Logo">
            <span>Engineer's parts</span>
        </div>
        <nav>
            <a href="../index.php"><span>Inicio</span></a>
            <a href="../vistas/buscador.html"><span>Buscador</span></a>
            <a href="../vistas/crear.php"><span>Crear</span></a>
            <a href="../vistas/inventario.php"><span>Inventario</span></a>
        </nav>
        <div class="user-icon">
            <img src="../img/usuario.png" alt="Usuario" onclick="toggleDropdown()">
            <div class="dropdown-content" id="dropdownMenu">
                <a href="../vistas/perfil.php">Perfil</a>
                <a href="../vistas/editarperfil.php">Editar perfil</a>
                <a href="../php/logout.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <!-- Contenido de la publicación -->
    <div class="usuario-publicacion">
        <div class="publicacion">
            <div class="info-usuario">
                <div class="nombre">
                    <img src="../fotos_perfil/<?= htmlspecialchars($publicacion['foto_perfil']) ?>" alt="Perfil" style="width: 60px; height: 60px;">
                    <span><?= htmlspecialchars($publicacion['usuario_subio_username']) ?></span>
                </div>
                <p><strong>Precio:</strong> $<?= number_format($publicacion["precio"] ?? 0, 2) ?></p>
                <p><?= htmlspecialchars($publicacion["descripcion"]) ?></p>

                <div class="foto-publicacion">
                    <img src="../fotos/<?= htmlspecialchars($publicacion["secure_id"] . "." . $publicacion["extension"]) ?>" alt="<?= htmlspecialchars($publicacion["nombre_archivo"]) ?>">
                </div>
            </div>

            <!-- Área de Me gusta y Contador de Me gusta -->
            <div class="post-actions">
                <button class="like-btn" onclick="likePost(<?= $publicacion['id'] ?>)">❤️ Me gusta</button>
                <span id="likes-count-<?= $publicacion['id'] ?>"><?= $publicacion['likes_count'] ?? 0 ?> Me gusta</span>
                <button class="comment-btn" onclick="toggleCommentForm(this)">💬 Comentar</button>
            </div>

            <!-- Sección de comentarios -->
            <div class="comentarios">
                <?php
                // Obtener comentarios de la publicación
                $sql_comentarios = "SELECT c.id, c.comentario, c.fecha, u.username as usuario_comentario, u.foto_perfil
                                    FROM comentarios c 
                                    JOIN usuarios u ON c.usuario_id = u.id 
                                    WHERE c.foto_id = :foto_id
                                    ORDER BY c.fecha ASC";
                $stmt_comentarios = $connection->prepare($sql_comentarios);
                $stmt_comentarios->bindParam(':foto_id', $publicacion_id, PDO::PARAM_INT);
                $stmt_comentarios->execute();
                $comentarios = $stmt_comentarios->fetchAll();

                if (!empty($comentarios)) {
                    foreach ($comentarios as $comentario) {
                        $fotoComentario = (!empty($comentario['foto_perfil']))
                            ? '../fotos_perfil/' . $comentario['foto_perfil']
                            : '../fotos_perfil/default.png';
                ?>
                        <div class="comentario">
                            <img src="<?= $fotoComentario ?>" alt="Perfil" class="profile-pic-comment">
                            <div class="comment-content">
                                <div>
                                    <strong><?= htmlspecialchars($comentario['usuario_comentario']) ?></strong> <?= htmlspecialchars($comentario['comentario']) ?>
                                </div>
                                <div class="comment-meta">
                                    <small><?= htmlspecialchars($comentario['fecha']) ?></small>
                                    <button class="reply-btn" onclick="toggleReplyForm(this)">Responder</button>
                                </div>

                                <!-- Respuestas al comentario -->
                                <div class="replies">
                                    <?php
                                    // Consultar respuestas de cada comentario
                                    $sql_respuestas = "SELECT r.respuesta, r.fecha, u.username as usuario_respuesta, u.foto_perfil
                                                       FROM respuestas r 
                                                       JOIN usuarios u ON r.usuario_id = u.id 
                                                       WHERE r.comentario_id = :comentario_id
                                                       ORDER BY r.fecha ASC";
                                    $stmt_respuestas = $connection->prepare($sql_respuestas);
                                    $stmt_respuestas->bindParam(':comentario_id', $comentario['id'], PDO::PARAM_INT);
                                    $stmt_respuestas->execute();
                                    $respuestas = $stmt_respuestas->fetchAll();

                                    if (!empty($respuestas)) {
                                        foreach ($respuestas as $respuesta) {
                                            $fotoRespuesta = (!empty($respuesta['foto_perfil']))
                                                ? '../fotos_perfil/' . $respuesta['foto_perfil']
                                                : '../fotos_perfil/default.png';
                                    ?>
                                            <div class="respuesta">
                                                <img src="<?= $fotoRespuesta ?>" alt="Perfil" class="profile-pic-reply">
                                                <strong><?= htmlspecialchars($respuesta['usuario_respuesta']) ?></strong> <?= htmlspecialchars($respuesta['respuesta']) ?>
                                                <small><?= htmlspecialchars($respuesta['fecha']) ?></small>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        echo "<p>No hay respuestas aún.</p>";
                                    }
                                    ?>
                                </div>

                                <!-- Formulario de respuesta -->
                                <form action="../php/agregar_respuesta.php" method="post" class="reply-form hidden">
                                    <input type="hidden" name="comentario_id" value="<?= $comentario['id'] ?>">
                                    <textarea name="respuesta" placeholder="Escribe una respuesta..." required></textarea>
                                    <button type="submit">Responder</button>
                                </form>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No hay comentarios aún.</p>";
                }
                ?>

                <!-- Formulario para añadir un comentario -->
                <form action="../php/agregar_comentario.php" method="post" class="comment-form">
                    <input type="hidden" name="foto_id" value="<?= $publicacion_id ?>">
                    <textarea name="comentario" placeholder="Escribe un comentario..." required></textarea>
                    <button type="submit">Comentar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/scriptInicio.js"></script>
</body>

</html>
