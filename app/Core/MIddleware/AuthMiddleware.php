<?php

namespace App\Core\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            error_log('User not authenticated'); // log ke error log
            header('Location: /login');
            exit;
        } else {
            error_log('User authenticated: ' . $_SESSION['user']['email']);
        }
    }
}
