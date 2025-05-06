<?php

namespace App\Core\Middleware;

class GuestMiddleware
{
    public function handle()
    {
        error_log('SESSION DATA: ' . print_r($_SESSION, true));

        if (isset($_SESSION['user'])) {
            header('Location: /admin');
            exit;
        }
    }
}
