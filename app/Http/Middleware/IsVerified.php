<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsVerified
{
    public function isRest($request) {
        $path = $request->path();
        if(in_array($path, ["rest/like", "rest/favourite", "rest/addtag", "rest/createcomment", "rest/report"])){
            return true;
        }
        return false;
    }

    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->verified) {
            if (self::isRest($request)) {
                $response = ["status" => "failed", "reason" => ["please verify your email first"]];
                return response(json_encode($response)) ->header('Content-Type', 'application/json');
            } else {
                return redirect('email-verification/verify');
            }
        } else if (Auth::check() && Auth::user()->banned) {
            if (self::isRest($request)) {
                $response = ["status" => "failed", "reason" => ['<span>You have been banned. If you believe this to be a mistake contact us at  <a href="maito:team@dietlah.sg">team@dietlah.sg</a>.</span>']];
                return response(json_encode($response)) ->header('Content-Type', 'application/json');
            } else {
                return redirect('banned');
            }
        } else {
            return $next($request);
        }
    }
}
