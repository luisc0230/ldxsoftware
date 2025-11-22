CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen_url VARCHAR(255),
    video_url VARCHAR(255),
    precio DECIMAL(10, 2) DEFAULT 0.00,
    es_gratuito BOOLEAN DEFAULT FALSE,
    orden INT DEFAULT 0,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar algunos cursos de ejemplo
INSERT INTO cursos (titulo, descripcion, imagen_url, precio, es_gratuito, orden) VALUES 
('Introducción a la Programación', 'Aprende los fundamentos de la programación desde cero.', 'assets/images/curso-prog.jpg', 0.00, TRUE, 1),
('Desarrollo Web Full Stack', 'Domina HTML, CSS, JS, PHP y MySQL para crear aplicaciones web completas.', 'assets/images/curso-web.jpg', 199.00, FALSE, 2),
('Base de Datos Avanzada', 'Optimización y diseño de bases de datos relacionales.', 'assets/images/curso-db.jpg', 99.00, FALSE, 3);
