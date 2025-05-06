<?php

use App\Core\Middleware\AuthMiddleware;
use App\Core\Middleware\GuestMiddleware;
use App\Core\Middleware\RoleMiddleware;

return [
    'auth' => AuthMiddleware::class,
    'guest' => GuestMiddleware::class,
    'role' => RoleMiddleware::class,
];
