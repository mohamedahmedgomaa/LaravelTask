<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->is('api') || $request->is('api/*')) {
            return route('redirectUnauthorized');
        }

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
