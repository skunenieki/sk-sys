<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    $dotenv = new Dotenv\Dotenv(__DIR__.'/../');
    $dotenv->load();
} catch (Exception $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
	realpath(__DIR__.'/../')
);

$app->withFacades();
$app->withEloquent();

// $logger = $app->make(\Psr\Log\LoggerInterface::class);
// $logger->popHandler();
// $logger->pushHandler(new \Monolog\Handler\ErrorLogHandler());

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    'Illuminate\Contracts\Debug\ExceptionHandler',
    'Skunenieki\System\Exceptions\Handler'
);

$app->singleton(
    'Illuminate\Contracts\Console\Kernel',
    'Skunenieki\System\Console\Kernel'
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    // 'Illuminate\Cookie\Middleware\EncryptCookies',
    // 'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
    // 'Illuminate\Session\Middleware\StartSession',
    // 'Illuminate\View\Middleware\ShareErrorsFromSession',
    // 'Laravel\Lumen\Http\Middleware\VerifyCsrfToken',
]);

$app->routeMiddleware([
    'auth' => Skunenieki\System\Http\Middleware\AuthenticateOnceWithBasicAuth::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register('Skunenieki\System\Providers\AppServiceProvider');

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

require __DIR__.'/../app/Http/routes.php';

return $app;
