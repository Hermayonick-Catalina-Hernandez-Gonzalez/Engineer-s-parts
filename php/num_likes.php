<?php
require "./connection.php";

// Validar el parámetro id
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    $errores[] = "El parámetro id no fue recibido";
    echo json_encode(["errores" => $errores]);
    exit();  // Fin de la ejecución.
}

// Actualizar la consulta para seleccionar el campo `likes` desde la vista `fotos_v`
$sql = "SELECT COUNT(*) AS `likes` FROM `fotos_v` WHERE `id` = ? AND `eliminado` = 0";
$stmt = $connection->prepare($sql);
$stmt->execute([$id]);

$registro_like = $stmt->fetch();

if ($stmt->rowCount() > 0) {
    http_response_code(200);
    echo json_encode($registro_like["likes"]); // Devuelve el número de likes en formato JSON
    exit();
} else {
    http_response_code(200);
    echo json_encode(0); // Si no encuentra el registro, devolver 0 likes
    exit();
}
