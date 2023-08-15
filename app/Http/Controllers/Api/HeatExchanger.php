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

class HeatExchanger extends Controller {

    public function __construct() {
        
    }

    public function get() {

        General::validation('heat-exchanger');
        $data = \App\Helper\Calc::heatExchanger();

        $data['HeatExchangers'] = $this->getData($data['HeatSurface']);

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    private function getData($heatSurface = false) {
        if (!$heatSurface) {
            $heatSurface = Input::get('HeatSurface');
        }

        $data = [];
        $where = [];
        $where[] = ['max_heat_surface', '>=', $heatSurface];

        $brand = Input::get('HeatExchangerBrand');
        if ($brand != '') {
            $where[] = ['brand_code', $brand];
        }

        $heatExcs = DB::table('api__heat_exchanger')
                ->where($where)
                ->orderBy('max_heat_surface', 'asc')
                ->limit(3)
                ->get();

        foreach ($heatExcs as $heatExc) {
            $data[] = [
                'id'=>$heatExc->id,
                'Name' => $heatExc->name,
                'Brand' => $heatExc->brand_code,
                'Type' => $heatExc->he_type,
                'Description' => $heatExc->description,
                'MaxHeatSurface' => $heatExc->max_heat_surface,
                'Web' => (string) $heatExc->web,
                'Catalog' => (string) $heatExc->katalog,
                'Producer' => General::getProducer($heatExc->producer_code),
                'Seller' => General::getSeller($heatExc->producer_code),
            ];
        }


        return $data;
    }

    //heatexchanger
    public function heatexchangerSave() {

        $data = \App\Helper\Calc::heatexchangerProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function heatexchangerprojectlist()
    {
        $data = \App\Helper\Calc::heatexchangerlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function heatexchangerprojectdelete()
    {
        $data = \App\Helper\Calc::heatexchangerProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function heatexchangerupdate()
    {
        $data = \App\Helper\Calc::heatexchangerProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }

    }

}
