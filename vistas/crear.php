<?php
// Requiere una sesión activa y conexión a la base de datos
require "../php/sesion_requerida.php"; // Verifica si el usuario ha iniciado sesión
require "../php/connection.php"; // Conexión a la base de datos

// Obtener el ID del usuario que inició sesión
$usuario_id = $usuarioID;

// Consulta para obtener la información del usuario desde la base de datos
$sql = "SELECT * FROM usuarios WHERE id = ?"; // Consulta SQL para seleccionar los datos del usuario autenticado
$stmt = $connection->prepare($sql); // Prepara la consulta SQL
$stmt->execute([$usuario_id]); // Ejecuta la consulta con el ID del usuario como parámetro
$usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene el resultado como un arreglo asociativo

// Verificar si el usuario tiene una foto de perfil
if (!empty($usuario['foto_perfil'])) {
    // Si el usuario tiene una foto de perfil registrada, se construye la ruta a esa imagen
    $imagen_usuario = "../fotos_perfil/" . htmlspecialchars($usuario['foto_perfil']);
} else {
    // Si no tiene foto de perfil, se usa una imagen predeterminada
    $imagen_usuario = "../img/default_perfil.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Configuración básica del documento HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Publicación</title>
    <!-- Icono de la página -->
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <!-- Hoja de estilos personalizada -->
    <link rel="stylesheet" href="../css/stylesCrear.css">
</head>

<body>
    <!-- Barra de navegación -->
    <header class="navbar">
        <div class="logo">
            <!-- Logo del sitio -->
            <img src="../img/Logo.png" alt="Logo">
            <span>Engineer's parts</span>
        </div>
        <!-- Menú de navegación -->
        <nav>
            <a href="../index.php"><span>Inicio</span></a>
            <a href="../vistas/buscador.html"><span>Buscador</span></a>
            <a href="../vistas/crear.php"><span>Crear</span></a>
            <a href="../vistas/inventario.php"><span>Inventario</span></a>
        </nav>
        <!-- Icono del usuario con menú desplegable -->
        <div class="user-icon">
            <!-- Imagen de perfil del usuario -->
            <img src="../img/usuario.png" alt="Usuario" onclick="toggleDropdown()">
            <div class="dropdown-content" id="dropdownMenu">
                <!-- Opciones del menú desplegable -->
                <a href="../vistas/perfil.php">Perfil</a>
                <a href="../vistas/editarperfil.php">Editar perfil</a>
                <a href="../php/logout.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <div class="contenedor">
        <!-- Formulario para crear la publicación -->
        <form action="../php/guardar_archivo.php" id="formCrear" enctype="multipart/form-data" method="post">
            <div class="subir">
                <!-- Sección para subir la foto del producto -->
                <div class="subir-foto">
                    <!-- Entrada de archivo oculta -->
                    <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
                    <!-- Vista previa de la foto -->
                    <div class="foto-preview" onclick="document.getElementById('foto').click();"></div>
                    <!-- Botón para seleccionar una foto -->
                    <button type="button" id="seleccionar-foto">Seleccionar foto</button>
                </div>

                <!-- Sección para la información del usuario y el producto -->
                <div class="usuario-publicacion">
                    <!-- Muestra la foto y el nombre del usuario -->
                    <div class="usuario">
                        <img src="<?php echo $imagen_usuario; ?>" alt="Foto usuario">
                        <span><?php echo htmlspecialchars($usuario['username']); ?></span>
                    </div>

                    <!-- Campo para ingresar el nombre del producto -->
                    <div class="nombre input">
                        <label>Nombre del Producto</label>
                        <input type="text" name="nombre_producto" placeholder="Nombre del Producto" required>
                    </div>

                    <!-- Opciones para seleccionar el estado del producto -->
                    <div class="estado">
                        <label>Estado</label>
                        <label><input type="radio" name="estado" value="nuevo" required> Nuevo</label>
                        <label><input type="radio" name="estado" value="seminuevo"> Seminuevo</label>
                        <label><input type="radio" name="estado" value="usado"> Usado</label>
                    </div>

                    <!-- Campo para ingresar el precio del producto -->
                    <div class="precio">
                        <label>Precio</label>
                        <input type="number" name="precio" placeholder="$" required min="0" step="0.01">
                    </div>

                    <!-- Campo para ingresar la descripción del producto -->
                    <textarea class="descripcion" name="descripcion" placeholder="Descripción..."></textarea>
                    <!-- Botón para enviar el formulario -->
                    <button class="publicar" type="submit">Publicar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Script para manejar la funcionalidad del menú desplegable -->
    <script src="../js/scriptCrear.js"></script>
    <script>
        function toggleDropdown() {
            // Alterna la visibilidad del menú desplegable
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        // Cierra el menú desplegable al hacer clic fuera de él
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
