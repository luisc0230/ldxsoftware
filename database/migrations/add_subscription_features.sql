-- Agregar campos adicionales a la tabla planes
ALTER TABLE `planes` 
ADD COLUMN `precio_trimestral` DECIMAL(10,2) DEFAULT NULL AFTER `precio_anual`,
ADD COLUMN `precio_lifetime` DECIMAL(10,2) DEFAULT NULL AFTER `precio_trimestral`,
ADD COLUMN `descuento_porcentaje` INT DEFAULT 0 AFTER `precio_lifetime`,
ADD COLUMN `es_recomendado` TINYINT(1) DEFAULT 0 AFTER `descuento_porcentaje`,
ADD COLUMN `orden` INT DEFAULT 0 AFTER `es_recomendado`;

-- Agregar campo rol a usuarios para identificar administradores
ALTER TABLE `usuarios`
ADD COLUMN `rol` ENUM('usuario', 'admin') DEFAULT 'usuario' AFTER `estado`;

-- Insertar planes de ejemplo con descuentos
INSERT INTO `planes` (`nombre`, `descripcion`, `precio_mensual`, `precio_trimestral`, `precio_anual`, `precio_lifetime`, `descuento_porcentaje`, `es_recomendado`, `caracteristicas`, `estado`, `orden`) VALUES
('Mensual', 'Plan básico mensual', 10.00, NULL, NULL, NULL, 50, 0, 'Acceso a todos los cursos|Proyectos prácticos|Soporte en WhatsApp', 'activo', 1),
('Trimestral', 'Plan trimestral con descuento', NULL, 27.00, NULL, NULL, 50, 0, 'Todo lo del plan mensual|Soporte prioritario en WhatsApp|Sorteos exclusivos mensuales', 'activo', 2),
('Anual', 'Plan anual - Más popular', NULL, NULL, 95.00, NULL, 50, 1, 'Todo lo del plan trimestral|Acceso prioritario a nuevo contenido|Mentoría grupal mensual', 'activo', 3),
('Lifetime', 'Acceso de por vida', NULL, NULL, NULL, 269.00, 0, 0, 'Todo lo del plan anual|Comunidad exclusiva|Un pago para toda la vida', 'activo', 4);

-- Crear tabla para características de planes (más flexible)
CREATE TABLE IF NOT EXISTS `plan_caracteristicas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `plan_id` INT NOT NULL,
  `caracteristica` VARCHAR(255) NOT NULL,
  `incluido` TINYINT(1) DEFAULT 1,
  `orden` INT DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_plan` (`plan_id`),
  CONSTRAINT `fk_caracteristica_plan` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
