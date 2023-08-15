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
use \stdClass;
class Hydrophore extends Controller {

    public static $MSG_1 ="false";
    public static $MSG_2 ="true";

    public function __construct() {
        
    }

    public function get() {
        General::validation('hydrophore');
        $data = \App\Helper\Calc::hydrophore();
        $data['Product'] = $this->getData($data["HydrophoreFlow"],$data["TotalLoss"]/101.974429,$data["markalar"],$data["limit"]);
        /**
         * hydropflow 
         * $data["TotalLoss"]/101.974429
         * 
         * 
         */

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function getData($sart1 = false,$sart2=false,$brand=[],$limit=3)
     {

        if(!empty($sart1) && !empty($sart2))
        {

            $sart1 =$sart1; //kap_1  
            $sart2 =$sart2; //basma yüksekligi
            $where = [];
            $markasarti="";
          /* $PumpFlowData = DB::table('api__pump')->select(
            "id",
            "kap_1_m3",
            "kap_2_m3",
            "kap_3_m3",
            "kap_4_m3",
            "kap_5_m3",
            "basyuk_1_mSS",
            "basyuk_2_mSS",
            "basyuk_3_mSS",
            "basyuk_4_mSS",
            "basyuk_5_mSS",
            )->where("markasi",$marka)->orWhere('tipi',$tipi)->limit($limit)->get(); */

          /* Get Data*/
        $Data = DB::table('api__hydrophoreproduct')->select(
            "id",
            "kap_1_m3",
            "kap_2_m3",
            "kap_3_m3",
            "kap_4_m3",
            "kap_5_m3",
            "basyuk_1_mSS",
            "basyuk_2_mSS",
            "basyuk_3_mSS",
            "basyuk_4_mSS",
            "basyuk_5_mSS",
            )->whereIn('markasi',$brand)->limit($limit)->get();

            
        /* Run Calc Func*/
          $veriler = Hydrophore::calc($Data,$sart1,$sart2);

          

          if(empty($veriler->original["data"][0]))
          {
              return null;
          }else{

            $urunler = $veriler->original["data"][0];
          
            foreach ($urunler as $key => $value) {
                $product[]= DB::table('api__hydrophoreproduct')->leftJoin('api__hydrophoreproducer', 'api__hydrophoreproducer.U_Kodu', '=', 'api__hydrophoreproduct.U_Kodu')
                ->leftJoin('api__hydrophoreseller', 'api__hydrophoreseller.U_Kodu', '=', 'api__hydrophoreproducer.U_Kodu')->where('api__hydrophoreproduct.id',$value->id)->get();
            }
            return $product;

          }  
          
          
        }
          
     }


     public static function calc($Data,$sart1 = "", $sart2 = ""){
        
        if(empty($sart1)){
            return Hydrophore::jsonResponse("Can't find sart1 value." , 500);
        }

        if(empty($sart2)){
            return Hydrophore::jsonResponse("Can't find HydrophoreFlow value." , 500);
        }

        $hariciAlanlar = [
            "id" => true,
            "basyuk_1_mSS" => true,
            "basyuk_2_mSS" => true,
            "basyuk_3_mSS" => true,
            "basyuk_4_mSS" => true,
            "basyuk_5_mSS" => true,
        ];
        $prefix = "basyuk_";
        $lastfix = "_mSS";


        

        $newDataSet = new stdClass;
        foreach($Data as $key => $val){
            $dataSet = Hydrophore::createDataSet($sart1);
            
            
            
            $dataSet->id = $val->id;
            $dataSet->pumpVal = $sart1;
            foreach($val as $_key => $_val){

                
            
                /* --- */
                if(isset($hariciAlanlar[$_key])){ continue ; }
                /* --- */
                $temp = $sart1 - $_val; 
                $kapNumberArr = explode("_",$_key);
                if($temp < 0){
                    $dataSet->maxVal->value = $dataSet->maxVal->value != null && $dataSet->maxVal->value < $_val ?  $dataSet->maxVal->value : $_val; 
                    $dataSet->maxVal->field = $dataSet->maxVal->value != null && $dataSet->maxVal->value < $_val ?  $dataSet->maxVal->field : $_key;
                    $dataSet->maxVal->kapNumber = $dataSet->maxVal->value != null && $dataSet->maxVal->value < $_val ?  $dataSet->maxVal->kapNumber : $kapNumberArr[1];
                }else if($temp > 0){
                    $dataSet->minVal->value = $dataSet->minVal->value != null && $dataSet->minVal->value > $_val ?  $dataSet->minVal->value : $_val;
                    $dataSet->minVal->field = $dataSet->minVal->value != null && $dataSet->minVal->value > $_val ?  $dataSet->minVal->field : $_key;
                    $dataSet->minVal->kapNumber = $dataSet->minVal->value != null && $dataSet->minVal->value < $_val ?  $dataSet->minVal->kapNumber : $kapNumberArr[1];

                
                }else{
                    $dataSet->minVal->value = $dataSet->minVal->value != null && $dataSet->minVal->value > $_val ?  $dataSet->minVal->value : $_val;
                    $dataSet->minVal->field = $dataSet->minVal->value != null && $dataSet->minVal->value > $_val ?  $dataSet->minVal->field : $_key;
                    $dataSet->maxVal->value = $dataSet->maxVal->value != null && $dataSet->maxVal->value < $_val ?  $dataSet->maxVal->value : $_val;
                    $dataSet->maxVal->field = $dataSet->maxVal->value != null && $dataSet->maxVal->value < $_val ?  $dataSet->maxVal->field : $_key;
                    /* --- */
                    $dataSet->maxVal->kapNumber = $dataSet->maxVal->value != null && $dataSet->maxVal->value < $_val ?  $dataSet->maxVal->kapNumber : $kapNumberArr[1];
                    $dataSet->minVal->kapNumber = $dataSet->minVal->value != null && $dataSet->minVal->value < $_val ?  $dataSet->minVal->kapNumber : $kapNumberArr[1];
                }
            }

            

            $productStatus = false;
            /* $prefix.$dataSet->minVal->kapNumber.$lastfix */
            if($dataSet->minVal->kapNumber == null || $dataSet->maxVal->kapNumber == null){
                continue;
            }

            $basDegerMax = $val->{$prefix.$dataSet->minVal->kapNumber.$lastfix};
            $basDegerMin = $val->{$prefix.$dataSet->maxVal->kapNumber.$lastfix};

            $oran1 = $dataSet->maxVal->value - $sart1 / $dataSet->maxVal->value - $dataSet->minVal->value;
            $oran2 = $basDegerMax - $sart2 / $basDegerMax - $basDegerMin;

            if(!(($oran1+$oran2) >= 1) ){
                continue;
            }
            $dataSet->oran = $oran1+$oran2;

            

            $dataSet->status = $dataSet->maxVal->value != null ? Hydrophore::$MSG_2 : Hydrophore::$MSG_1;
            $newDataSet->{$dataSet->id} = $dataSet;
        }

        

        return Hydrophore::jsonResponse("Success" , 200,[Hydrophore::sortData($newDataSet)]);

     }

     public static function sortData($data){
        $res = (array) $data;

        usort($res, function ( $item1, $item2) {
            $item1 = (array) $item1;
            $item2 = (array) $item2;
            return $item1['oran'] <=> $item2['oran'];
        });

        return $res;
     } 

     public static function createDataSet($pf){
        
        $dataSet = new stdClass;
        $dataSet->id = null;
        /* Min Values */
        $dataSet->minVal = new stdClass;
        $dataSet->minVal->field = "";
        $dataSet->minVal->kapNumber = null;
        $dataSet->minVal->pressure = "";
        $dataSet->minVal->pressureVal = null;
        $dataSet->minVal->value = null;
        /* Max values */
        $dataSet->maxVal = new stdClass;
        $dataSet->maxVal->field = "";
        $dataSet->maxVal->kapNumber = null;
        $dataSet->maxVal->pressure = "";
        $dataSet->maxVal->pressureVal = null;
        $dataSet->maxVal->value = null;
        /* Status */
        $dataSet->pumpVal = $pf;
        $dataSet->status = "";
        $dataSet->oran = 0;
        return $dataSet;
     }

     public static function jsonResponse($message,$status = 200,$data = []){
        if(!is_array($data)){
            return exit("Lütfen veri tipinin Array olduğundan emin olun.");
        }
        return response()->json([
            'message' => $message,
            'data' => $data
        ])->header('status',$status);
    }

    public function hydrophoreSave() {

        $data = \App\Helper\Calc::hydrophoreProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function hydrophoreprojectlist()
    {
        $data = \App\Helper\Calc::hydrophorelistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function hydrophoreprojectdelete()
    {
        $data = \App\Helper\Calc::hydrophoreProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function hydrophoreupdate()
    {
        $data = \App\Helper\Calc::hydrophoreProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }

    }

}
