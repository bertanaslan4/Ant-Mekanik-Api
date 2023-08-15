<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Helper\General;

class Method extends Controller {

    public function __construct() {
        
    }

    public function all() {
        $path = 'data/method';
        $methods = [];
        foreach (\Illuminate\Support\Facades\Storage::files($path) as $filename) {
            $method = str_replace($path, '', $filename);
            $method = str_replace('.json', '', $method);
            $method = trim($method, '/');
            $method = trim($method);
            $res = General::getMethod($method);

            if ($res['success']) {
                $methods[$method] = $res['data'];
            }
        }

        return response($methods, Response::HTTP_OK);
    }

    public function get($method) {
        $res = General::getMethod($method);
        return response($res, Response::HTTP_OK);
    }

}
