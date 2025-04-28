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

$GLOBALS['__current_stack'] = null;
$GLOBALS['__push_stacks'] = [];

if (!function_exists('startPush')) {
    function startPush($stack)
    {
        $GLOBALS['__current_stack'] = $stack;
        if (!isset($GLOBALS['__push_stacks'][$stack])) {
            $GLOBALS['__push_stacks'][$stack] = [];
        }

        ob_start();
    }
}

if (!function_exists('endPush')) {
    function endPush()
    {
        $content = ob_get_clean();
        $stack = $GLOBALS['__current_stack'];
        $GLOBALS['__push_stacks'][$stack][] = $content;
        $GLOBALS['__current_stack'] = null; // Reset current stack
    }
}

if (!function_exists('renderPush')) {
    function renderPush($stack)
    {
        if (!isset($GLOBALS['__push_stacks'][$stack])) {
            return '';
        }

        return implode("\n", $GLOBALS['__push_stacks'][$stack]);
    }
}
