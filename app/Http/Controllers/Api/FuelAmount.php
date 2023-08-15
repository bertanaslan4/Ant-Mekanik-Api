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

class FuelAmount extends Controller {

    public function __construct() {
        
    }

    public function get() {

        General::validation('fuel-amount');
        $data = \App\Helper\Calc::fuelAmount();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function getYearly() {

        General::validation('fuel-amount-yearly');
        $data = \App\Helper\Calc::fuelAmountYearly();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function fuelamountprojectsave()
    {
        $data = \App\Helper\Calc::fuelamountProjectSave();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function fuelamountprojectlist()
    {
        $data = \App\Helper\Calc::fuelamountProjectList();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function fuelamountprojectdelete()
    {
        $data = \App\Helper\Calc::fuelamountProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function fuelamountupdate()
    {

        $data = \App\Helper\Calc::fuelamountProjectUpdate();
         
        if(empty($data["id"]))
        {
            
            return response(['success' => false, 'data' => $data], Response::HTTP_OK);
        }
       else
       {
           
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
       }
    } 

    //fuel amount yearly

    public function fuelamountyearlyprojectsave()
    {
        
        $data = \App\Helper\Calc::fuelamountyearlyProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function fuelamountyearlyprojectlist()
    {
        $data = \App\Helper\Calc::fuelamountyearlyProjectListele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function fuelamountyearlyprojectdelete()
    {
        $data = \App\Helper\Calc::fuelamountyearlyProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function fuelamountyearlyupdate()
    {
        $data = \App\Helper\Calc::fuelamountyearlyProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
       
    }

}
