<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    if (!in_array($user->role, $roles)) {
        abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
    }

    return $next($request);

    }
}
