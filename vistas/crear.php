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
    <form action="#" id="formCrear" enctype="multipart/form-data" method="post">
      <div class="subir">
        <!-- Vista previa de la imagen -->
        <div class="subir-foto">
          <div class="foto-preview" onclick="document.getElementById('foto').click();"></div>
          <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
          <button type="button" id="seleccionar-foto">Seleccionar Foto</button>
        </div>

        <!-- Información del usuario y detalles del producto -->
        <div class="usuario-publicacion">
          <div class="usuario">
            <img src="../img/default_perfil.jpg" alt="Foto usuario">
            <span>Hermayonick</span>
          </div>
          <div class="estado">
            <label>Estado</label>
            <label><input type="radio" name="estado" value="nuevo"> Nuevo</label>
            <label><input type="radio" name="estado" value="seminuevo"> Seminuevo</label>
            <label><input type="radio" name="estado" value="usado"> Usado</label>
          </div>

          <div class="precio">
            <label>Precio</label>
            <input type="text" placeholder="$">
          </div>
          <textarea class="descripcion" placeholder="Descripción..."></textarea>
          <button class="publicar" type="submit">Publicar</button>
        </div>
      </div>
    </form>
  </div>
  <script src="../js/scriptCrear.js"></script>
</body>

</html>