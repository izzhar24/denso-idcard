<?php

namespace App\Core\Middleware;

class GuestMiddleware
{
    public function handle()
    {
        if (isset($_SESSION['user'])) {
            redirect('/admin');
        }
    }
}
