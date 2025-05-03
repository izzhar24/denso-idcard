<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/helpers.php';

use App\Core\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
// Init Router
$router = new Router();
// Path dinamis
define('BASE_PATH', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('ASSET_PATH', BASE_PATH . '/assets/');

// Start session
session_start();

loadEnv();


// Load route definitions
require_once __DIR__ . '/../route/web.php';

// Run router
$router->run();
