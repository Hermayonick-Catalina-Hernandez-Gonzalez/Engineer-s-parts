<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID del usuario que inició sesión
$usuario_id = $usuarioID;

// Obtener la información del usuario de la base de datos
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$usuario_id]);
$usuarioResp = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario tiene una foto de perfil
if (!empty($usuarioResp['foto_perfil'])) {
    // Si el campo foto_perfil contiene el nombre de un archivo, cargar desde la carpeta fotos_perfil
    $imagen_usuario = "../fotos_perfil/" . $usuarioResp['foto_perfil'];
} else {
    // Imagen predeterminada si no hay foto de perfil
    $imagen_usuario = "../img/default_perfil.jpg"; 
}

$usuario_nombre = htmlspecialchars($usuarioResp['username']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear publicación</title>
  <link rel="icon" href="../img/Logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/stylesCrear.css">
</head>

<body>
  <div class="panel">
    <div class="opcion" id="lydch"><a href="#"><img src="../img/Logo.png" alt="LYDCH"
          style="width: 60px; height: 60px;"><span style="font-size: larger; font-weight: bold;">UPVBOOKS</span></a></div>
    <div class="espacio"></div>
    <div class="opcion"><a href="../index.php"><img src="../img/Inicio.png" alt="Inicio"><span>Inicio</span></a></div>
    <div class="opcion"><a href="./buscador.html"><img src="../img/Buscador.png" alt="Buscador"><span>Buscador</span></a></div>
    <div class="opcion"><a href="./crear.php"><img src="../img/Crear.png" alt="Crear"><span>Crear</span></a></div>
    <div class="opcion" id="perfil"><a href="./perfil.php"><img src="../img/usuario.png" alt="Perfil"><span>Perfil</span></a></div>
    <div class="opcion"><a href="../php/logout.php"><img src="../img/Salir.png" alt="Salir"><span>Salir</span></a></div>
  </div>

  <div class="contenedor">
    <form action="#" id="formCrear" enctype="multipart/form-data" method="post">
      <div class="subir">
        <div class="subir-foto">
          <input type="file" id="foto" name="foto" accept="image/*" style="display: none;" >
          <div class="foto-preview" onclick="document.getElementById('foto').click();"></div>
          <button type="button" id="seleccionar-foto">Seleccionar foto</button>
        </div>

        <div class="usuario-publicacion">
          <div class="usuario"><img src="<?=$imagen_usuario?>" alt="Foto usuario"><span><?=$usuario_nombre?></span></div>
          <textarea class="descripcion" placeholder="Escribe una descripción..." id="descripcion" name="descripcion"></textarea>
          <button class="publicar" type="submit">Publicar</button>
        </div>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../js/scriptCrear.js"></script>
</body>

</html>
