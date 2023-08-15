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

class Collector extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('collector');
        $data = \App\Helper\Calc::collector();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function collectorSave() {

        $data = \App\Helper\Calc::collectorProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function collectorprojectlist()
    {
        $data = \App\Helper\Calc::collectorlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function collectorprojectdelete()
    {
        $data = \App\Helper\Calc::collectorProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
   
    public function collectorupdate()
    {
        
        $data = \App\Helper\Calc::collectorProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }

    }
}
