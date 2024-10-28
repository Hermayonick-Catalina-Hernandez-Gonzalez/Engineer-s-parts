<?php

// Extensiones válidas para los archivos de fotos que se van a subir.
$EXT_ARCHIVOS_FOTOS = ["png", "gif", "jpg", "jpeg"];

// Extensiones de archivos con su correspondiente content-type.
$CONTENT_TYPES_EXT = [
    "jpg" => "image/jpeg",
    "jpeg" => "image/jpeg",
    "gif" => "image/gif",
    "png" => "image/png",
    "json" => "application/json",
    "pdf" => "application/pdf",
    "bin" => "application/octet-stream"
];

// Configuraciones correspondientes a la conexión a base de datos.
define("DB_DSN", "mysql:host=127.0.0.1;port=3306;dbname=my_db;charset=utf8mb4;");
define("DB_USERNAME", "root"); 
define("DB_PASSWORD", "");
