CREATE TABLE IF NOT EXISTS `notificacions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL COMMENT 'ID del usuario que recibe la notificación',
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` varchar(50) NOT NULL DEFAULT 'info' COMMENT 'Tipo de notificación: info, success, warning, error',
  `icono` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL COMMENT 'URL opcional para redirigir al hacer clic',
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunas notificaciones de ejemplo (ajustar id_usuario según los usuarios existentes)
-- Las siguientes instrucciones son opcionales, puedes comentarlas si prefieres crear las notificaciones desde la aplicación
/*
INSERT INTO `notificacions` (`id_usuario`, `titulo`, `mensaje`, `tipo`, `icono`, `url`, `leida`, `created_at`, `updated_at`) VALUES
(1, 'Asesoría confirmada', 'El asesor ha confirmado tu asesoría para Matemáticas.', 'success', 'bi-check-circle', '/asesorias', 0, NOW(), NOW()),
(1, 'Asesoría iniciada', 'Tu asesoría de Programación Web ha comenzado.', 'info', 'bi-play-circle', '/asesorias', 0, NOW(), NOW()),
(1, 'Asesoría cancelada', 'El asesor ha cancelado tu asesoría para Bases de Datos. Motivo: Problemas de salud.', 'warning', 'bi-x-circle', '/asesorias', 0, NOW(), NOW()),
(2, 'Nueva solicitud', 'El estudiante ha solicitado una asesoría para Física.', 'info', 'bi-clipboard-plus', '/asesoriasa', 0, NOW(), NOW());
*/
