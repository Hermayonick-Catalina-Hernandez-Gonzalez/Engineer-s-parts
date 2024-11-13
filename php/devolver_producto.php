<?php
require "../php/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $sql = "UPDATE fotos SET status = 'En venta' WHERE id = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt->execute([$id])) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
