<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
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
