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

class Paddlebox extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('paddlebox');
        $data = \App\Helper\Calc::paddlebox();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function paddleboxSave() {

        $data = \App\Helper\Calc::paddleboxProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function paddleboxprojectlist()
    {
        $data = \App\Helper\Calc::paddleboxlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function paddleboxprojectdelete()
    {
        $data = \App\Helper\Calc::paddleboxProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
     
    public function paddleboxupdate()
    {
        $data = \App\Helper\Calc::paddleboxProjectUpdate();
         
        if($data==1 || $data==0)
        { 
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }

    }

}
