<?php
require "./php/sesion_requerida.php";
require "./php/connection.php";

header("Content-Type: application/json");

$body = [];

$sqlUsuario = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sqlUsuario);
$stmt->execute([$usuarioID]);

$resultadoUsuario = $stmt->fetch();
$body["usuario_id"] = $resultadoUsuario["id"];
$body["username"] = $resultadoUsuario["username"];
$body["nombreCompleto"] = $nombreCompleto;
$body["genero"] = $resultadoUsuario["genero"];
$body["fecha_nacimiento"] = $resultadoUsuario["fecha_nacimiento"];
$body["foto_perfil"] = $resultadoUsuario["foto_perfil"];

$sqlSeguidores = "SELECT COUNT(*) FROM seguidores WHERE usuario_siguiendo_id = ?";
$stmt = $connection->prepare($sqlSeguidores);
$stmt->execute([$usuarioID]);

$body["cantidad_seguidores"] = $stmt->fetchColumn();

$sqlSiguiendo = "SELECT COUNT(*) FROM seguidores WHERE usuario_seguidor_id = ?";
$stmt = $connection->prepare($sqlSiguiendo);
$stmt->execute([$usuarioID]);

$body["cantidad_siguiendo"] = $stmt->fetchColumn();
?>