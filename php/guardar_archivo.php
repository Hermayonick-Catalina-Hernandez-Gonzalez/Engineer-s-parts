<?php

// Importar los archivos necesarios para la ejecución
require "./config.php";  // Configuraciones generales de la aplicación
require "./sesion_requerida.php";  // Para acceder a las variables de sesión
require "./connection.php";  // Para el acceso a datos

// Indicamos que la respuesta es de tipo JSON, porque la petición será por AJAX
header("Content-Type: application/json");

$errores = [];  // Array para guardar los errores obtenidos.
$now = new DateTime();  // Fecha y hora actual de la ejecución

// Validar que el usuario está autenticado
if (!$usuarioAutenticado) {
    $errores[] = "El usuario no se ha autenticado";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución de este archivo PHP
}

// Validar que se haya enviado el archivo en el request
if (empty($_FILES) || !isset($_FILES["foto"]) || empty($_FILES["foto"]["name"])) {
    $errores[] = "No se recibió el archivo a publicar.";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución de este archivo PHP
}

// Obtener el array asociativo con los datos del archivo subido
$archivoSubido = $_FILES["foto"];

// Obtener la descripción del archivo
$descripcion = filter_input(INPUT_POST, "descripcion");
$descripcion = $descripcion && strlen(trim($descripcion)) ? // Valor establecido?
    trim($descripcion) : NULL;  // SI -> Quitamos espacios en blanco : NO -> NULL

// Obtener los datos del archivo que se subió
$nombreArchivo = $archivoSubido["name"];  // Nombre del archivo original
$nombreArchivoParts = explode(".", $nombreArchivo);  // Obtener array por "."
$extension = strtolower($nombreArchivoParts[count($nombreArchivoParts) - 1]);
$tamaño = $archivoSubido["size"];  // Tamaño del archivo subido

// Validación del tipo de archivo que se subió
if (!in_array($extension, $EXT_ARCHIVOS_FOTOS)) {
    $errores[] = "El tipo de archivo no es el correcto, solo agregue imágenes.";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución de este archivo PHP
}

// Generar un nombre de archivo aleatorio
$nombreArchivoGuardado = strtoupper(bin2hex(random_bytes(32)));
$ruta = "C:/xampp/htdocs/xampp/UPVBOOKS/fotos/" . $nombreArchivoGuardado . "." . $extension;  
$seGuardo = move_uploaded_file($archivoSubido["tmp_name"], $ruta);

// Si no se guardó el archivo, regresar un error
if (!$seGuardo) {
    $errores[] = "Ocurrió un error al guardar el archivo.";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución de este archivo PHP
}

// Obtener el SHA256 del archivo
$hashSha256 = strtoupper(hash_file("sha256", $ruta));

// Obtener la fecha-hora actual en string con formato yyyy-MM-dd HH:mm:ss
$fechaSubido = $now->format("Y-m-d H:i:s");

// Ejecutar la operación de insert del registro del archivo en DB
$sqlCmd =  // Sentencia SQL del INSERT
    "INSERT INTO fotos (secure_id, extension, usuario_subio_id, nombre_archivo, tamaño, descripcion, fecha_subido) VALUES (?, ?, ?, ?, ?, ?, ?)";
$sqlParam = [  // Array con los datos a guardar
    $nombreArchivoGuardado,
    $extension,
    $usuarioID,
    $nombreArchivo,
    $tamaño,
    $descripcion,
    $fechaSubido
];

$stmt = $connection->prepare($sqlCmd);  // Obtener el statement de la ejecución
$stmt->execute($sqlParam);

// Obtener el id del registro que insertamos en tabla archivos
$id = (int)$connection->lastInsertId(); // Convertir a int

// Regresar una respuesta como un JSON string
$resObj = [
    "errores" => $errores,
    "archivo" => [
        "id" => $id,
        "nombreArchivo" => $nombreArchivo,
        "extension" => $extension,
        "descripcion" => $descripcion,
        "nombreArchivoGuardado" => $nombreArchivoGuardado,
        "fechaSubido" => $fechaSubido,
        "tamaño" => $tamaño
    ]
];

echo json_encode($resObj);  
