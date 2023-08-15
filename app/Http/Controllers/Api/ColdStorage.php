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

class ColdStorage extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('cold-storage');
        $data = \App\Helper\Calc::coldStorage();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function coldstorageSave() {

        $data = \App\Helper\Calc::coldstorageProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function coldstorageprojectlist()
    {
        $data = \App\Helper\Calc::coldstoragelistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function coldstorageprojectdelete()
    {
        $data = \App\Helper\Calc::coldstorageProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
   
    public function coldstorageUpdate()
    {
       
        $data = \App\Helper\Calc::coldstorageProjectUpdate();
        
        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);

        
        }
    }


}
