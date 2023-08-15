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

class PipePressureLoss extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('pipe-pressure-loss');
        $data = \App\Helper\Calc::pipePressureLoss();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    //pipe
    public function pipeSave() {

        $data = \App\Helper\Calc::pipeProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function pipeprojectlist()
    {
        $data = \App\Helper\Calc::pipelistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function pipeprojectdelete()
    {
        $data = \App\Helper\Calc::pipeProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function pipeupdate()
    {
        $data = \App\Helper\Calc::pipePressureLossProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }

}
