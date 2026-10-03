<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Laravel's own .env loader (LoadEnvironmentVariables) only runs later,
// inside $app->handleRequest() -- after this file has already finished
// building $app and config/*.php has already been evaluated (config
// files read env() the moment they're included). APP_PUBLIC_PATH below
// needs to be known before that config evaluation happens, so load .env
// ourselves, right now, before anything else in this file reads it.
if (is_file($envPath = dirname(__DIR__).'/.env') && class_exists(\Dotenv\Dotenv::class)) {
    \Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
}

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'setlocale' => \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            $first = $request->segment(1);
            $locale = in_array($first, config('app.locales'), true) ? $first : config('app.locale');

            return route('login', ['locale' => $locale]);
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (\Throwable $e, Request $request) {
            $first = $request->segment(1);
            app()->setLocale(in_array($first, config('app.locales'), true) ? $first : config('app.locale'));

            if (! $request->expectsJson() && ! $request->is('api/*')) {
                return null;
            }

            $status = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
                ? $e->getStatusCode()
                : ($e instanceof \Illuminate\Auth\AuthenticationException ? 401
                : ($e instanceof \Illuminate\Auth\Access\AuthorizationException ? 403
                : ($e instanceof \Illuminate\Session\TokenMismatchException ? 419
                : ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404
                : ($e instanceof \Illuminate\Validation\ValidationException ? 422 : 500)))));

            if ($e instanceof \Illuminate\Validation\ValidationException || $status === 401) {
                return null;
            }

            $key = in_array($status, [403, 404, 419, 500], true) ? $status : null;

            return response()->json([
                'success' => false,
                'message' => $key ? __('errors.m'.$key) : __('errors.message'),
                'data' => (object) [],
            ], $status);
        });
    })->create();

// On cPanel-style split hosting (public/ as a sibling of a separate
// backend/ folder instead of a subfolder), set APP_PUBLIC_PATH in .env to
// the real public folder's absolute path so public_path() -- and anything
// built on it, like the public_media disk -- points at the actual
// web-accessible folder instead of backend/public (which wouldn't exist
// in that layout). Left unset, this is a no-op and behaves like a normal
// single-folder Laravel install.
if ($publicPath = env('APP_PUBLIC_PATH')) {
    $app->usePublicPath($publicPath);
}

return $app;
