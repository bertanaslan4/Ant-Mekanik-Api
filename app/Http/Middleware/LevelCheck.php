<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class LevelCheck {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role) {
        $roles = explode('-', $role);

        if (Auth::check()) {

            $auth = Auth::user()->auth_level;

            if (in_array($auth, $roles)) {
                return $next($request);
            }
        }
//
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return response(view('master.authError'));
        }
//        return $next($request);
    }

}
