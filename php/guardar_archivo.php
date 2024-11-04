
<?php

require "./config.php";
require "./sesion_requerida.php";
require "./connection.php";

header("Content-Type: application/json");

$errores = [];
$now = new DateTime();

// Validar autenticación
if (!$usuarioAutenticado) {
    $errores[] = "El usuario no se ha autenticado";
    echo json_encode(["errores" => $errores]);
    exit();
}

// Validar el archivo subido
if (empty($_FILES) || !isset($_FILES["foto"]) || empty($_FILES["foto"]["name"])) {
    $errores[] = "No se recibió el archivo a publicar.";
    echo json_encode(["errores" => $errores]);
    exit();
}

$archivoSubido = $_FILES["foto"];
$nombreProducto = filter_input(INPUT_POST, "nombre_producto", FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING);
$precio = filter_input(INPUT_POST, "precio", FILTER_VALIDATE_FLOAT);
$descripcion = filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_STRING);

if (!$nombreProducto || !$estado || $precio === false) {
    $errores[] = "Datos del formulario incompletos o inválidos.";
    echo json_encode(["errores" => $errores]);
    exit();
}

$nombreArchivo = $archivoSubido["name"];
$nombreArchivoParts = explode(".", $nombreArchivo);
$extension = strtolower(end($nombreArchivoParts));
$tamaño = $archivoSubido["size"];

if (!in_array($extension, $EXT_ARCHIVOS_FOTOS)) {
    $errores[] = "El tipo de archivo no es el correcto, solo agregue imágenes.";
    echo json_encode(["errores" => $errores]);
    exit();
}

$nombreArchivoGuardado = strtoupper(bin2hex(random_bytes(32)));
$ruta = "C:/xampp/htdocs/xampp/Engineer's parts/fotos/" . $nombreArchivoGuardado . "." . $extension;
$seGuardo = move_uploaded_file($archivoSubido["tmp_name"], $ruta);

if (!$seGuardo) {
    $errores[] = "Ocurrió un error al guardar el archivo.";
    echo json_encode(["errores" => $errores]);
    exit();
}

$fechaSubido = $now->format("Y-m-d H:i:s");

$sqlCmd = "INSERT INTO fotos (secure_id, extension, usuario_subio_id, nombre_archivo, tamaño, descripcion, fecha_subido, nombre_producto, estado, precio) 
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
