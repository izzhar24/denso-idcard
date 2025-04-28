<?php

namespace App\Core;

class Asset
{
    public static function url($path)
    {
        $baseUrl = '/assets/';
        return $baseUrl . ltrim($path, '/');
    }
}
