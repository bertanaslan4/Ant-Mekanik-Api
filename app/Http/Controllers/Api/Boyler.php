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

class Boyler extends Controller {

    public function __construct() {
        
    }

    public function get() {

        General::validation('boyler');
        $data = \App\Helper\Calc::boyler();
        $data['Product'] = $this->getBoylerProduct($data["SelectedVolume"],$data["markalar"],$data["limit"]);

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function getBoylerProduct($deger =false,$brand=[],$limit=3)
    {
        if (!$deger) {
            $deger = Input::get('SelectedVolume');
        }

        $data = [];
        $where = [];
        $where[] = ['su_hacmi_lt', '>=', $deger];
        $markasarti="";
        //$brand = Input::get('BoilerBrand');
        $brands = DB::table('api__boylerproduct')
                ->where($where)
                ->whereIn('markasi',$brand)
                ->orderBy('su_hacmi_lt', 'asc')
                ->limit($limit)
                ->get();  
                
                
                

        foreach ($brands as $brand) {
            $data[] = [
                'markasi' => $brand->markasi,
                'standard'=>$brand->standard,
                'BBBF_No' => $brand->BBBF_No,
                'tipi' => $brand->tipi,
                'adi' => $brand->adi,
                'su_hacmi_lt' => $brand->su_hacmi_lt,
                'yuksekligi_mm' => $brand->yuksekligi_mm,
                'uzunlugu_mm' => $brand->uzunlugu_mm,
                'genisligi_mm' => $brand->genisligi_mm,
                'agirligi_kg' =>$brand->agirligi_kg,
                'aciklama' =>$brand->aciklama,
                'U_Kodu' =>$brand->U_Kodu,
                'katalog'=>$brand->katalog,
                'web'=>$brand->web,
                'id'=>$brand->id,
                'Producer' => General::getBoylerProducer($brand->U_Kodu),
                'Seller' => General::getBoylerSeller($brand->U_Kodu),
            ];
        }

        return $data;


    }





    

    public function boylerSave() {

        $data = \App\Helper\Calc::boylerProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

    }

    public function boylerprojectlist()
    {
        $data = \App\Helper\Calc::boylerlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function boylerprojectdelete()
    {
        $data = \App\Helper\Calc::boylerProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    
    public function boylerupdate()
    {
        $data = \App\Helper\Calc::boylerProjectUpdate();

        if($data==1 || $data==0)
        {
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }
}
