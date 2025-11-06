<?php
/**
 * Logout - Cerrar sesión
 */

define('LDX_ACCESS', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$authController = new AuthController();
$authController->logout();
