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

class ExpansionTank extends Controller {

    public function __construct() {
        
    }

    public function getOpen() {

        General::validation('open-expansion-tank');
        $data = \App\Helper\Calc::openExpansionTank();

        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function headerType()
    {
        $data = \App\Helper\Calc::headerType();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function valveType()
    {
        $data = \App\Helper\Calc::valveType();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function waterExpansionGet()
    {
        $data = \App\Helper\Calc::waterExpansion();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function openexpansionSave()
    {
        $data = \App\Helper\Calc::openexpansionProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function openexpansionprojectlist()
    {
        $data = \App\Helper\Calc::openexpansionlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function openexpansionprojectdelete()
    {
        $data = \App\Helper\Calc::openexpansionProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }

    public function openexpansionupdate()
    {
        
        $data = \App\Helper\Calc::openexpansionProjectUpdate();
       
        if($data==1 || $data==0)
        {
           
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
        }else{
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }

    public function getClosed() {

        General::validation('closed-expansion-tank');
        $data = \App\Helper\Calc::closedExpansionTank();
        //yapıldı paremetre degişikligi söz konusu olacak
        $data['Product'] = $this->getCloseExpansionProduct($data['TankVolume'],$data["markalar"],$data["limit"]);


        return response(['success' => true, 'data' => $data], Response::HTTP_OK);

        
    }

    public function getCloseExpansionProduct($deger = false,$brand=[],$limit=3)
    {
        if (!$deger) {
            $deger = Input::get('TankVolume');
        }

        $data = [];
        $where = [];
        $where[] = ['su_hacmi', '>=', $deger];
        $markasarti="";
        //$brand = Input::get('BoilerBrand');
        $brands = DB::table('api__closeexpansionproduct')
                ->where($where)
                ->whereIn('markasi',$brand)
                ->orderBy('su_hacmi', 'asc')
                ->limit($limit)
                ->get();      

        foreach ($brands as $brand) {
            $data[] = [
                'markasi' => $brand->markasi,
                'standard'=>$brand->standard,
                'bbbf_no' => $brand->bbbf_no,
                'tipi' => $brand->tipi,
                'adi' => $brand->adi,
                'su_hacmi' => $brand->su_hacmi,
                'konstruksiyon_basinci' => $brand->konstruksiyon_basinci,
                'yuksekligi' => $brand->yuksekligi,
                'uzunlugu' => $brand->uzunlugu,
                'genisligi' =>$brand->genisligi,
                'agirligi' =>$brand->agirligi,
                'giris_capi' =>$brand->giris_capi,
                'aciklama'=>$brand->aciklama,
                'U_kodu'=>$brand->U_kodu,
                'katalog'=>$brand->katalog,
                'web'=>$brand->web,
                'record_time'=>$brand->record_time,
                'id'=>$brand->id,
                'Producer' => General::getCloseExpansionProducer($brand->U_kodu),
                'Seller' => General::getCloseExpansionSeller($brand->U_kodu),
                'Banner' =>$brand->mini_banner,
            ];
        }

        return $data;


    }

    public function closeexpansionSave()
    {
        $data = \App\Helper\Calc::closeexpansionProjectKaydetme();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function closeexpansionprojectlist()
    {
        $data = \App\Helper\Calc::closeexpansionlistele();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    public function closeexpansionprojectdelete()
    {
        $data = \App\Helper\Calc::closeexpansionProjectDelete();
        return response(['success' => true, 'data' => $data], Response::HTTP_OK);
    }
    
    public function closeexpansionupdate()
    {
        $data = \App\Helper\Calc::closeexpansionProjectUpdate();
         
        if($data==1 || $data==0)
        {
             
            return response(['success' => true, 'data' =>1], Response::HTTP_OK);
            
        }else{
           
            return response(['success' => true, 'data' =>0], Response::HTTP_OK);
        }
    }
}
