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

class CirculationPump extends Controller {

    /* MESAJLAR */
    public static $MSG_1 ="false";
    public static $MSG_2 ="true";
    /* BİTİŞ MESAJLAR */

    public function __construct() {
        
    }

    public function get() {
        General::validation('circulation-pump');
       
        $data = \App\Helper\Calc::circulationPump();
        $data["Product"] = $this->getData($data["PumpFlow"],$data["PressureDrop"],$data["markalar"],$data["limit"]);
        



        


        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function getData($PumpFlow = false,$PressureDrop=false,$brand=[],$limit=3)
     {

        if(!empty($PumpFlow) && !empty($PressureDrop))
        {

            $PumpFlow =$PumpFlow; //kap_1  
            $PressureDrop =$PressureDrop; //basma yüksekligi
            $where = [];
            $tipi = "UPC 32-60";
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
        $PumpFlowData = DB::table('api__pump')->select(
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
          $veriler = CirculationPump::calc($PumpFlowData,$PumpFlow,$PressureDrop);

          if(empty($veriler->original["data"][0]))
          {
              return null;
          }else{
            $urunler = $veriler->original["data"][0];


            foreach ($urunler as $key => $value) {
                $productData = DB::table('api__pump')->where('id', $value->id)->get();

                // Ekstra verileri ekleme
    $productData[0]->Producer =General::getProducer($productData[0]->markasi);
    $productData[0]->Seller = General::getSellerBoiler($productData[0]->markasi,$productData[0]->p_type);

    $product[] = $productData;
            }

return $product;
            

          }
          
        }
          
     }


     public static function calc($PumpFlowData,$pf = "", $pd = ""){
        
        if(empty($pf)){
            return CirculationPump::jsonResponse("Can't find PumpFlow value." , 500);
        }

        if(empty($pd)){
            return CirculationPump::jsonResponse("Can't find PressureDrop value." , 500);
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
        foreach($PumpFlowData as $key => $val){
            $dataSet = CirculationPump::createDataSet($pf);

            
            $dataSet->id = $val->id;
            $dataSet->pumpVal = $pf;
            foreach($val as $_key => $_val){
                /* --- */
                if(isset($hariciAlanlar[$_key])){ continue ; }
                /* --- */
                $temp = $pf - $_val; 
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

            //return CityController::jsonResponse("Success" , 200,[]);

            $oran1 = $dataSet->maxVal->value - $pf / $dataSet->maxVal->value - $dataSet->minVal->value;
            $oran2 = $basDegerMax - $pd / $basDegerMax - $basDegerMin;


             /* if($basDegerMax < $pd && $pd < $basDegerMin){
                continue;
            } */

            if(!(($oran1+$oran2) >= 1) ){
                continue;
            }
            $dataSet->oran = $oran1+$oran2;

            $dataSet->status = $dataSet->maxVal->value != null ? CirculationPump::$MSG_2 : CirculationPump::$MSG_1;
            $newDataSet->{$dataSet->id} = $dataSet;
        }

        

        return CirculationPump::jsonResponse("Success" , 200,[CirculationPump::sortData($newDataSet)]);

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

    public function circulationpumpSave()
    {
        $data = \App\Helper\Calc::circulationpumpProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function circulationpumpprojectlist()
    {
        $data = \App\Helper\Calc::circulationpumplistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function circulationpumpprojectdelete()
    {
        $data = \App\Helper\Calc::circulationpumpProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function circulationpumpupdate()
    {
        $data = \App\Helper\Calc::circulationpumpProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }

}
