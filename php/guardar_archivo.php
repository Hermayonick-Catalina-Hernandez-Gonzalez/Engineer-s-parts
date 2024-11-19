<?php

// Importa archivos necesarios para la configuración, autenticación y conexión a la base de datos
require "./config.php"; // Archivo de configuración (constantes, variables globales, etc.)
require "./sesion_requerida.php"; // Verifica si el usuario está autenticado
require "./connection.php"; // Conexión a la base de datos

// Configura la cabecera para devolver la respuesta en formato JSON
header("Content-Type: application/json");

// Inicializa un array para capturar posibles errores
$errores = [];
// Crea una instancia de DateTime para manejar la fecha y hora actuales
$now = new DateTime();

// Validar si el usuario está autenticado
if (!$usuarioAutenticado) {
    // Agrega un error si el usuario no está autenticado
    $errores[] = "El usuario no se ha autenticado";
    // Devuelve los errores como respuesta en JSON y finaliza el script
    echo json_encode(["errores" => $errores]);
    exit();
}

// Validar si se recibió un archivo en la solicitud
if (empty($_FILES) || !isset($_FILES["foto"]) || empty($_FILES["foto"]["name"])) {
    // Agrega un error si no se recibió un archivo válido
    $errores[] = "No se recibió el archivo a publicar.";
    // Devuelve los errores en formato JSON y finaliza el script
    echo json_encode(["errores" => $errores]);
    exit();
}

// Asigna el archivo subido a una variable para su manejo
$archivoSubido = $_FILES["foto"];

// Filtrar y validar los datos enviados en el formulario
$nombreProducto = filter_input(INPUT_POST, "nombre_producto", FILTER_SANITIZE_STRING); // Filtra el nombre del producto
$estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING); // Filtra el estado del producto
$precio = filter_input(INPUT_POST, "precio", FILTER_VALIDATE_FLOAT); // Valida que el precio sea un número flotante
$descripcion = filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_STRING); // Filtra la descripción del producto

// Verificar si los campos obligatorios están completos y son válidos
if (!$nombreProducto || !$estado || $precio === false || $precio < 0) {
    // Agrega un error si hay datos incompletos o inválidos
    $errores[] = "Datos del formulario incompletos o inválidos. Asegúrate de que el precio sea un número válido.";
    // Devuelve los errores en formato JSON y finaliza el script
    echo json_encode(["errores" => $errores]);
    exit();
}

// Procesar el archivo subido
$nombreArchivo = $archivoSubido["name"]; // Nombre original del archivo
$nombreArchivoParts = explode(".", $nombreArchivo); // Divide el nombre del archivo en partes usando el punto como delimitador
$extension = strtolower(end($nombreArchivoParts)); // Obtiene la extensión del archivo en minúsculas
$tamaño = $archivoSubido["size"]; // Obtiene el tamaño del archivo en bytes

// Validar que la extensión del archivo sea válida
if (!in_array($extension, $EXT_ARCHIVOS_FOTOS)) {
    // Agrega un error si la extensión no está permitida
    $errores[] = "El tipo de archivo no es el correcto, solo agregue imágenes.";
    // Devuelve los errores en formato JSON y finaliza el script
    echo json_encode(["errores" => $errores]);
    exit();
}

// Generar un nombre único para guardar el archivo
$nombreArchivoGuardado = strtoupper(bin2hex(random_bytes(32))); // Genera un nombre aleatorio en hexadecimal
$ruta = "C:/xampp/htdocs/Engineer-s-parts/fotos/" . $nombreArchivoGuardado . "." . $extension; // Construye la ruta completa para guardar el archivo

// Mover el archivo subido a la ubicación final
$seGuardo = move_uploaded_file($archivoSubido["tmp_name"], $ruta); // Mueve el archivo desde su ubicación temporal a la definitiva

if (!$seGuardo) {
    // Agrega un error si no se pudo guardar el archivo
    $errores[] = "Ocurrió un error al guardar el archivo.";
    // Devuelve los errores en formato JSON y finaliza el script
    echo json_encode(["errores" => $errores]);
    exit();
}

// Registrar los datos en la base de datos
$fechaSubido = $now->format("Y-m-d H:i:s"); // Formatea la fecha y hora actuales para la base de datos

// Comando SQL para insertar los datos en la tabla `fotos`
$sqlCmd = "INSERT INTO fotos (secure_id, extension, usuario_subio_id, nombre_archivo, tamaño, descripcion, fecha_subido, nombre_producto, estado, precio) 
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Parámetros para la consulta SQL
$sqlParam = [
    $nombreArchivoGuardado, // ID seguro del archivo
    $extension, // Extensión del archivo
    $usuarioID, // ID del usuario que subió el archivo
    $nombreArchivo, // Nombre original del archivo
    $tamaño, // Tamaño del archivo en bytes
    $descripcion, // Descripción del producto
    $fechaSubido, // Fecha de subida
    $nombreProducto, // Nombre del producto
    $estado, // Estado del producto
    $precio // Precio del producto
];

// Prepara la consulta SQL
$stmt = $connection->prepare($sqlCmd);

// Ejecutar la consulta y verificar el resultado
if ($stmt->execute($sqlParam)) {
    // Si la consulta se ejecuta correctamente, devuelve un mensaje de éxito
    echo json_encode(["mensaje" => "Registro guardado exitosamente"]);
} else {
    // Si ocurre un error al ejecutar la consulta, devuelve un error
    echo json_encode(["errores" => ["Error al ejecutar la consulta SQL."]]);
    exit();
}
