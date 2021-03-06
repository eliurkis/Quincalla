<?php

namespace Quincalla\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Quincalla\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Quincalla\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => \Quincalla\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.admin' => \Quincalla\Http\Middleware\AdminAuthenticate::class,
        'checkout'   => \Quincalla\Http\Middleware\CheckoutSession::class,
        'order.auth' => \Quincalla\Http\Middleware\OrderAuth::class,
        'guest'      => \Quincalla\Http\Middleware\RedirectIfAuthenticated::class,
    ];
}
