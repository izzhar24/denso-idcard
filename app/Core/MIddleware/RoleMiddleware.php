<?php

namespace App\Core\Middleware;

class RoleMiddleware
{
    public function handle($role)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            header('Location: /unauthorized');
            exit;
        }
    }
}
