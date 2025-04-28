<?php

namespace App\Core\Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        // Cek apakah session user ada (misal login)
        if (!isset($_SESSION['user'])) {
            // Jika belum login, redirect ke halaman login
            redirect('/login');
        }
    }
}
