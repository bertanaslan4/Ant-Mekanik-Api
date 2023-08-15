<?php 


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use JWTAuth;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;
use \stdClass;

use Mail;

/* DB */
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{

    /* MESAJLAR */
    public static $MSG_1 ="false";
    public static $MSG_2 ="true";
    /* BİTİŞ MESAJLAR */

     public function getcity(Request $request)
     {
          try {
               $data = \App\Helper\Calc::getcity();
               return response(['success' => true, 'data' => $data,"message"=>"Success"], Response::HTTP_OK);

          } catch (\Throwable $th) {

               return response(['success' => false,"message"=>"Please check the parameters"], Response::HTTP_OK);
          }

     }

     public function boruhesabi(Request $request)
     {
         try {
            //$data = \App\Helper\Calc::boruhesabi();
            $array = [
                'name'=>"dsadasdas",
                'email'=>"dsadsadas",
                'telefon'=>"4534543534",
                'mesaj'=>"dfgdfgfdgfdgfdgd"
            ];
            Mail::send('iletisim', $array, function ($message) {
                $message->from('tayfun.yesilyurttt@gmail.com', 'İletişim');
                $message->subject("İLETİŞİM FORMU");
                $message->to("tayfunyesilyurt0707@gmail.com");
            });
            return response(['success' => true, 'data' => $result,"message"=>"Success"], Response::HTTP_OK);
         } catch (\Exception $e) {
            return response(['success' => false,"message"=>$e->getMessage()], Response::HTTP_OK);
         }

     }

     public function islem(Request $request)
     {
          $PumpFlow = 9; //kap_1  

          $PressureDrop = 2; //basma yüksekligi

          $marka = "Demirdöküm";

          $limit =3;

          $tipi = "UPC 32-60";
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
            )->get();

        /* Run Calc Func*/
          return CityController::calc($PumpFlowData,$PumpFlow,$PressureDrop);
          //sql tablosu api__pump
     }


     public static function calc($PumpFlowData,$pf = "", $pd = ""){
        
        if(empty($pf)){
            return CityController::jsonResponse("Can't find PumpFlow value." , 500);
        }

        if(empty($pd)){
            return CityController::jsonResponse("Can't find PressureDrop value." , 500);
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
            $dataSet = CityController::createDataSet($pf);
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

            $dataSet->status = $dataSet->maxVal->value != null ? CityController::$MSG_2 : CityController::$MSG_1;
            $newDataSet->{$dataSet->id} = $dataSet;
        }

        

        return CityController::jsonResponse("Success" , 200,[CityController::sortData($newDataSet)]);

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
    

}