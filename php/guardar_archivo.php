<?php

require "./config.php";
require "./sesion_requerida.php";
require "./connection.php";

header("Content-Type: application/json");

$errores = [];
$now = new DateTime();

// Validar que el usuario esté autenticado
if (!$usuarioAutenticado) {
    $errores[] = "El usuario no se ha autenticado";
    echo json_encode(["errores" => $errores]);
    exit();
}

// Validar la existencia del archivo
if (empty($_FILES) || !isset($_FILES["foto"]) || empty($_FILES["foto"]["name"])) {
    $errores[] = "No se recibió el archivo a publicar.";
    echo json_encode(["errores" => $errores]);
    exit();
}

// Obtener y sanitizar los datos del formulario
$archivoSubido = $_FILES["foto"];
$nombreProducto = filter_input(INPUT_POST, "nombre_producto", FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING);
$precio = filter_input(INPUT_POST, "precio", FILTER_VALIDATE_FLOAT);
$descripcion = filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_STRING);

// Validar los datos del formulario
if (!$nombreProducto || !$estado || $precio === false) {
    $errores[] = "Datos del formulario incompletos o inválidos.";
    echo json_encode(["errores" => $errores]);
    exit();
}

// Obtener información del archivo
$nombreArchivo = $archivoSubido["name"];
$nombreArchivoParts = explode(".", $nombreArchivo);
$extension = strtolower(end($nombreArchivoParts));
$tamaño = $archivoSubido["size"];

// Validar la extensión del archivo
if (!in_array($extension, $EXT_ARCHIVOS_FOTOS)) {
    $errores[] = "El tipo de archivo no es el correcto, solo agregue imágenes.";
    echo json_encode(["errores" => $errores]);
    exit();
}

// Generar un nombre único para el archivo
$nombreArchivoGuardado = strtoupper(bin2hex(random_bytes(32)));
$ruta = "C:/xampp/htdocs/xampp/Engineer's parts/fotos/" . $nombreArchivoGuardado . "." . $extension;

// Mover el archivo subido a la carpeta de destino
$seGuardo = move_uploaded_file($archivoSubido["tmp_name"], $ruta);

if (!$seGuardo) {
    $errores[] = "Ocurrió un error al guardar el archivo.";
    echo json_encode(["errores" => $errores]);
    exit();
}

// Insertar los datos en la base de datos
$fechaSubido = $now->format("Y-m-d H:i:s");

$sqlCmd = "INSERT INTO fotos (secure_id, extension, usuario_subio_id, nombre_archivo, tamaño, descripcion, fecha_subido, nombre_producto, estado, precio, status) 
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'En venta')";
$sqlParam = [
    $nombreArchivoGuardado,
    $extension,
    $usuarioID,
    $nombreArchivo,
    $tamaño,
    $descripcion,
    $fechaSubido,
    $nombreProducto,
    $estado,
    $precio
];

$stmt = $connection->prepare($sqlCmd);
if ($stmt->execute($sqlParam)) {
    echo json_encode(["mensaje" => "Registro guardado exitosamente", "success" => true]);
    exit();
} else {
    echo json_encode(["errores" => ["Error al ejecutar la consulta SQL."]]);
    exit();
}
