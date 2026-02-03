<?php
namespace App\Core;

class Middleware
{
    protected static $middleware = [
        'auth' => \App\Middleware\AuthMiddleware::class,
    ];

    public static function resolve($middleware)
    {
        if (isset(self::$middleware[$middleware])) {
            $class = self::$middleware[$middleware];
            return new $class();
        }
        
        return null;
    }
}