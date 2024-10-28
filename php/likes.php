<?php
require "./connection.php";
require "./sesion_requerida.php";

$data = json_decode(file_get_contents('php://input'), true);

$publicacion_id = $data['id'] ?? null;

if (!$publicacion_id) {
    http_response_code(400);
    $errores[] = "No se encontro la publicacion";
    echo json_encode(["errores" => $errores]);
    exit();
}


$sql = "SELECT id, eliminado FROM fotos_likes WHERE foto_id = ? AND usuario_dio_like_id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$publicacion_id, $usuarioID]);

$registro_like = $stmt->fetch();

if ($stmt->rowCount() > 0) {
    //Ya hay un registro, modificarlo
    if ($registro_like["eliminado"]) {
        //Esta eliminado, dale clic otra vez es ponerlo de nuevo
        $sql = "UPDATE fotos_likes SET eliminado = 0 WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$publicacion_id]);

        http_response_code(200);
        $body[] = "Se registro con exito";
        echo json_encode(["body" => $body]);
        exit();
    } else {
        $sql = "UPDATE fotos_likes SET eliminado = 1 WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$publicacion_id]);

        http_response_code(200);
        $body[] = "Se registro con exito";
        echo json_encode(["body" => $body]);
        exit();
    }
} else {
    $sql = "INSERT INTO fotos_likes ( foto_id, usuario_dio_like_id, fecha_hora, eliminado) VALUES (?, ?, NOW(), 0)";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$publicacion_id, $usuarioID]);

    http_response_code(200);
    $body[] = "Se registro con exito";
    echo json_encode(["body" => $body]);
    exit();
}
