<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = auth()->user();

        if ($user && $user->status === 'new') {
            return redirect()->route('auth.dashboard');
        }

        return $next($request);
    }
}