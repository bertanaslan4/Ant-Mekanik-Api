<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class HttpsProtocol {

    public function handle($request, Closure $next) {

//        if (!$request->secure() && App::environment() === 'production' && $_SERVER['HTTP_HOST'] == 'fabrika.toptancimburada.com') {
//            return redirect()->secure($request->getRequestUri());
//        }

        return $next($request);
    }

}
