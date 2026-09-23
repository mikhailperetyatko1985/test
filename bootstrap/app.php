<?php

use App\Exceptions\SelfReferralException;
use App\Exceptions\UnknownReferralCodeException;
use App\Http\Middleware\ResolveCurrentMaster;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Авторизация в тестовом проекте заглушена:
        // текущий мастер берётся из заголовка X-Master-Id.
        $middleware->api(prepend: [
            ResolveCurrentMaster::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // API всегда отвечает JSON, даже если клиент не прислал Accept: application/json
        // (иначе abort()/валидация отдали бы HTML или редирект).
        $exceptions->shouldRenderJsonWhen(
            fn ($request) => $request->is('api/*'),
        );

        $exceptions->renderable(
            fn (UnknownReferralCodeException $e) => response()->json(['message' => 'Referral code not found.'], Response::HTTP_NOT_FOUND),
        );
        $exceptions->renderable(
            fn (SelfReferralException $e) => response()->json(['message' => 'You cannot attach to your own referral code.'], Response::HTTP_UNPROCESSABLE_ENTITY),
        );
    })->create();
