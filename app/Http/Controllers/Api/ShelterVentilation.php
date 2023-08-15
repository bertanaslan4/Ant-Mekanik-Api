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

class ShelterVentilation extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('shelter-ventilation');
        $data = \App\Helper\Calc::shelterVentilation();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    //shelterventilation
    public function shelterventilationSave() {

        $data = \App\Helper\Calc::shelterventilationProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function shelterventilationprojectlist()
    {
        $data = \App\Helper\Calc::shelterventilationlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function shelterventilationprojectdelete()
    {
        $data = \App\Helper\Calc::shelterventilationProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function shelterventilationupdate()
    {  

    
        $data = \App\Helper\Calc::shelterventilationProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }




    }

}
