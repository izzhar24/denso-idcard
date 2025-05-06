<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/helpers.php';

use App\Core\Router;
use Dotenv\Dotenv;

// Start session
session_start();

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
// Init Router
$router = new Router();
// Path dinamis
define('BASE_PATH', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('ASSET_PATH', BASE_PATH . '/assets/');


loadEnv();


// Load route definitions
require_once __DIR__ . '/../route/web.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan BASE_PATH jika ada
$uri = str_replace(BASE_PATH, '', $uri);

$router->dispatch($uri, $method);