<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class Token extends Controller {

    public function __construct() {
        
    }

    public function login($token) {
        if ($token == '') {
            return response([
                'success' => false,
                'errorCode' => 'empty_token_error',
                'message' => 'Token boş olamaz'
                    ], Response::HTTP_FORBIDDEN);
        } else {

            $user = \App\Model\User::where('login_token', $token)->first();

            if ($user && $user->id > 0) {
                if (Auth::loginUsingId($user->id)) {

                    \App\Model\User::where('id', Auth::user()->id)->update(['last_login' => time()]);

                    return redirect('/dashboard');
                } else {

                    return response([
                        'success' => false,
                        'errorCode' => 'auth_error',
                        'message' => 'Giriş bilgileri hatalı'
                            ], Response::HTTP_FORBIDDEN);
                }
            } else {
                return response([
                    'success' => false,
                    'errorCode' => 'auth_error',
                    'message' => 'Giriş bilgileri hatalı.'
                        ], Response::HTTP_FORBIDDEN);
            }
        }
    }

    public function service() {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:3'
        );

        $validator = Validator::make(Input::all(), $rules);

//        return response([$_POST, Input::all()], Response::HTTP_FORBIDDEN);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'errorCode' => 'validation_error',
                'validations' => $validator->messages(),
                'message' => 'Geçersiz veriler'
                    ], Response::HTTP_FORBIDDEN);
        } else {

            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );

            if (Auth::attempt($userdata)) {
                $token = substr(md5('FD' . date('YmdHis') . '1453'), 12);
                \App\Model\User::where('id', Auth::user()->id)->update(['login_token' => $token]);
                $url = 'http://81.213.108.195/loginToken/'.$token;

                return response(['success' => true, 'token' => $token, 'url' => $url, 'user_id', Auth::user()->id], Response::HTTP_OK);
            } else {

                return response([
                    'success' => false,
                    'errorCode' => 'auth_error',
                    'message' => 'Giriş bilgileri hatalı.'
                        ], Response::HTTP_FORBIDDEN);
            }
        }
    }

}
