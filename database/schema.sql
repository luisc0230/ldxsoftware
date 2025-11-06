-- Base de datos para LDX Software
-- Ejecutar este script en phpMyAdmin o MySQL

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `google_id` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `foto` VARCHAR(500) DEFAULT NULL,
  `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `estado` ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `google_id` (`google_id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de planes de suscripción
CREATE TABLE IF NOT EXISTS `planes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT,
  `precio_mensual` DECIMAL(10,2) NOT NULL,
  `precio_anual` DECIMAL(10,2) DEFAULT NULL,
  `caracteristicas` TEXT,
  `estado` ENUM('activo', 'inactivo') DEFAULT 'activo',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar planes por defecto
INSERT INTO `planes` (`nombre`, `descripcion`, `precio_mensual`, `precio_anual`, `caracteristicas`) VALUES
('Básico', 'Plan básico para empezar', 29.00, 290.00, 'Hosting básico, 1 dominio, Soporte email'),
('Profesional', 'Plan para profesionales', 59.00, 590.00, 'Hosting avanzado, 5 dominios, Soporte prioritario, SSL gratis'),
('Empresarial', 'Plan para empresas', 99.00, 990.00, 'Hosting ilimitado, Dominios ilimitados, Soporte 24/7, SSL gratis, CDN');

-- Tabla de suscripciones
CREATE TABLE IF NOT EXISTS `suscripciones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `plan_id` INT(11) NOT NULL,
  `tipo_pago` ENUM('mensual', 'anual') NOT NULL DEFAULT 'mensual',
  `precio_pagado` DECIMAL(10,2) NOT NULL,
  `estado` ENUM('pendiente', 'activa', 'cancelada', 'expirada', 'suspendida') DEFAULT 'pendiente',
  `fecha_inicio` DATETIME DEFAULT NULL,
  `fecha_fin` DATETIME DEFAULT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `auto_renovacion` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_plan` (`plan_id`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `fk_suscripcion_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_suscripcion_plan` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de pagos (historial de transacciones)
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `suscripcion_id` INT(11) NOT NULL,
  `usuario_id` INT(11) NOT NULL,
  `monto` DECIMAL(10,2) NOT NULL,
  `moneda` VARCHAR(3) DEFAULT 'PEN',
  `metodo_pago` VARCHAR(50) DEFAULT 'culqi',
  `culqi_charge_id` VARCHAR(255) DEFAULT NULL,
  `culqi_token` VARCHAR(255) DEFAULT NULL,
  `estado` ENUM('pendiente', 'completado', 'fallido', 'reembolsado') DEFAULT 'pendiente',
  `fecha_pago` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datos_adicionales` TEXT,
  PRIMARY KEY (`id`),
  KEY `idx_suscripcion` (`suscripcion_id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_culqi_charge` (`culqi_charge_id`),
  CONSTRAINT `fk_pago_suscripcion` FOREIGN KEY (`suscripcion_id`) REFERENCES `suscripciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pago_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de sesiones de checkout (para guardar el estado antes del pago)
CREATE TABLE IF NOT EXISTS `checkout_sessions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) DEFAULT NULL,
  `plan_id` INT(11) NOT NULL,
  `tipo_pago` ENUM('mensual', 'anual') NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `session_id` VARCHAR(255) NOT NULL,
  `estado` ENUM('pendiente', 'completado', 'expirado') DEFAULT 'pendiente',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `fk_checkout_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
