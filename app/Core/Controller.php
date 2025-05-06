<?php

namespace App\Core;

class Controller
{
    protected function view(string $name, array $data = [], string $layout = 'app'): void
    {
        view($name, $data, $layout);
    }
}
