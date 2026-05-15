<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMaster
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isTeacher()) {
            return redirect()
                ->route('home')
                ->with('error', 'Этот раздел доступен только ведущим мастер-классов.');
        }

        return $next($request);
    }
}
