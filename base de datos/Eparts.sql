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
    `status` varchar(20) NOT NULL DEFAULT 'En venta',
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

-- Creación de la tabla `Respuestas`.
CREATE TABLE respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comentario_id INT NOT NULL, -- Relaciona la respuesta con el comentario
    usuario_id INT NOT NULL, -- ID del usuario que respondió (opcional)
    respuesta TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comentario_id) REFERENCES comentarios(id) ON DELETE CASCADE
);

-- Creación de la tabla `comentarios`.
CREATE TABLE `comentarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `foto_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `comentario` TEXT NOT NULL,
  `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`foto_id`) REFERENCES `fotos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Creación de la vista `fotos_v`
CREATE OR REPLACE VIEW `fotos_v` AS
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
  `usuarios`.`email`,
  `fotos`.`status`,
  `fotos`.`precio`
FROM `fotos`
LEFT JOIN `usuarios` ON `fotos`.`usuario_subio_id` = `usuarios`.`id`
WHERE `fotos`.`eliminado` = 0 AND `fotos`.`status` = 'En venta';
