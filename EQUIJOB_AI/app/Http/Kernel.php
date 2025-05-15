<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\NoCacheHeaders::class,  // <-- Ensure this is here
        // Other middlewares...
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'nocache' => \App\Http\Middleware\NoCacheHeaders::class,  // <-- Ensure this is here
        'auth' => \App\Http\Middleware\UnauthorizedMiddleware::class,
        // Other middlewares...
    ];
}

?>