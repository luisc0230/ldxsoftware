<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug LDX Software</h1>";

try {
    echo "<h2>1. Definiendo LDX_ACCESS</h2>";
    define('LDX_ACCESS', true);
    echo "✓ LDX_ACCESS definido<br>";
    
    echo "<h2>2. Cargando config.php</h2>";
    require_once '../config/config.php';
    echo "✓ Config cargado<br>";
    echo "BASE_URL: " . BASE_URL . "<br>";
    
    echo "<h2>3. Cargando routes.php</h2>";
    require_once '../config/routes.php';
    echo "✓ Routes cargado<br>";
    
    echo "<h2>4. Probando autoloader</h2>";
    spl_autoload_register(function ($className) {
        echo "Intentando cargar clase: $className<br>";
        
        // Try to load from controllers directory
        $controllerFile = CONTROLLERS_PATH . $className . '.php';
        echo "Buscando controller en: $controllerFile<br>";
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            echo "✓ Controller cargado: $className<br>";
            return;
        }
        
        // Try to load from models directory
        $modelFile = MODELS_PATH . $className . '.php';
        echo "Buscando model en: $modelFile<br>";
        if (file_exists($modelFile)) {
            require_once $modelFile;
            echo "✓ Model cargado: $className<br>";
            return;
        }
        
        echo "❌ No se encontró la clase: $className<br>";
    });
    
    echo "<h2>5. Probando ruta</h2>";
    $uri = $_SERVER['REQUEST_URI'];
    echo "URI original: $uri<br>";
    
    $basePath = parse_url(BASE_URL, PHP_URL_PATH);
    echo "Base path: $basePath<br>";
    
    if ($basePath && strpos($uri, $basePath) === 0) {
        $uri = substr($uri, strlen($basePath));
    }
    echo "URI procesada: $uri<br>";
    
    $route = getRoute($uri);
    echo "Ruta encontrada: ";
    print_r($route);
    echo "<br>";
    
    echo "<h2>6. Cargando controller</h2>";
    $controllerName = $route['controller'];
    $actionName = $route['action'];
    
    echo "Controller: $controllerName<br>";
    echo "Action: $actionName<br>";
    
    if (!class_exists($controllerName)) {
        echo "❌ Controller no existe: $controllerName<br>";
    } else {
        echo "✓ Controller existe: $controllerName<br>";
        
        $controller = new $controllerName();
        echo "✓ Controller instanciado<br>";
        
        if (!method_exists($controller, $actionName)) {
            echo "❌ Action no existe: $actionName<br>";
        } else {
            echo "✓ Action existe: $actionName<br>";
            echo "<h2>7. Ejecutando action...</h2>";
            $controller->$actionName();
        }
    }
    
} catch (Exception $e) {
    echo "<h2>❌ ERROR:</h2>";
    echo "Mensaje: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<h2>❌ FATAL ERROR:</h2>";
    echo "Mensaje: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
