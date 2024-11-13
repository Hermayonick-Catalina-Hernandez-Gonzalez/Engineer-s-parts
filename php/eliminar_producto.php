<?php
require "../php/connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $sql = "DELETE FROM fotos WHERE id = ?";
    $stmt = $connection->prepare($sql);
    if ($stmt->execute([$id])) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>