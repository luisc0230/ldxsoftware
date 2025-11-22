<?php
require_once __DIR__ . '/Database.php';

class Curso {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // Helper para slugificar texto (simple)
    private function slugify($text) {
        // Reemplazar caracteres no alfanuméricos por guiones
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // Transliterar
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // Remover caracteres indeseados
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Trim
        $text = trim($text, '-');
        // Lowercase
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }

    public function getCursoBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getContenidoCurso($cursoId) {
        // Obtener módulos
        $stmt = $this->db->prepare("SELECT * FROM modulos WHERE curso_id = ? ORDER BY orden ASC");
        $stmt->bind_param("i", $cursoId);
        $stmt->execute();
        $modulos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Para cada módulo, obtener clases
        foreach ($modulos as &$modulo) {
            $modulo['slug'] = $this->slugify($modulo['titulo']); // Generar slug al vuelo
            
            $stmtClases = $this->db->prepare("SELECT * FROM clases WHERE modulo_id = ? ORDER BY orden ASC");
            $stmtClases->bind_param("i", $modulo['id']);
            $stmtClases->execute();
            $clases = $stmtClases->get_result()->fetch_all(MYSQLI_ASSOC);
            
            // Añadir slug a cada clase
            foreach ($clases as &$clase) {
                $clase['slug'] = $this->slugify($clase['titulo']);
            }
            
            $modulo['clases'] = $clases;
        }

        return $modulos;
    }

    // Buscar una clase específica basada en los slugs de la URL
    public function getClaseBySlugs($courseSlug, $moduleSlug, $lessonSlug) {
        $curso = $this->getCursoBySlug($courseSlug);
        if (!$curso) return null;

        $modulos = $this->getContenidoCurso($curso['id']);
        
        $foundModulo = null;
        foreach ($modulos as $mod) {
            if ($mod['slug'] === $moduleSlug) {
                $foundModulo = $mod;
                break;
            }
        }

        if (!$foundModulo) return null;

        $foundClase = null;
        $nextClase = null;
        $prevClase = null;
        
        // Aplanar todas las clases para encontrar prev/next fácilmente
        $allClases = [];
        foreach ($modulos as $mod) {
            foreach ($mod['clases'] as $c) {
                $c['modulo_slug'] = $mod['slug'];
                $c['modulo_titulo'] = $mod['titulo'];
                $allClases[] = $c;
            }
        }

        for ($i = 0; $i < count($allClases); $i++) {
            if ($allClases[$i]['slug'] === $lessonSlug && $allClases[$i]['modulo_slug'] === $moduleSlug) {
                $foundClase = $allClases[$i];
                $prevClase = ($i > 0) ? $allClases[$i - 1] : null;
                $nextClase = ($i < count($allClases) - 1) ? $allClases[$i + 1] : null;
                break;
            }
        }

        if ($foundClase) {
            return [
                'curso' => $curso,
                'clase' => $foundClase,
                'modulo' => $foundModulo,
                'prev' => $prevClase,
                'next' => $nextClase,
                'temario' => $modulos // Para el sidebar
            ];
        }

        return null;
    }
}
?>
