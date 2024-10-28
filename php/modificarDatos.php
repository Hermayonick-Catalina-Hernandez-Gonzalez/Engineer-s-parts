<?php
require "./config.php";
require "../php/sesion.php";  // Asegúrate que esta línea esté primero
require "../php/connection.php";

// Manejo de los datos del formulario
$nombre = filter_input(INPUT_POST, "nombre");
$apellidos = filter_input(INPUT_POST, "apellidos");
$genero = filter_input(INPUT_POST, "genero");
$fecha_Nac = filter_input(INPUT_POST, "fecha-nacimiento");
$email = filter_input(INPUT_POST, "correo");

// Verifica que se haya enviado el nombre o el email
if (!$nombre && !$email) {
    $mensaje = "No se enviaron los datos requeridos";
    require "../vistas/editarperfil.php";
    exit();
}

// Preparación de la consulta SQL para actualizar datos
$sql = "UPDATE usuarios SET nombre = ?, email = ?";
$params = [$nombre, $email];

// Agregar otros campos si están presentes
if ($apellidos) {
    $sql .= ", apellidos = ?";
    $params[] = $apellidos;
}

if ($genero) {
    $sql .= ", genero = ?";
    $params[] = $genero;
}

if ($fecha_Nac) {
    $sql .= ", fecha_nacimiento = ?";
    $params[] = $fecha_Nac;
}

// Manejo de la imagen de perfil
if (!empty($_FILES) && isset($_FILES["imagen"]) && !empty($_FILES["imagen"]["name"])) {
    $archivoSubido = $_FILES["imagen"];
    $nombreArchivo = $archivoSubido["name"];  
    $nombreArchivoParts = explode(".", $nombreArchivo);
    $extension = strtolower(end($nombreArchivoParts));

    if (in_array($extension, $EXT_ARCHIVOS_FOTOS)) {
        $ruta = "C:/xampp/htdocs/UPVBOOKS/fotos_perfil/" . $usuarioID . "_" . $nombre . "." . $extension;

        // Verificar si el directorio existe
        if (!file_exists("C:/xampp/htdocs/UPVBOOKS/fotos_perfil/")) {
            // Crear el directorio si no existe
            if (!mkdir("C:/xampp/htdocs/UPVBOOKS/fotos_perfil/", 0777, true)) {
                $mensaje = "El directorio de fotos no existe y no se pudo crear.";
                require "../vistas/editarperfil.php";
                exit();
            }
        }

        // Borrar archivo existente si es necesario
        if (file_exists($ruta)) {
            $seBorro = unlink($ruta);
            if (!$seBorro) {
                $mensaje = "Hay una imagen de perfil que no fue posible borrar";
                require "../vistas/editarperfil.php";
                exit();
            }
        }

        // Mover el archivo subido
        $seGuardo = move_uploaded_file($archivoSubido["tmp_name"], $ruta);
        if (!$seGuardo) {
            $mensaje = "No se logró guardar el archivo";
            require "../vistas/editarperfil.php";
            exit();
        } else {
            $sql .= ", foto_perfil = ?";
            $params[] = $usuarioID . "_" . $nombre . "." . $extension;
        }
    } else {
        $mensaje = "Tipo de archivo no válido, solo se admiten imágenes";
        require "../vistas/editarperfil.php";
        exit();
    }
}

$sql .= " WHERE id = ?";
$params[] = $usuarioID;

$stmt = $connection->prepare($sql);
if ($stmt->execute($params)) {
    header("Location: ../vistas/perfil.php");
} else {
    $mensaje = "No se pudo modificar la información";
    require "../vistas/editarperfil.php";
    exit();
}
?>
