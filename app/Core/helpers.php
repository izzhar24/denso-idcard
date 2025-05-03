<?php
function view($name, $data = [])
{
    extract($data);

    // Ambil file view
    $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $name) . '.php';
    if (!file_exists($viewFile)) {
        throw new Exception("View [$name] not found.");
    }

    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    // Load layout utama
    $layoutFile = __DIR__ . '/../views/layouts/app.php';
    if (file_exists($layoutFile)) {
        require $layoutFile;
    } else {
        echo $content; // Jika tidak ada layout, tampilkan langsung kontennya
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

function startPush($section) {
    global $__currentPushSection;
    ob_start();
    $__currentPushSection = $section;
}

function endPush() {
    global $__pushStacks, $__currentPushSection;
    $content = ob_get_clean();
    $__pushStacks[$__currentPushSection][] = $content;
}

function renderPush($section) {
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
