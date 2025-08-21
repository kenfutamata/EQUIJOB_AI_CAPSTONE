<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     * ...
     */
    protected $middleware = [
        \App\Http\Middleware\NoCacheHeaders::class,
        // Other middlewares...
    ];

    /**
     * The application's route middleware.
     * ...
     */
    protected $routeMiddleware = [
        'nocache' => \App\Http\Middleware\NoCacheHeaders::class,
        'auth' => \App\Http\Middleware\UnauthorizedMiddleware::class,
        // Other middlewares...
    ];

    // DELETE THIS ENTIRE METHOD FROM THIS FILE
    /*
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('feedback:send-requests')->daily();
        $schedule->command('app:send-interview-reminders')->dailyAt('08:00');
    }
    */

    /**
     * Register the commands for the application.
     *
     * @return void
     */
     // This method might not even exist in your Http Kernel, which is fine.
}