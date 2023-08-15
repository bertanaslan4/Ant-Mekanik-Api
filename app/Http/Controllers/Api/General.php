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
use Illuminate\Http\Request;


class General extends Controller {
     public function defaultlist()
     {
          $general_variable = DB::table('api__defaultdegerler')
               ->where('kid',"=",Input::post('kid'))
                ->get();
          return response(['success' => true, 'data' => $general_variable], Response::HTTP_OK);      
     }
     public function defaultupdate()
     {
          $affected = DB::table('api__defaultdegerler')
               ->where('kid',"=",Input::post('kid'))
              ->update([Input::post('colum') =>Input::post('valcolm')]);
          if($affected==0)
          {
               return response(['success' => false, 'data' =>"Hata olustu.Lutfen daha sonra tekrar deneyiniz.."], Response::HTTP_OK);

          }else{
               return response(['success' => true, 'data' => $affected], Response::HTTP_OK);
          }
                

     }
     public function projekaydet(){
          $data = $request->all();
            $result=DB::table('Projeler')->insert([
            [
                'kullanici_id' =>1,
                'proje_no' =>'pno',
                'proje_revizyon' =>'prev',
                'proje_adi' =>'adi',
                'proje_konu'=>'konu',
                'proje_hesap'=>'hesap',
                'proje_kontrol'=>'kontrol',
              
                
            ],
        ]);
        return response(['success' => true, 'data' => $result], Response::HTTP_OK);
     }
}