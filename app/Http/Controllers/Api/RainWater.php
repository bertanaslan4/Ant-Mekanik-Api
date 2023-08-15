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

class RainWater extends Controller {

    public function __construct() {
        
    }

    public function get() {
        //General::validation('rain-water');
        $data = \App\Helper\Calc::rainWater();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function rainwaterSave() {

        $data = \App\Helper\Calc::rainwaterProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function rainwaterprojectlist()
    {
        $data = \App\Helper\Calc::rainwaterlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function rainwaterprojectdelete()
    {
        $data = \App\Helper\Calc::rainwaterProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function rainwaterupdate()
    {
      
        $data = \App\Helper\Calc::rainwaterProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }


    }

}
