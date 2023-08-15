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

class Boiler extends Controller {

    public function __construct() {

    }

    public function get() {
        General::validation('boiler');

        $data = \App\Helper\Calc::boiler();
        $data['Boilers'] = $this->getData($data['BoilerCapacity'],$data["markalar"],$data["limit"]);
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    

    private function getData($boilerCapacity = false,$brand=[],$limit=3) {
        if (!$boilerCapacity) {
            $boilerCapacity = Input::get('BoilerCapacity');
        }

        $data = [];
        $where = [];
        $where[] = ['heat_power', '>=', $boilerCapacity / 1000];
        $markasarti="";
        //$brand = Input::get('BoilerBrand');
        $boilers = DB::table('api__boiler')
                ->where($where)
                ->whereIn('brand',$brand)
                ->orderBy('heat_power', 'asc')
                ->limit($limit)
                ->get();      

        foreach ($boilers as $boiler) {
            $data[] = [
                'Name' => $boiler->name,
                'Boiler_type'=>$boiler->boiler_type,
                'Brand' => $boiler->brand,
                'Description' => $boiler->description,
                'FuelType' => $boiler->fuel_type,
                'HeatPower' => $boiler->heat_power,
                'mop' =>$boiler->max_operating_pressure,
                'BBBF_No' => $boiler->bbbf_no,
                'B_Height'=> $boiler->b_height,
                'B_Length' => $boiler->b_length,
                'B_Width' => $boiler->b_width,
                'Water_Volume' => $boiler->water_volume,
                'Weight' =>$boiler->weight,
                'Fume_Con' =>$boiler->fume_con,
                'gsr' =>$boiler->gas_side_resistance,
                'Boiler_Eff' =>$boiler->boiler_efficiency,
                'Code' => $boiler->code,
                'Web' => (string) $boiler->web,
                'Catalog' => (string) $boiler->katalog,
                'Banner' => $boiler->mini_banner,
                'Producer' => General::getProducer($boiler->code),
                'Seller' => General::getSellerBoiler($boiler->code,$boiler->p_type),
                'id'=>$boiler->id,
            ];
        }

        return $data;
    }
    public function getBoilerDetail()
    {
        $data = \App\Helper\Calc::boiler();
        $boilers = DB::table('api__boiler')
                ->where("id",Input::get('id'))
                ->get();
            foreach ($boilers as $boiler) {
                    $data[] = [
                        'Name' => $boiler->name,
                        'Boiler_type'=>$boiler->boiler_type,
                        'Brand' => $boiler->brand,
                        'Description' => $boiler->description,
                        'FuelType' => $boiler->fuel_type,
                        'HeatPower' => $boiler->heat_power,
                        'HeatPower' => $boiler->heat_power,
                        'Code' => $boiler->code,
                        'Web' => (string) $boiler->web,
                        'Banner' => $boiler->mini_banner,
                        'Catalog' => (string) $boiler->katalog,
                        'Producer' => General::getProducer($boiler->code),
                        'Seller' => General::getSellerBoiler($boiler->code,$boiler->p_type),
                        'id'=>$boiler->id,
                    ];
            }
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function boilerprojectsave()
    {
        //General::validation('boilerProjectSave');
        $data = \App\Helper\Calc::boilerProjectSave();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function boilerprojectlist()
    {
        $data = \App\Helper\Calc::boilerProjectList();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function boilerprojectdelete()
    {
        $data = \App\Helper\Calc::boilerProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function boilerupdate()
    {
      
        
        
    }

    

}
