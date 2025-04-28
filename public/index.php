<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/helpers.php';

use App\Core\Router;

// Path dinamis
define('BASE_PATH', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('ASSET_PATH', BASE_PATH . '/assets/');

// Start session
session_start();

// Init Router
$router = new Router();

// Load route definitions
require_once __DIR__ . '/../route/web.php';

// Run router
$router->run();
