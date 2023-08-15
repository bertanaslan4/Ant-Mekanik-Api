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

class Data extends Controller {

    public function __construct() {
        
    }

    public function Brand() {
        
        $data = \App\Helper\Calc::getBrand();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

     


    public function get($type = "") {
        $res = General::getData($type);

        if ($res['success']) {
            return response($res, Response::HTTP_OK);
        } else {
            return response($res, Response::HTTP_FORBIDDEN);
        }
    }

    public function multi() {
        $data = Input::all();

        $list = [];

        if (isset($data['Types'])) {
            foreach ($data['Types'] as $d) {
                $cond = isset($d['Condition']) ? $d['Condition'] : [];

                if (isset($d['Name'])) {
                    $res = General::getData($d['Name'], $cond);

                    $list[] = [
                        'Name' => $d['Name'],
                        'Data' => $res['success'] ? $res['data'] : []
                    ];
                }
            }
        }

        return response(['success' => true, 'data' => $list], Response::HTTP_OK);
    }

}
