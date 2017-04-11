<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->verified) {
            return redirect('email-verification/verify');
        } else if (Auth::check() && Auth::user()->banned) {
            return redirect("banned");
        } else {
            return $next($request);
        }
    }
}
