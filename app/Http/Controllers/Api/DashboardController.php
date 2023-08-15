<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Helper\General;

class DashboardController extends Controller {

     public function __construct() {
         
     }
     public function get() {

          $data = \App\Helper\Calc::istatislik();
          return response(['success' => true, 'data' =>$data], Response::HTTP_OK);

          
      }

}