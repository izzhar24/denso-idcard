<?php

use App\Controllers\AuthController;
use App\Controllers\EmployeeController;
use App\Controllers\EmployeeRequestCardController;
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
    $router->post('/store-idcard', [HomeController::class, 'storeIdcard']);
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
    $router->get('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
    $router->post('/users/{id}/reset-password', [UserController::class, 'storeResetPassword']);

    $router->get('/employees', [EmployeeController::class, 'index']);
    $router->get('/employees/create', [EmployeeController::class, 'create']);
    $router->post('/employees', [EmployeeController::class, 'store']);
    $router->get('/employees/{id}/edit', [EmployeeController::class, 'edit']);
    $router->post('/employees/{id}/update', [EmployeeController::class, 'update']);
    $router->post('/employees/{id}/delete', [EmployeeController::class, 'destroy']);

    $router->get('/employee-request-cards', [EmployeeRequestCardController::class, 'index']);
    $router->post('/employee-request-cards/{id}/reject', [EmployeeRequestCardController::class, 'reject']);
    $router->post('/employee-request-cards/approve', [EmployeeRequestCardController::class, 'approve']);
});

$router->get('/unauthorized', function () {
    echo "Unauthorized access";
});
