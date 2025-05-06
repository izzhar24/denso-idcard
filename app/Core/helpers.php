<?php
if (!function_exists('view')) {
    function view($name, $data = [], $layout = 'app')
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Parameter $data harus berupa array.');
        }

        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $name) . '.php';
        $layoutPath = __DIR__ . '/../Views/layouts/' . str_replace('.', '/', $layout) . '.php';

        if (!file_exists($viewPath)) {
            die("View file not found: $viewPath");
        }

        if (!file_exists($layoutPath)) {
            die("Layout file not found: $layoutPath");
        }

        extract($data);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        require $layoutPath;
    }
}

if (!function_exists('redirect')) {
    function redirect($url)
    {
        header('Location: ' . BASE_PATH . $url);
        exit;
    }
}

if (!function_exists('asset')) {
    function asset($file)
    {
        return ASSET_PATH . $file;
    }
}
$__pushStacks = [];
$__currentPushSection = null;

function startPush($section)
{
    global $__currentPushSection;
    ob_start();
    $__currentPushSection = $section;
}

function endPush()
{
    global $__pushStacks, $__currentPushSection;
    $content = ob_get_clean();
    $__pushStacks[$__currentPushSection][] = $content;
}

function renderPush($section)
{
    global $__pushStacks;
    if (!empty($__pushStacks[$section])) {
        echo implode("\n", $__pushStacks[$section]);
    }
}

if (!function_exists('loadEnv')) {
    function loadEnv($path = __DIR__ . '/../../.env')
    {
        if (!file_exists($path)) return;

        foreach (file($path) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;

            [$key, $val] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val);
        }
    }
}

if (!function_exists('json')) {
    function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('json_error')) {
    function json_error($message = 'Terjadi kesalahan.', $statusCode = 400)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit;
    }
}
