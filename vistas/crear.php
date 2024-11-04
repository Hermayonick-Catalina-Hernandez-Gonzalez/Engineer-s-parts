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
      <a href="../index.php" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
        <img src="../img/Logo.png" alt="Logo">
        <span>Engineer's parts</span>
      </a>
    </div>
    <nav>
      <a href="../index.php"><span>Inicio</span></a>
      <a href="../vistas/buscador.html"><span>Buscador</span></a>
      <a href="../vistas/crear.php"><span>Crear</span></a>
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

  <script>
    function toggleDropdown() {
      document.getElementById("dropdownMenu").classList.toggle("show");
    }
    window.onclick = function(event) {
      if (!event.target.matches('.user-icon img')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
  </script>
</body>

</html>