<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UserController;


// Guest Page
$router->middleware('guest')->group(function () use ($router) {
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/card', [HomeController::class, 'card']);
    $router->get('/photo', [HomeController::class, 'photo']);
    $router->get('/choose-background', [HomeController::class, 'chooseBackground']);
    $router->get('/print-preview', [HomeController::class, 'printPreview']);
    $router->post('/get-card', [HomeController::class, 'getCard']);
    $router->post('/set-employee', [HomeController::class, 'setEmployee']);
    $router->post('/set-photo', [HomeController::class, 'setPhoto']);
    $router->post('/set-background', [HomeController::class, 'setBackground']);
    $router->post('/request-print-idcard', [HomeController::class, 'requestPrintIdcard']);
    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);
});

// Authorization Page
$router->middleware('auth')->group(function () use ($router) {
    $router->get('/admin', [AuthController::class, 'admin']);
    $router->get('/logout', [AuthController::class, 'logout']);
    $router->get('/users', [UserController::class, 'index']);
    $router->get('/users/create', [UserController::class, 'create']);
    $router->post('/users', [UserController::class, 'store']);
    $router->get('/users/{id}/edit', [UserController::class, 'edit']);
    $router->post('/users/{id}/update', [UserController::class, 'update']);
    $router->post('/users/{id}/delete', [UserController::class, 'destroy']);
});

$router->get('/unauthorized', function () {
    echo "Unauthorized access";
});
