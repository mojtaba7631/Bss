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
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'checkProjectsAlert' => \App\Http\Middleware\checkProjectsAlert::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'loginAuth' => \App\Http\Middleware\loginAuth::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'adminAuth' => \App\Http\Middleware\adminAuth::class,
        'userAuth' => \App\Http\Middleware\userAuth::class,
        'employerAuth' => \App\Http\Middleware\employerAuth::class,
        'managerAuth' => \App\Http\Middleware\managerAuth::class,
        'legalUserAuth' => \App\Http\Middleware\legalUserAuth::class,
        'realUserAuth' => \App\Http\Middleware\realUserAuth::class,
        'tarhoBarnameManagerAuth' => \App\Http\Middleware\tarhoBarnameManagerAuth::class,
        'mainManagerAuth' => \App\Http\Middleware\mainManager::class,
        'maliManagerAuth' => \App\Http\Middleware\maliManager::class,
        'SupervisorAuth' => \App\Http\Middleware\SupervisorAuth::class,
        'expert' => \App\Http\Middleware\experts::class,
        'personnel' => \App\Http\Middleware\personnel::class,
        'deputy_plan_program' => \App\Http\Middleware\deputy_plan_program::class,
        'support_manager' => \App\Http\Middleware\support_manager::class,
        'relations_manager' => \App\Http\Middleware\relations_manager::class,
        'support_expert' => \App\Http\Middleware\support_expert::class,
        'special_expert' => \App\Http\Middleware\special_expert::class,
        'discourse_expert' => \App\Http\Middleware\discourse_expert::class,
        'innovation_expert' => \App\Http\Middleware\innovation_expert::class,
        'adjustment_expert' => \App\Http\Middleware\adjustment_expert::class,
        'head_discourse' => \App\Http\Middleware\head_discourse::class,



    ];
}
