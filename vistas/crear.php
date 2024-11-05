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
  // Convertir el BLOB en base64 si `foto_perfil` es un campo BLOB
  $imagen_usuario = 'data:image/jpeg;base64,' . base64_encode($usuario['foto_perfil']);
} else {
  // Imagen predeterminada si no hay foto de perfil
  $imagen_usuario = "../img/default_perfil.jpg";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Publicación</title>
  <link rel="icon" href="../img/Logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/stylesCrear.css">
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
    </nav>
    <div class="user-icon">
      <a href="../vistas/perfil.php"><img src="../img/usuario.png" alt="Usuario"></a>
    </div>
  </header>

  <div class="contenedor">
    <form action="../php/guardar_archivo.php" id="formCrear" enctype="multipart/form-data" method="post">
      <div class="subir">
        <div class="subir-foto">
          <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
          <div class="foto-preview" onclick="document.getElementById('foto').click();"></div>
          <button type="button" id="seleccionar-foto">Seleccionar foto</button>
        </div>

        <div class="usuario-publicacion">
          <div class="usuario">
            <img src="<?php echo $imagen_usuario; ?>" alt="Foto usuario">
            <span><?php echo htmlspecialchars($usuario['username']); ?></span>
          </div>

          <div class="nombre input">
            <label>Nombre del Producto</label>
            <input type="text" name="nombre_producto" placeholder="Nombre del Producto" required>
          </div>

          <div class="estado">
            <label>Estado</label>
            <label><input type="radio" name="estado" value="nuevo" required> Nuevo</label>
            <label><input type="radio" name="estado" value="seminuevo"> Seminuevo</label>
            <label><input type="radio" name="estado" value="usado"> Usado</label>
          </div>

          <div class="precio">
            <label>Precio</label>
            <input type="text" name="precio" placeholder="$" required>
          </div>
          <textarea class="descripcion" name="descripcion" placeholder="Descripción..."></textarea>
          <button class="publicar" type="submit">Publicar</button>
        </div>
      </div>
    </form>

  </div>

  <!-- Incluye el script -->
  <script src="../js/scriptCrear.js"></script>
</body>

</html>