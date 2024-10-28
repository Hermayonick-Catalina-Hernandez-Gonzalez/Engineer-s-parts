<?php

// Import de los archivos de código necesarios para la ejecución 
require "./config.php";  // Configuraciones generales de la aplicación
require "./sesion.php";  // para acceder a las variables de sesión
require "./connection.php";  // para el acceso a datos

// Indicamos que la respuesta es de tipo JSON, porque la petición a esta ejecución
// será por AJAX

$errores = [];  // Array para guardar los errores obtenidos.
$now = new DateTime();  // fecha hora actual de la ejecución

// Se valida que el usuario está autenticado, de lo contrario se regresa un
// json con el error (usuario no autenticado)
if (!$usuarioAutenticado) {
    $errores[] = "El usuario no se ha autenticado";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución.
}

// Se obtiene el parámetro "id" enviado en la petición post
$id = filter_input(INPUT_POST, "publicacion_id", FILTER_VALIDATE_INT);
if (!$id) {
    $errores[] = "El parámetro id no esta especificado";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución.
}

// Consulta a DB, tabla de archivos, el registro del archivo por el id
$sqlCmd = "SELECT * FROM fotos WHERE id = ?";
$sqlParam = [$id];
$stmt = $connection->prepare($sqlCmd);
$stmt->execute($sqlParam);
$archivo = $stmt->fetch();

// Si no existe el registro del archivo según el id proporcionado, se regresa
// un error indicando esto
if (!$archivo) {
    $errores[] = "En los registros no se ha encontrado el archivo que se desea eliminar";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución.
}

// Si el archivo ya ha sido borrado, no hay necesidad de volverlo a borrar,
// por lo que se termina la ejecución y se regresa un array vacio de errores
if ($archivo["eliminado"]) {
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución.
}

// Realizamos la operación de borrar del archivo en DB, la cual será una 
// operación "soft delete", marcando un campo (fecha_borrado) para indicar que
// ese registro ya fue borrado
$sqlCmd = "UPDATE fotos SET eliminado = ? WHERE id = ?";
$fechaBorrado = $now->format("Y-m-d H:i:s");
$sqlParam = [1, $id];
$stmt = $connection->prepare($sqlCmd);
$stmt->execute($sqlParam);

$stmt = $connection->prepare($sqlCmd);  // obtenemos el statement de la ejecución
$stmt->execute($sqlParam);  // ejecutamos el statement con los parámetros

// Obtenemos el id del registro insertado en archivos_log_general, por si lo ocupamos
$idEnLog = (int)$connection->lastInsertId();  // la función regresa string, convertimos a int

// Regresamos la respuesta JSON que lo único que contendrá es un array empty
// de los errores, que si llegamos aquí no hay errores
header("Location: ../vistas/perfil.php");
