-- Creación de la base de datos.
CREATE DATABASE `Eparts`;

USE Eparts;

-- Creación de table usuarios.
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `email` varchar(512) NOT NULL,
  `password_encrypted` varchar(128) NOT NULL,
  `password_salt` varchar(64) NOT NULL,
  `fecha_hora_registro` datetime NOT NULL,
  `activo` tinyint(4) NOT NULL,
  `foto_perfil` BLOB,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Creación de la tabla `fotos`
CREATE TABLE `fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `secure_id` varchar(64) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `usuario_subio_id` int(11) NOT NULL,
  `nombre_archivo` varchar(256) NOT NULL,
  `tamaño` int(11) NOT NULL,
  `descripcion` varchar(1024) DEFAULT NULL,
  `fecha_subido` datetime NOT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  `nombre_producto` varchar(255) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Creación de la tabla `fotos_likes`
CREATE TABLE `fotos_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foto_id` int(11) NOT NULL,
  `usuario_dio_like_id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Creación de la tabla `seguidores`
CREATE TABLE seguidores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_seguidor_id INT NOT NULL,
    usuario_siguiendo_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    eliminado TINYINT(1) DEFAULT 0
);

-- Creación de la vista `fotos_v`
CREATE VIEW `fotos_v` AS
SELECT
  `fotos`.`id`,
  `fotos`.`usuario_subio_id`,
  `fotos`.`secure_id`,
  `fotos`.`extension`,
  `fotos`.`nombre_archivo`,
  `fotos`.`tamaño`,
  `fotos`.`descripcion`,
  `fotos`.`fecha_subido`,
  `fotos`.`eliminado`,
  `fotos`.`usuario_subio_id` AS `user_id`,
  `usuarios`.`foto_perfil`,
  `usuarios`.`username`,
  `usuarios`.`email`
FROM `fotos`
LEFT JOIN `usuarios` ON `fotos`.`usuario_subio_id` = `usuarios`.`id`
WHERE `fotos`.`eliminado` = 0;