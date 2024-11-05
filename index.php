<?php
require "./php/sesion_requerida.php";
require "./php/connection.php";

// Consulta para obtener todas las publicaciones junto con sus fotos de perfil
$sql = "SELECT f.*, u.username as usuario_subio_username, u.foto_perfil
        FROM fotos_v f
        JOIN usuarios u ON f.usuario_subio_id = u.id
        WHERE f.eliminado = 0
        ORDER BY f.fecha_subido DESC;";

// Preparar y ejecutar la consulta
$stmt = $connection->prepare($sql);
$stmt->execute();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="icon" href="img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/stylesInicio.css">
</head>

<body>
    <header class="navbar">
        <div class="logo">
            <img src="img/Logo.png" alt="Logo">
            <span>Engineer's parts</span>
        </div>
        <nav>
            <a href="index.php"><span>Inicio</span></a>
            <a href="./vistas/buscador.html"><span>Buscador</span></a>
            <a href="./vistas/crear.php"><span>Crear</span></a>
        </nav>
        <div class="user-icon">
            <img src="./img/usuario.png" alt="Usuario" onclick="toggleDropdown()">
            <div class="dropdown-content" id="dropdownMenu">
                <a href="./vistas/perfil.php">Perfil</a>
                <a href="./vistas/editarperfil.php">Editar perfil</a>
                <a href="./php/logout.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <div class="usuario-publicacion">
        <?php
        if ($stmt->rowCount() > 0) {
            $publicaciones = $stmt->fetchAll();
            foreach ($publicaciones as $publicacion) {
                // Verificar si el usuario tiene una imagen de perfil personalizada
                $fotoPerfil = (!empty($publicacion['foto_perfil']))
                    ? './fotos_perfil/' . $publicacion['foto_perfil']
                    : './fotos_perfil/default.png';  // Ruta de la imagen por defecto
        ?>
                <div class="publicacion">
                    <div class="info-usuario">
                        <div class="nombre">
                            <img src="<?= $fotoPerfil ?>" alt="Perfil" style="width: 60px; height: 60px;">
                            <span>
                                <?= isset($publicacion["usuario_subio_username"]) ? htmlspecialchars($publicacion["usuario_subio_username"]) : 'Usuario desconocido'; ?>
                            </span>
                        </div>
                        <p><?= htmlspecialchars($publicacion["descripcion"]) ?></p>

                        <div class="foto-publicacion">
                            <img src="fotos/<?= htmlspecialchars($publicacion["secure_id"] . "." . $publicacion["extension"]) ?>" alt="<?= htmlspecialchars($publicacion["nombre_archivo"]) ?>">
                        </div>


                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
    <script src="./js/scriptInicio.js"></script>
</body>

</html>