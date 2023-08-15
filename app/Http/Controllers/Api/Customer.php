<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Helper\General;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Illuminate\Support\Facades\Input;



class Customer extends Controller
{
    

    public function Musterilist(Request $request)
    {
        try {
            $sql= DB::table('kullanicilar')->get();
            return response(['success' => true, 'data' =>$sql]);
            
        } catch (\Throwable $th) {
            return response(['success' => false, 'data' =>null]);
            
        }
            
             
    }


    public function MusteriEdit(Request $request)
    {
             $data = Input::all();
             $sql= DB::table('kullanicilar');
             if(!empty($data["id"])) {
                $sql=$sql->where('id', $data["id"]);
             }
             return $sql->get();
             
    }


    public function Musteriupdate(Request $request)
    {



        try {
            $data = Input::all();
            $sql= DB::table('kullanicilar');
            if(!empty($data["id"])) {
               $sql=$sql->where('id', $data["id"])->update(array(
                   "name"=>$data["name"],
                   "kilit_id"=>$data["kilit_id"],
                   "email"=>$data["email"],
                   "telefon"=>$data["telefon"],
                   "adres"=>$data["adres"],
                   "lisanssuresi"=>$data["lisanssuresi"],
                   "tc"=>$data["tc"],
                   "vd"=>$data["vd"],
                   "firma"=>$data["firma"],
                   "active"=>$data["active"],
                   "role"=>$data["role"],   
               ));

               
            }
            if($sql==1 || $sql==0)
            {
                return response(['success' => true, 'data' =>1]);
            }else{
                return response(['success' => true, 'data' =>0]);
            }
        } catch (\Throwable $th) {
           return 2;
        }

        
            
             
    }


    public function MusteriDelete(Request $request)
    {
     
        try {
            $data = Input::all();
            $sql= DB::table('kullanicilar');
            if(!empty($data["id"])) {
               $sql=$sql->where('id', $data["id"])->delete();
            }
            if($sql==1 || $sql==0)
            {
                return response(['success' => true, 'data' =>1]);
            }else{
                return response(['success' => true, 'data' =>0]);
            }
        } catch (\Throwable $th) {
           return 2;
        }     
             
    }


    





}


?>