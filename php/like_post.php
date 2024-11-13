<?php
require "./connection.php";
require "./sesion_requerida.php";

$data = json_decode(file_get_contents("php://input"), true);
$foto_id = $data['foto_id'];
$usuario_id = $_SESSION['id'];

if (isset($foto_id)) {
    // Verifica si el usuario ya dio "me gusta"
    $checkLike = $connection->prepare("SELECT * FROM fotos_likes WHERE foto_id = :foto_id AND usuario_dio_like_id = :usuario_id");
    $checkLike->bindParam(':foto_id', $foto_id);
    $checkLike->bindParam(':usuario_id', $usuario_id);
    $checkLike->execute();

    if ($checkLike->rowCount() == 0) {
        // Si no dio "me gusta" aún, insertarlo
        $stmt = $connection->prepare("INSERT INTO fotos_likes (foto_id, usuario_dio_like_id, fecha_hora) VALUES (:foto_id, :usuario_id, NOW())");
        $stmt->bindParam(':foto_id', $foto_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
    } else {
        // Si ya dio "me gusta", eliminarlo
        $stmt = $connection->prepare("DELETE FROM fotos_likes WHERE foto_id = :foto_id AND usuario_dio_like_id = :usuario_id");
        $stmt->bindParam(':foto_id', $foto_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
    }

    // Obtener el nuevo número de "me gusta"
    $likeCount = $connection->prepare("SELECT COUNT(*) as likes_count FROM fotos_likes WHERE foto_id = :foto_id");
    $likeCount->bindParam(':foto_id', $foto_id);
    $likeCount->execute();
    $count = $likeCount->fetchColumn();

    echo json_encode(['success' => true, 'likes_count' => $count]);
} else {
    echo json_encode(['success' => false]);
}
