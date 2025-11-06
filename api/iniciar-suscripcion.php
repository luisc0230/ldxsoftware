<?php
/**
 * API Endpoint - Iniciar Suscripción
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/SuscripcionController.php';

header('Content-Type: application/json');

$controller = new SuscripcionController();
$controller->iniciarSuscripcion();
