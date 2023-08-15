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

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function show() {

        if (Auth::check()) {
            Redirect::to('panel');
        }

        return view('master.login');
    }

    public function login() {
        $rules = array(
            'email' => 'required|email', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

//        $hashed = Hash::make('123456');
//        dd($hashed);

        $validator = Validator::make(Input::all(), $rules);

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

//                dd(['id' => Auth::user()->id]);

                \App\Model\User::where('id', Auth::user()->id)->update(['last_login' => time()]);

//                $user = \App\Model\User::first(['id' => Auth::user()->id]);
//                
//                $user->last_login = time();
//                $user->save();

                return response(['success' => true], Response::HTTP_OK);
            } else {

                return response([
                    'success' => false,
                    'errorCode' => 'auth_error',
                    'message' => 'Giriş bilgileri hatalı.'
                        ], Response::HTTP_FORBIDDEN);
            }
        }
    }

    public function loginWithToken($token) {
        if ($token == '') {
            return response([
                'success' => false,
                'errorCode' => 'empty_token_error',
                'message' => 'Token boş olamaz'
                    ], Response::HTTP_FORBIDDEN);
        } else {

            $user = \App\Model\User::where('token', $token)->first();

            if ($user && $user->id > 0) {
                if (Auth::loginUsingId($user->id)) {

                    \App\Model\User::where('id', Auth::user()->id)->update(['last_login' => time(), 'login_token' => '']);

                    return response(['success' => true], Response::HTTP_OK);
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

    public function loginService() {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:3'
        );

        $validator = Validator::make(Input::all(), $rules);

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

                return response(['success' => true, 'token' => $token], Response::HTTP_OK);
            } else {

                return response([
                    'success' => false,
                    'errorCode' => 'auth_error',
                    'message' => 'Giriş bilgileri hatalı.'
                        ], Response::HTTP_FORBIDDEN);
            }
        }
    }

    public
            function logout() {
        Auth::logout();
        return Redirect::to('/login');
    }

}
