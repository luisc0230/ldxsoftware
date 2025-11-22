-- Add new columns to cursos table
-- Note: If these columns already exist, these statements will fail. You can ignore those specific errors.
ALTER TABLE cursos ADD COLUMN slug VARCHAR(255) UNIQUE;
ALTER TABLE cursos ADD COLUMN instructor_nombre VARCHAR(255) DEFAULT 'LDX Instructor';
ALTER TABLE cursos ADD COLUMN instructor_bio TEXT;
ALTER TABLE cursos ADD COLUMN instructor_avatar VARCHAR(255) DEFAULT 'assets/images/default-avatar.png';
ALTER TABLE cursos ADD COLUMN nivel VARCHAR(50) DEFAULT 'Intermedio';
ALTER TABLE cursos ADD COLUMN duracion_total VARCHAR(50) DEFAULT '0h 0m';
ALTER TABLE cursos ADD COLUMN stack TEXT COMMENT 'JSON array of technologies';
ALTER TABLE cursos ADD COLUMN lo_que_aprenderas TEXT COMMENT 'JSON array of learning points';
ALTER TABLE cursos ADD COLUMN total_clases INT DEFAULT 0;

-- Create modulos table
CREATE TABLE IF NOT EXISTS modulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Create clases table
CREATE TABLE IF NOT EXISTS clases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modulo_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    video_url VARCHAR(255),
    duracion VARCHAR(50),
    orden INT DEFAULT 0,
    es_gratuita BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (modulo_id) REFERENCES modulos(id) ON DELETE CASCADE
);

-- Insert sample data for the Astro course
INSERT INTO cursos (titulo, slug, descripcion, imagen_url, precio, instructor_nombre, instructor_bio, instructor_avatar, nivel, duracion_total, stack, lo_que_aprenderas, total_clases)
VALUES (
    'Curso de Astro y Headless CMS',
    'astro-cms-headless',
    'Astro ha traído un soplo de aire fresco al desarrollo web. Es rápido, sencillo y está pensado para hacer sitios de contenido sin peleas con el rendimiento ni con el mantenimiento. Los Headless CMS, por su parte, nos dan una forma limpia de gestionar el contenido sin casarnos con ningún framework de UI.',
    'assets/images/curso-astro.jpg',
    0,
    'Braulio Diez',
    'Technical Lead at Lemoncode & basefactor. Desarrollador JavaScript/TypeScript con experiencia en .NET.',
    'assets/images/braulio.webp',
    'Intermedio',
    '2h 59m',
    '["Astro", "Content Island", "React", "TypeScript", "Tailwind"]',
    '["Conceptos de SSG y Astro", "Fences: servidor vs cliente", "Colecciones y rutas dinámicas", "Componentes y layouts", "Qué es un Headless CMS", "Integrar Content Island", "Construir un blog completo", "Client Islands con React", "Server Actions", "Formularios con Resend", "View Transitions", "SSR y Server Streaming"]',
    23
) ON DUPLICATE KEY UPDATE titulo=titulo;

-- Get the ID of the inserted course
SET @curso_id = (SELECT id FROM cursos WHERE slug = 'astro-cms-headless');

-- Insert Modules
INSERT INTO modulos (curso_id, titulo, orden) VALUES 
(@curso_id, 'Introducción', 1),
(@curso_id, 'Fundamentos de Astro', 2),
(@curso_id, 'Proyecto: Astro + Headless CMS', 3);

-- Get Module IDs
SET @mod1 = (SELECT id FROM modulos WHERE curso_id = @curso_id AND titulo = 'Introducción');
SET @mod2 = (SELECT id FROM modulos WHERE curso_id = @curso_id AND titulo = 'Fundamentos de Astro');
SET @mod3 = (SELECT id FROM modulos WHERE curso_id = @curso_id AND titulo = 'Proyecto: Astro + Headless CMS');

-- Insert Lessons for Module 1
INSERT INTO clases (modulo_id, titulo, duracion, orden, video_url) VALUES
(@mod1, 'Presentación del curso', '29s', 1, 'https://videos.midudev.com/astro-cms-headless/01-00-presentacion/playlist.m3u8'),
(@mod1, 'Conceptos de SSG y Astro', '8m 6s', 2, 'https://videos.midudev.com/astro-cms-headless/01-01-conceptos-ssg/playlist.m3u8'),
(@mod1, '¡Practica lo que aprendimos!', '0s', 3, '');

-- Insert Lessons for Module 2
INSERT INTO clases (modulo_id, titulo, duracion, orden, video_url) VALUES
(@mod2, 'Creando el proyecto', '7m 9s', 1, 'https://videos.midudev.com/astro-cms-headless/02-00-creando-el-proyecto/playlist.m3u8'),
(@mod2, 'Fences: Código en servidor vs cliente', '10m 48s', 2, 'https://videos.midudev.com/astro-cms-headless/02-01-fences/playlist.m3u8'),
(@mod2, 'Trabajando con colecciones', '2m 19s', 3, 'https://videos.midudev.com/astro-cms-headless/02-02-coleciones/playlist.m3u8');
