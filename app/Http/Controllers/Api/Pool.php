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

class Pool extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('pool');
        $data = \App\Helper\Calc::pool();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function poolSave() {

        $data = \App\Helper\Calc::poolProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function poolprojectlist()
    {
        $data = \App\Helper\Calc::poollistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function poolprojectdelete()
    {
        $data = \App\Helper\Calc::poolProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function poolupdate()
    {

        $data = \App\Helper\Calc::poolProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }

}
