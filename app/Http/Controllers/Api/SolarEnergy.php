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

class SolarEnergy extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('solar-energy');
        $data = \App\Helper\Calc::solarEnergy();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function solarenergySave() {

        $data = \App\Helper\Calc::solarenergyProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function solarenergyprojectlist()
    {
        $data = \App\Helper\Calc::solarenergylistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function solarenergyprojectdelete()
    {
        $data = \App\Helper\Calc::solarenergyProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function solarenergyupdate()
    {  

    
        $data = \App\Helper\Calc::solarenergyProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }

    }

}
