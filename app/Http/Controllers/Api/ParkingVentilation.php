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

class ParkingVentilation extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('parking-ventilation');
        $data = \App\Helper\Calc::parkingVentilation();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    //parkingventilation
    public function parkingventilationSave() {

        $data = \App\Helper\Calc::parkingventilationProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function parkingventilationprojectlist()
    {
        $data = \App\Helper\Calc::parkingventilationlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function parkingventilationprojectdelete()
    {
        $data = \App\Helper\Calc::parkingventilationProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function parkingventilationupdate()
    {   
        $data = \App\Helper\Calc::parkingventilationProjectUpdate();
         
        if($data==1 || $data==0)
        { 
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }

}
