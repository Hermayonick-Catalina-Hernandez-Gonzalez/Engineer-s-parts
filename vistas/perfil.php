<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID del usuario que inició sesión
$usuario_id = $usuarioID;

// Obtener la información del usuario de la base de datos
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario tiene una foto de perfil guardada
if (!empty($usuario['foto_perfil'])) {
  // Si el campo foto_perfil contiene el nombre de un archivo, cargar desde la carpeta fotos_perfil
  $imagen_usuario = "../fotos_perfil/" . $usuario['foto_perfil'];
} else {
  // Imagen predeterminada si no hay foto de perfil
  $imagen_usuario = "../img/default_perfil.png";
}

// Consulta para obtener el número de publicaciones
$sql_publicaciones = "SELECT COUNT(*) AS num_publicaciones FROM fotos WHERE usuario_subio_id = ? AND eliminado = 0";
$stmt_publicaciones = $connection->prepare($sql_publicaciones);
$stmt_publicaciones->execute([$usuario_id]);
$resultado_publicaciones = $stmt_publicaciones->fetch(PDO::FETCH_ASSOC);
$num_publicaciones = $resultado_publicaciones['num_publicaciones'];

// Consulta para obtener el número de seguidores
$sql_seguidores = "SELECT COUNT(*) AS num_seguidores FROM seguidores WHERE usuario_siguiendo_id = ? AND eliminado = 0";
$stmt_seguidores = $connection->prepare($sql_seguidores);
$stmt_seguidores->execute([$usuario_id]);
$resultado_seguidores = $stmt_seguidores->fetch(PDO::FETCH_ASSOC);
$num_seguidores = $resultado_seguidores['num_seguidores'];

// Consulta para obtener el número de seguidos
$sql_seguidos = "SELECT COUNT(*) AS num_seguidos FROM seguidores WHERE usuario_seguidor_id = ? AND eliminado = 0";
$stmt_seguidos = $connection->prepare($sql_seguidos);
$stmt_seguidos->execute([$usuario_id]);
$resultado_seguidos = $stmt_seguidos->fetch(PDO::FETCH_ASSOC);
$num_seguidos = $resultado_seguidos['num_seguidos'];

// Consulta para obtener las publicaciones del usuario
$sql_publicaciones_usuario = "SELECT * FROM fotos WHERE usuario_subio_id = ? AND eliminado = 0";
$stmt_publicaciones_usuario = $connection->prepare($sql_publicaciones_usuario);
$stmt_publicaciones_usuario->execute([$usuario_id]);
$publicaciones_usuario = $stmt_publicaciones_usuario->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi perfil</title>
  <link rel="icon" href="../img/Logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/stylesPerfil.css">
</head>

<body>
  <header class="navbar">
    <div class="logo">
      <img src="../img/Logo.png" alt="Logo">
      <span>Engineer's parts</span>
    </div>
    <nav>
      <a href="../index.php">Inicio</a>
      <a href="./buscador.html">Buscador</a>
      <a href="./crear.php">Crear</a>
    </nav>
    <div class="user-icon">
      <a href="../php/logout.php"><img src="../img/usuario.png" alt="Usuario"></a>
    </div>
  </header>

  <div class="contenedor">
    <div class="perfil">
      <div class="foto-usuario">
        <img src="../img/default_perfil.jpg" alt="Foto de Usuario">
      </div>


      <div class="info-usuario">
        <div class="nombre-usuario"><?php echo htmlspecialchars($usuario['username']); ?></div>
        <button class="editar-perfil"><a href="./editarperfil.php">Editar</a></button>
      </div>

      <div class="datos-usuario">
        <span class="informacion-detallada"><?php echo $num_publicaciones; ?> publicaciones</span>
        <span class="informacion-detallada"><?php echo $num_seguidores; ?> seguidores</span>
        <span class="informacion-detallada"><?php echo $num_seguidos; ?> seguidos</span>
      </div>

      <div class="galeria">
        <?php foreach ($publicaciones_usuario as $publicacion) : ?>
          <div class="publicacion">
            <img src="../fotos/<?php echo htmlspecialchars($publicacion['secure_id'] . "." . $publicacion['extension']); ?>" alt="Publicación">
            <span class="estado">Vendido</span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</body>

</html>