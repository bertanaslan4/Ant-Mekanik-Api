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

class Burner extends Controller {

    public function __construct() {
        
    }

    public function get() {
        General::validation('burner');

        $data = \App\Helper\Calc::burner();
        $data['Burners'] = $this->getData($data['BoilerCapacity'], $data['LiquitFuelType'],$data["brand"],$data['brand_tipi']);

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function burnerprojectsave()
    {
        
        $data = \App\Helper\Calc::burnerProjectSave();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function burnerprojectlist()
    {
        $data = \App\Helper\Calc::burnerProjectList();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function getBurnerDetail()
    {
        $data = \App\Helper\Calc::burner();
        $burners = DB::table('api__burner')
                ->where("id",Input::get('id'))
                ->get();
            foreach ($burners as $burner) {
                $data[] = [
                    'id'=>$burner->id,
                    'Boiler_type'=>$burner->burner_type,
                    'Name' => $burner->name,
                    'Brand' => $burner->brand_code,
                    'Type' => $burner->burner_type,
                    'Description' => $burner->description,
                    'LiquitFuelType' => $burner->fuel_type,
                    'MinCapacity' => $burner->min_capacity,
                    'Motor_Power' =>$burner->motor_power,
                    'MaxCapacity' => $burner->max_capacity,
                    'Web' => (string) $burner->web,
                    'Catalog' => (string) $burner->katalog,
                    'Banner'=>$burner->mini_banner,
                    'Producer' => General::getProducer($burner->producer_code),
                    'Seller' => General::getSellerBoiler($burner->producer_code,$burner->p_type),
                ];
            }
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    private function getData($boilercapacity, $fuelType = false,$brand=[],$tipi) {
        $capacity = $boilercapacity/1000;
        if (!$fuelType) {
            $fuelType = Input::get('LiquitFuelType');
        }

        $where = [];
        //$where[] = ['min_capacity', '<=', $capacity];
        $where[] = ['max_capacity', '>=', $capacity];
        $where[] = ['fuel_type', $fuelType];
        $where[] = ['fuel_type_id', $tipi];
       

        /*$brand = Input::get('brand');
        if ($brand != '') {
            $where[] = ['brand_code', $brand];
        }*/
        
        $burners = DB::table('api__burner')
                ->where($where)
                ->whereIn('brand_code', $brand)
                ->orderBy('max_capacity', 'asc')
                ->limit(5)
                ->get();

        $data = [];

        foreach ($burners as $burner) {
            $data[] = [
                'id'=>$burner->id,
                'Boiler_type'=>$burner->burner_type,
                'Name' => $burner->name,
                'Brand' => $burner->brand_code,
                'Type' => $burner->burner_type,
                'Description' => $burner->description,
                'LiquitFuelType' => $burner->fuel_type,
                'MinCapacity' => $burner->min_capacity,
                'MaxCapacity' => $burner->max_capacity,
                'Motor_Power' =>$burner->motor_power,
                'Web' => (string) $burner->web,
                'Catalog' => (string) $burner->katalog,
                'Banner'=>$burner->mini_banner,
                'Producer' => General::getProducer($burner->producer_code),
                'Seller' => General::getSellerBoiler($burner->producer_code,$burner->p_type),
            ];
        }

        return $data;
    }

    public function burnerprojectdelete()
    {
        $data = \App\Helper\Calc::burnerProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function burnerupdate()
    {
        $data = \App\Helper\Calc::burnerProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }
   

    

}
