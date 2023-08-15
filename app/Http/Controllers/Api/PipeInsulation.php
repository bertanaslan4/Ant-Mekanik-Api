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

class PipeInsulation extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('pipe-insulation');
        $data = \App\Helper\Calc::pipeInsulation();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function pipeinsulationSave() {

        $data = \App\Helper\Calc::pipeinsulationProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function pipeinsulationprojectlist()
    {
        $data = \App\Helper\Calc::pipeinsulationlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function pipeinsulationprojectdelete()
    {
        $data = \App\Helper\Calc::pipeinsulationProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function pipeinsulationupdate()
    {
        $data = \App\Helper\Calc::pipeinsulationProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }

}
