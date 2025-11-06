<?php
/**
 * Google OAuth - Callback
 */

define('LDX_ACCESS', true);
require_once '../../../config/config.php';
require_once '../../../app/controllers/AuthController.php';

$authController = new AuthController();
$authController->googleCallback();
