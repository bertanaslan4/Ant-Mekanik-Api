<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\General;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;

use Mail;
class Calc {

    public static function getcity() {

        $data = Input::all();
        if(!empty($data["Country"]))
        {
            
            if($data["Country"] == "Türkiye")
            {
                
                $sql = DB::table('api__city');
                if (!empty($data['City'])) {
                    $sql = $sql->where('ili','LIKE',$data['City'].'%');
                }
                if (!empty($data['ilcesi'])) {
                    $sql = $sql->where('ilcesi','LIKE',$data['ilcesi'].'%');
                }
            }else if($data["Country"] == "World" ){
               
                $sql = DB::table('api__cityworld');

                if (!empty($data['City'])) {
                    $sql = $sql->where('Country','LIKE',$data['City'].'%');
                }

            }

            $sql = $sql->get();
            return $sql;    


        }else{
            $array = [
                'success'=>false,
                "message"=>"Lütfen gerekli paremetreleri gönderiniz !"

            ];
            return $array;

        }

        /*if (!empty($data['filter_name'])) {
            $sql = $sql->where('pd.name','LIKE',$data['filter_name'].'%');
        }*/

        


        


    }

    public static function getBrand()
    {
        $data = Input::all();

        if(!empty($data["BoilerBrand"]))
        {
           //$column ="id,brand,genus,fuel_type,standard,max_operating_pressure,bbbf_no,boiler_type,name,heat_power,b_height,b_length,b_width,water_volume,weight,fume_con,gas_side_resistance,boiler_efficiency,description,code,katalog,web,record_time,mini_banner";
            //$sql = DB::select("SELECT * FROM api__boiler GROUP BY brand");
            $sql = DB::table('api__boiler')->groupBy('brand')->get();

        }else if(!empty($data["BurnerBrand"]))
        {
            $sql = DB::table('api__burner')->groupBy('brand_code')->get();

        }else if(!empty($data["HeatchangerBrand"]))
        {
            $sql = DB::table('api__heat_exchanger')->groupBy('brand_code')->get();

        }else if(!empty($data["BoylerBrand"]))
        {
            $sql = DB::table('api__boylerproduct')->groupBy('markasi')->get();

        }else if(!empty($data["CloseExpansionBrand"]))
        {
            $sql = DB::table('api__closeexpansionproduct')->groupBy('markasi')->get();

        }else if(!empty($data["HydrophoreBrand"]))
        {
            $sql = DB::table('api__hydrophoreproduct')->groupBy('markasi')->get();

        }else if(!empty($data["PumpBrand"]))
        {
            $sql = DB::table('api__pump')->groupBy('markasi')->get();

        }else{

            $array = [
                'success'=>false,
                "data"=>0

            ];
            return $array;

        }
        return $sql;  

        


    }

    public static function istatislik()
    {
        $data = Input::all();

        $data['KullaniciSayisi'] = DB::table('kullanicilar')->where("role",0)->count();
        $data['AdminSayisi'] = DB::table('kullanicilar')->where("role",1)->count();
        $data['Boiler'] = DB::table('api__boilerproject')->count();
        $data['Boyler'] = DB::table('api__boylerproject')->count();
        $data['Burner'] = DB::table('api__burnerproject')->count();
        $data['Circulationpump'] = DB::table('api__circulationpumpproject')->count();
        $data['Closeexpansion'] = DB::table('api__closeexpansionproject')->count();
        $data['Coldstorage'] = DB::table('api__coldstorageproject')->count();
        $data['Collector'] = DB::table('api__collectorproject')->count();
        $data['Fuelamount'] = DB::table('api__fuelamountproject')->count();
        $data['Fuelamountyearly'] = DB::table('api__fuelamountyearlyproject')->count();
        $data['Heatexchanger'] = DB::table('api__heatexchangerproject')->count();
        $data['Hydrophore'] = DB::table('api__hydrophoreproject')->count();
        $data['Openexpansion'] = DB::table('api__openexpansionproject')->count();
        $data['Paddlebox'] = DB::table('api__paddleboxproject')->count();
        $data['Parkingventilation'] = DB::table('api__parkingventilationproject')->count();
        $data['Pipeinsulation'] = DB::table('api__pipeinsulationproject')->count();
        $data['Pipe'] = DB::table('api__pipeproject')->count();
        $data['Pool'] = DB::table('api__poolproject')->count();
        $data['Rainwater'] = DB::table('api__rainwaterproject')->count();
        $data['Solarenergy'] = DB::table('api__solarenergyproject')->count();
        $data['Shelterventilation'] = DB::table('api__shelterventilationproject')->count();
        return $data;


    }

    public static function boiler() {

        $data = Input::all();
          
        if(isset($data["yedek"]) && !empty($data["yedek"]))
        {
            $yedek = $data["yedek"];
        }else{
            $yedek=0;
        }

        if(isset($data["KK"]) && $data["KK"]!=0)
        {
            $data['BoilerUnitAvgHeat'] = $data["KK"];
        }else{
            //
            $data['BoilerUnitAvgHeat'] = DB::table('api__fuel_type')->where('code', $data['FuelType'])->value('kk');
        }
        // 
        $data['BoilerIncreaseFactor'] = DB::table('api__distribution_pipe')->where('code', $data['DistributionPipe'])->value('zr');;


        
        $data['BoilerCapacity'] = General::calcQk($data["HeatNeed"], $data["Piece"],$yedek);;


        // 
        $data['HeatingSurface'] = $data['BoilerCapacity'] / $data['BoilerUnitAvgHeat'] * (1 + $data['BoilerIncreaseFactor']);;

        return $data;
    }

    public static function boruhesabi()
    {
        try {
            $data = Input::all();
       
            $array = [
                'name'=>"dsadasdas",
                'email'=>"dsadsadas",
                'telefon'=>"4534543534",
                'mesaj'=>"dfgdfgfdgfdgfdgd"
            ];
           $result = Mail::send('iletisim', $array, function ($message) {
                $message->from('tayfun.yesilyurttt@gmail.com', 'İletişim');
                $message->subject("İLETİŞİM FORMU");
                $message->to("tayfunyesilyurt0707@gmail.com");
            });
            return $result;
        } catch (\Throwable $th) {
            return $th;
        }
        

        

    }
    

    public static function boilerProjectSave()
    {
        $data = Input::all();
        $result=DB::table('api__boilerproject')->insert([
            [
                'kullanici_id' =>$data['kullanici_id'],
                'adi' =>$data['adi'],
                'aciklama' =>$data['aciklama'],
                'FuelType' =>$data['FuelType'],
                'DistributionPipe'=>$data['DistributionPipe'],
                'HeatNeed'=>$data['HeatNeed'],
                'Piece'=>$data['Piece'],
                'BoilerUnitAvgHeat'=>$data['BoilerUnitAvgHeat'],
                'BoilerIncreaseFactor'=>$data['BoilerIncreaseFactor'],
                'BoilerCapacity'=>$data['BoilerCapacity'],
                'HeatingSurface'=>$data['HeatingSurface'],
                "Boiler_id"=>$data["Boiler_id"],
                "proje_id"=>$data["proje_id"],
            ],
        ]);
        return $result;
    }
    public static function projeSave(){
        $data = Input::all();
        $result=DB::table('Projeler')->insert([
            [
                'kullanici_id' =>$data['kullanici_id'],
                'proje_no' =>$data['pno'],
                'proje_revizyon' =>$data['prev'],
                'proje_adi' =>$data['adi'],
                'proje_konu'=>$data['konu'],
                'proje_hesap'=>$data['hesap'],
                'proje_kontrol'=>$data['kontrol'],
              
                
            ],
        ]);
        return $result;
    }
    public static function boilerProjectList()
    {
        $data = Input::all();
        $sql = DB::table('api__boilerproject')->where('kullanici_id',$data["kullanici_id"]);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function boilerProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__boilerproject')->where('id', $data['id'])->delete();

        return $result;
    }
    public static function boilerProjectUpdate() 
    {
        $data = Input::all();
        $result = DB::table('api__boilerproject')->where('id', $data['id'])->update();

        return $result;
    }
    public static function burner() {

        $data = Input::all();

        if (!isset($data['BurnerEfficiency'])) {
            $data['BurnerEfficiency'] = General::getDefault('BurnerEfficiency');
        }

        $data['LowerHeatValue'] = DB::table('api__liquit_fuel_type')->where('name', $data['LiquitFuelType'])->value('lower_heat_value');
        $data['brand_tipi']=DB::table('api__liquit_fuel_type')->where('name', $data['LiquitFuelType'])->value('tipi');
        $data['Capacity'] = 3.6 * ($data['BoilerCapacity'] / (($data['LowerHeatValue'] / 100) * $data['BurnerEfficiency']));

        return $data;
    }
    public static function burnerProjectSave()
    {
        $data = Input::all();
        $result=DB::table('api__burnerproject')->insert([
            [
                'kullanici_id' =>$data['kullanici_id'],
                'adi'=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                'LiquitFuelType' =>$data['LiquitFuelType'],
                'BoilerCapacity'=>$data['BoilerCapacity'],
                'BurnerEfficiency'=>$data['BurnerEfficiency'],
                'LowerHeatValue'=>$data['LowerHeatValue'],
                'Capacity'=>$data['Capacity'],
                'Burner_id'=>$data['Burner_id'],
                'proje_id'=>$data['proje_id'],
            ],
        ]);
        return $result;
    }
    public static function burnerProjectList()
    {
        $data = Input::all();
        $sql = DB::table('api__burnerproject')->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function burnerProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__burnerproject')->where('id', $data['id'])->delete();

        return $result;
    }
  
    public static function heatExchanger() {

        $data = Input::all();
//        $data['HeatExchangerCapacity'] = General::calcQk($data['HeatNeed'], $data['Piece']);
        $data['LogHeatDiff'] = ($data['HeaterFluidAvg'] - $data['HeatedFluidAvg']) / log($data['HeaterFluidAvg'] / $data['HeatedFluidAvg'], M_E);//formül 1
        $data['HeatSurface'] = ($data['HeatNeed'] / ($data['TotalPass'] * $data['LogHeatDiff'])) * (1 + $data['PollutionFactor']);//formül 2
        $data['HeatSurface'] = General::calcQk($data['HeatSurface'],$data['Piece'],$data["yedek"]);
        $data['HeatExchangerCapacity'] = ($data['TotalPass'] * $data['HeatSurface'] * $data['LogHeatDiff']) / $data['Piece']; //formül 3 

        return $data;
    }

    //heatexchanger
    public static function heatexchangerProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $result=DB::table('api__heatexchangerproject')->insert([
                    [
                        "kullanici_id"=>$data["kullanici_id"],
                        'adi' =>$data['adi'],
                        'aciklama' =>$data['aciklama'],
                        "HeatNeed"=>$data["HeatNeed"],
                        "heatexchanger_id"=>$data["heatexchanger_id"],
                        "TotalPass"=>$data["TotalPass"],
                        "Piece"=>$data["Piece"],
                        "HeaterFluidAvg"=>$data["HeaterFluidAvg"],
                        "HeatedFluidAvg"=>$data["HeatedFluidAvg"],
                        "PollutionFactor"=>$data["PollutionFactor"],
                        "LogHeatDiff"=>$data["LogHeatDiff"],
                        "HeatSurface"=>$data["HeatSurface"],
                        "HeatExchangerCapacity"=>$data["HeatExchangerCapacity"],
                        
                    ]
                ]);
            
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function heatexchangerlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__heatexchangerproject')
        ->leftJoin('api__heat_exchanger','api__heatexchangerproject.heatexchanger_id','=','api__heat_exchanger.id')
        ->where('api__heatexchangerproject.kullanici_id', $data['kullanici_id'])
        ->select('api__heatexchangerproject.*','api__heat_exchanger.Name','api__heat_exchanger.brand_code as Brand','api__heat_exchanger.he_type as Type','api__heat_exchanger.Description','api__heat_exchanger.max_heat_surface as MaxHeatSurface','api__heat_exchanger.katalog as Catalog');
        if(!empty($data["id"]))
        {
            $sql=$sql->where("api__heatexchangerproject.id",$data["id"]); 
        }
         
        return $sql->get();
    }
    public static function heatexchangerProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__heatexchangerproject')->where('id', $data['id'])->delete();
        return $result;
    }
    

    public static function fuelAmount() {

        $data = Input::all();

        $data['LowerHeatValue'] = DB::table('api__liquit_fuel_type')->where('name', $data['LiquitFuelType'])->value('lower_heat_value');
        $data['FuelAmount'] = 3.6 * ($data['BoilerCapacity'] * $data['DailyWorkingTime'] * $data['StoredDays']) / ($data['LowerHeatValue'] * $data['BoilerEfficiency']);
        $data['FuelAmount'] = $data['FuelAmount'] / $data['Piece'];

        $data['FuelDensity'] = DB::table('api__liquit_fuel_type')->where('name', $data['LiquitFuelType'])->value('fuel_density');
        $data['LiquidTank'] = $data['FuelAmount'] / ($data['FuelDensity'] * $data['LiquidOccupancyRate']);

        $data['SolidFuelSurface'] = $data['FuelAmount'] / ($data['FuelDensity'] * $data['SolidStackLoad']);

        $burner = self::burner();
        $data['PreheaterLoad'] = $burner['Capacity'] * $data['FuelTemperature'] * $data['AvgFuelTemperature'] * 3.6;

        return $data;
    }
    public static function fuelamountProjectSave()
    {
        $data = Input::all();
        $result=DB::table('api__fuelamountproject')->insert([
            [
                'kullanici_id' =>$data['kullanici_id'],
                'adi'=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                'FuelType' =>$data['FuelType'],
                'Piece'=>$data['Piece'],
                'BoilerEfficiency'=>$data['BoilerEfficiency'],
                'BoilerCapacity'=>$data['BoilerCapacity'],
                'LiquitFuelType'=>$data['LiquitFuelType'],
                'AvgFuelTemperature'=>$data['AvgFuelTemperature'],
                'FuelTemperature' =>$data['FuelTemperature'],
                'DailyWorkingTime'=>$data['DailyWorkingTime'],
                'YearlyWorkingTime'=>$data['YearlyWorkingTime'],
                'StoredDays'=>$data['StoredDays'],
                'LiquidOccupancyRate'=>$data['LiquidOccupancyRate'],
                'SolidStackLoad'=>$data['SolidStackLoad'],
                'LowerHeatValue' =>$data['LowerHeatValue'],
                'FuelAmount'=>$data['FuelAmount'],
                'FuelDensity'=>$data['FuelDensity'],
                'LiquidTank'=>$data['LiquidTank'],
                'SolidFuelSurface'=>$data['SolidFuelSurface'],
                'PreheaterLoad'=>$data['PreheaterLoad'],
                'proje_id'=>$data['proje_id'],
            ],
        ]);
        return $result;
    }

    public static function fuelamountProjectList()
    {
        $data = Input::all();
        $sql = DB::table('api__fuelamountproject')->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function fuelamountProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__fuelamountproject')->where('id', $data['id'])->delete();

        return $result;
    }
    //fuel amount yearly
    public static function fuelAmountYearly() {

        $data = Input::all();

        General::getDefaults($data, ['BoilerEfficiency', 'AvgFuelTemperature', 'FuelTemperature', 'YearlyWorkingTime', 'LiquidOccupancyRate', 'SolidStackLoad']);

        $data['LowerHeatValue'] = DB::table('api__liquit_fuel_type')->where('name', $data['LiquitFuelType'])->value('lower_heat_value');
        $data['FuelAmount'] = 360000 * $data['YearlyHeatingEnergy'] / ($data['LowerHeatValue'] * $data['BoilerEfficiency']) * $data['BuildingArea'];
        $data['FuelAmount'] = $data['FuelAmount'] / $data['Piece'];

        $data['FuelDensity'] = DB::table('api__liquit_fuel_type')->where('name', $data['LiquitFuelType'])->value('fuel_density');
        $data['LiquidTank'] = $data['FuelAmount'] / ($data['FuelDensity'] * $data['LiquidOccupancyRate']);

        $data['SolidFuelSurface'] = $data['FuelAmount'] / ($data['FuelDensity'] * $data['SolidStackLoad']);

        $burner = self::burner();
        $data['PreheaterLoad'] = $burner['Capacity'] * $data['FuelTemperature'] * $data['AvgFuelTemperature'] * 3.6;

        return $data;
    }

    public static function fuelamountyearlyProjectKaydetme()
    {
        $data = Input::all();

        $result=DB::table('api__fuelamountyearlyproject')->insert([
            [
                'kullanici_id'=>$data["kullanici_id"],
                'adi'=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                'FuelType' =>$data['FuelType'],
                'Piece'=>$data['Piece'],
                'BoilerEfficiency'=>$data['BoilerEfficiency'],
                'BurnerEfficiency'=>$data['BurnerEfficiency'],
                'BoilerCapacity'=>$data['BoilerCapacity'],
                'LiquitFuelType'=>$data['LiquitFuelType'],
                'AvgFuelTemperature'=>$data['AvgFuelTemperature'],
                'FuelTemperature' =>$data['FuelTemperature'],
                'YearlyHeatingEnergy'=>$data['YearlyHeatingEnergy'],
                'YearlyWorkingTime'=>$data['YearlyWorkingTime'],
                'BuildingArea'=>$data['BuildingArea'],
                'LiquidOccupancyRate'=>$data['LiquidOccupancyRate'],
                'SolidStackLoad'=>$data['SolidStackLoad'],
                'LowerHeatValue' =>$data['LowerHeatValue'],
                'FuelAmount'=>$data['FuelAmount'],
                'FuelDensity'=>$data['FuelDensity'],
                'LiquidTank'=>$data['LiquidTank'],
                'SolidFuelSurface'=>$data['SolidFuelSurface'],
                'PreheaterLoad'=>$data['PreheaterLoad'],
                'proje_id'=>$data['proje_id'],
            ],
        ]);
        return $result;
    }

    public static function fuelamountyearlyProjectListele()
    {
        $data = Input::all();
        $sql = DB::table('api__fuelamountyearlyproject')->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function fuelamountyearlyProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__fuelamountyearlyproject')->where('id', $data['id'])->delete();

        return $result;
    }



    public static function openExpansionTank() {

        $data = Input::all();
        $data['Expansion'] = DB::table('api__heater_type')->where('name', $data['HeaterType'])->value('f');
        $data['WaterExpansion'] = DB::table('api__water_expansion')->where('heat', $data['WaterHeat'])->value('expansion');

        $data['WaterVolume'] = ($data['BoilerCapacity'] / 1000) * $data['Expansion'];
        $data['TankVolume'] = ($data['WaterVolume'] * ($data['WaterExpansion'] / 100)) * 2;

        $data['DiameterG'] = 15 + (1.4 * sqrt($data['BoilerCapacity'] / 1000)); 
        $data['DiameterD'] = 15 + (0.93 * sqrt($data['BoilerCapacity'] / 1000));
        $data['DiameterH'] = 15;
        
        


        $data['DiameterInchG'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['DiameterG'])->value('inch');
        $data['DiameterInchD'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['DiameterD'])->value('inch');
        $data['DiameterInchH'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['DiameterH'])->value('inch');
        
        $data['DiameterdnG'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['DiameterG'])->value('nominal_d');
        $data['DiameterdnD'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['DiameterD'])->value('nominal_d');
        $data['DiameterdnH'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['DiameterH'])->value('nominal_d');

        
        return $data;
    }

    public static function headerType()
    {
        $data = DB::table('api__heater_type')->get();

        return $data;
    }
    public static function valveType()
    {
        $data = DB::table('api__valve')->get();

        return $data;
    }

    public static function waterExpansion()
    {
        $data = DB::table('api__water_expansion')->get();

        return $data;
    }
    public static function openexpansionProjectKaydetme()
    {
        try {
            $data = Input::all();
            $result=DB::table('api__openexpansionproject')->insert([
                [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    'HeaterType' =>$data['HeaterType'],
                    'WaterHeat' =>$data['WaterHeat'],
                    'BoilerCapacity' =>$data['BoilerCapacity'],
                    'Expansion' =>$data['Expansion'],
                    'WaterExpansion' =>$data['WaterExpansion'],
                    'WaterVolume' =>$data['WaterVolume'],
                    'TankVolume' =>$data['TankVolume'],
                    'DiameterG' =>$data['DiameterG'],
                    'DiameterD' =>$data['DiameterD'],
                    'DiameterH' =>$data['DiameterH'],
                    'DiameterInchG' =>$data['DiameterInchG'],
                    'DiameterInchD' =>$data['DiameterInchD'],
                    'DiameterInchH' =>$data['DiameterInchH'],
                    'proje_id'=>$data['proje_id'],
                ],
            ]);
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
        
    }
    public static function openexpansionlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__openexpansionproject')->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function openexpansionProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__openexpansionproject')->where('id', $data['id'])->delete();

        return $result;
    }

    public static function closedExpansionTank() {

        $data = Input::all();
        General::getDefaults($data, ['OpeningPressure', 'Piece']);

        $min_max = DB::table("api__water_expansion")->select(DB::raw('MIN(heat) AS min, MAX(heat) AS max'))->get();
        $min_expansion = DB::table('api__water_expansion')->where('heat',$min_max[0]->min)->get();
        $max_expansion = DB::table('api__water_expansion')->where('heat',$min_max[0]->max)->get();
        
        

        if($data["WaterHeat"]<$min_max[0]->min)
        {
            $db_waterheat = $min_max[0]->min;

        }else if($data["WaterHeat"]>$min_max[0]->max)
        {
            $db_waterheat = $min_max[0]->max;
        }else{

            $db_waterheat = $data["WaterHeat"];

            $data['WaterExpansion'] = DB::table('api__water_expansion')->where('heat', $data['WaterHeat'])->value('expansion');
        }

        if($data["WaterExpansion"]==null)
        {
            $result = DB::select( DB::raw("SELECT  (SELECT max(heat) 
                FROM api__water_expansion d
                WHERE  d.heat < ".$data['WaterHeat']."
            ) as min_sql, (SELECT min(heat) 
                FROM api__water_expansion d
                WHERE  d.heat > ".$data['WaterHeat']."
            ) as max_sql from  api__water_expansion   LIMIT 1"));

            $min_expansion = DB::table('api__water_expansion')->where('heat',$result[0]->min_sql)->get();
            $max_expansion = DB::table('api__water_expansion')->where('heat',$result[0]->max_sql)->get();
            
            $b5 =$result[0]->min_sql;
            $b7 = $db_waterheat;
            $b9 = $result[0]->max_sql;

            $d5 = $min_expansion[0]->expansion;
            $d7 = 0;
            $d9 =$max_expansion[0]->expansion;

            $data['WaterExpansion']=$d5-((($b5-$b7)*($d5-$d9))/($b5-$b9));
        }

        $data['Expansion'] = DB::table('api__heater_type')->where('name', $data['HeaterType'])->value('f');

        $data['UpperPressure'] = $data['OpeningPressure'] - 0.5;
        $data['TankPrePressure'] = $data['StaticHeight'] / 10;

        $data['WaterVolume'] = $data['Expansion'] * ($data['BoilerCapacity'] / 1000);
        $data['ExpandingWater'] = $data['WaterVolume'] * ($data['WaterExpansion'] / 100);

        $data['StartPreWaterVolume'] = 0.005 * $data['WaterVolume'];
        $data['TankVolume'] = ($data['StartPreWaterVolume'] + $data['ExpandingWater']) * (($data['UpperPressure'] + 1) / ($data['UpperPressure'] - $data['TankPrePressure']));

        if ($data['TankVolume'] < 0) {
            return response(['success' => false], Response::HTTP_NOT_ACCEPTABLE);
        }

        $valve = DB::table('api__valve')->where('type', $data["ValveType"])
                ->where('effective_pressure', '>=', $data['OpeningPressure'])
                ->where('boiler_capacity', '>=', ($data['BoilerCapacity'] / 1000) / $data['Piece'])
                ->orderBy('effective_pressure', 'ASC')
                ->orderBy('boiler_capacity', 'ASC')
                ->limit(1)
                ->first();

        if ($valve) {
            $data['ValveDiameter'] = $valve->dn;
            $data['ValveInch'] = $valve->inch;
        }

        return $data;
    }

    public static function closeexpansionProjectKaydetme()
    {
        try {
            $data = Input::all();
            $result=DB::table('api__closeexpansionproject')->insert([
                [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "BoilerCapacity"=>$data["BoilerCapacity"],
                    "HeaterType"=>$data["HeaterType"],
                    "WaterHeat"=>$data["WaterHeat"],
                    "StaticHeight"=>$data["StaticHeight"],
                    "OpeningPressure"=>$data["OpeningPressure"],
                    "ValveType"=>$data["ValveType"],
                    "Piece"=>$data["Piece"],
                    "Expansion"=>$data["Expansion"],
                    "WaterExpansion"=>$data["WaterExpansion"],
                    "UpperPressure"=>$data["UpperPressure"],
                    "TankPrePressure"=>$data["TankPrePressure"],
                    "WaterVolume"=>$data["WaterVolume"],
                    "ExpandingWater"=>$data["ExpandingWater"],
                    "StartPreWaterVolume"=>$data["StartPreWaterVolume"],
                    "TankVolume"=>$data["TankVolume"],
                    "ValveDiameter"=>$data["ValveDiameter"],
                    "ValveInch"=>$data["ValveInch"],
                    "closeexpansion_id"=>$data["closeexpansion_id"],
                ],
            ]);
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
        
    }
    public static function closeexpansionlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__closeexpansionproject')->select('api__closeexpansionproject.*','api__closeexpansionproduct.markasi','api__closeexpansionproduct.standard','api__closeexpansionproduct.bbbf_no','api__closeexpansionproduct.tipi','api__closeexpansionproduct.adi as product_adi','api__closeexpansionproduct.su_hacmi','api__closeexpansionproduct.konstruksiyon_basinci','api__closeexpansionproduct.yuksekligi','api__closeexpansionproduct.uzunlugu','api__closeexpansionproduct.genisligi','api__closeexpansionproduct.agirligi','api__closeexpansionproduct.giris_capi','api__closeexpansionproduct.aciklama','api__closeexpansionproduct.U_kodu','api__closeexpansionproduct.katalog','api__closeexpansionproduct.web','api__closeexpansionproduct.record_time','api__closeexpansionproducer.Urun_tipi','api__closeexpansionproducer.U_Uretici','api__closeexpansionproducer.U_adres1','api__closeexpansionproducer.U_adres2','api__closeexpansionproducer.U_semt','api__closeexpansionproducer.U_sehir','api__closeexpansionproducer.U_ulke','api__closeexpansionproducer.U_posta_kodu','api__closeexpansionproducer.U_telefon','api__closeexpansionproducer.U_telefon','api__closeexpansionproducer.U_faks','api__closeexpansionproducer.U_Kodu','api__closeexpansionseller.S_Uretici','api__closeexpansionseller.S_Satici','api__closeexpansionseller.S_adres1','api__closeexpansionseller.S_adres2','api__closeexpansionseller.S_semt','api__closeexpansionseller.S_sehir','api__closeexpansionseller.S_ulke','api__closeexpansionseller.S_posta_kodu','api__closeexpansionseller.S_telefon','api__closeexpansionseller.S_faks','api__closeexpansionseller.U_Kodu')
        ->leftJoin('api__closeexpansionproduct', 'api__closeexpansionproject.closeexpansion_id', '=', 'api__closeexpansionproduct.id')
        ->leftJoin('api__closeexpansionproducer', 'api__closeexpansionproducer.U_Kodu', '=', 'api__closeexpansionproduct.U_Kodu')
        ->leftJoin('api__closeexpansionseller', 'api__closeexpansionseller.U_Kodu', '=', 'api__closeexpansionproducer.U_Kodu')
        ->where('api__closeexpansionproject.kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("api__closeexpansionproject.id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function closeexpansionProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__closeexpansionproject')->where('id', $data['id'])->delete();

        return $result;
    }

    public static function circulationPump() {

        $data = Input::all();

        $data['PumpFlow'] = ($data['BoilerCapacity'] / $data['Piece']) / ($data['SpecificHeat'] * $data['Density'] * $data['TemperatureDiff']) * 3600;
        $data['MotorPower'] = ($data['PressureDrop'] * ($data['PumpFlow'] / 3600)) / (($data['PumpEfficiency'] / 100) * ($data['EngineEfficiency'] / 100));



        return $data;
    }

    public static function circulationpumpProjectKaydetme()
    {
        try {
            $data = Input::all();
            $result=DB::table('api__circulationpumpproject')->insert([
                [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "BoilerCapacity"=>$data["BoilerCapacity"],
                    "TemperatureDiff"=>$data["TemperatureDiff"],
                    "Piece"=>$data["Piece"],
                    "PressureDrop"=>$data["PressureDrop"],
                    "PumpEfficiency"=>$data["PumpEfficiency"],
                    "EngineEfficiency"=>$data["EngineEfficiency"],
                    "SpecificHeat"=>$data["SpecificHeat"],
                    "Density"=>$data["Density"],
                    "PumpFlow"=>$data["PumpFlow"],
                    "MotorPower"=>$data["MotorPower"],
                    "circulation_id"=>$data["circulation_id"],
                    "proje_id"=>$data["proje_id"],
                ],
            ]);
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
        
    }
    public static function circulationpumplistele()
    {
        $data = Input::all();
        $sql = DB::table('api__circulationpumpproject')->select('api__circulationpumpproject.*','api__pump.markasi','api__pump.standardı','api__pump.bbbf_no','api__pump.tipi','api__pump.kap_1_m3','api__pump.basyuk_1_mSS','api__pump.kap_2_m3','api__pump.basyuk_2_mSS','api__pump.kap_3_m3','api__pump.basyuk_3_mSS','api__pump.kap_4_m3','api__pump.basyuk_4_mSS','api__pump.kap_5_m3','api__pump.basyuk_5_mSS','api__pump.devir_d/d','api__pump.motor_gucu_KW','api__pump.agirligi_kg','api__pump.baglanti_ebadi','api__pump.u_kodu','api__pump.katalog','api__pump.web','api__pump.record_time')
        ->leftJoin('api__pump', 'api__circulationpumpproject.circulation_id', '=', 'api__pump.id')
        ->where('api__circulationpumpproject.kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("api__circulationpumpproject.id",$data["id"]);

        }
        return $sql->get();
    }
    public static function circulationpumpProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__circulationpumpproject')->where('id', $data['id'])->delete();

        return $result;
    }

    public static function boyler() {

        $data = Input::all();
        General::getDefaults($data, ['TempIn', 'TempOut', ["SpecificHeat", "SpecificHeatK"], ["Density", "DensityK"],]);

        $build = DB::table('api__build_type')->where('build_type', $data['BuildType'])->first();
        $data['StorageFactor'] = $build->storage_factor;
        $data['UserFactor'] = $build->user_factor;

//        $equipments = $data['Equipments'];
        $data['WaterConsumption'] = 0;
            
        foreach ($data['Equipments'] as &$e) {
            try {
                $e['WaterConsumption'] = DB::table('api__equipment')->where('equipment', $e['Name'])->value($build->build_code);
            } catch (\Exception $exc) {
                $e['WaterConsumption'] = 0;
            }

            $e['Piece'] = $e['Piece'] > 0 ? $e['Piece'] : 1;
            $e['WaterConsumption'] = $e['WaterConsumption'] * $e['Piece'] * $data['StorageFactor'];
            $data['WaterConsumption'] += $e['WaterConsumption'];
        }

        $data['AvgWaterConsumption'] = $data['WaterConsumption'] * $data['UserFactor'];
        $data['BoylerCapacity'] = $data['AvgWaterConsumption'] * $data['StorageFactor'];

        $range = 50;
        $data['SelectedVolume'] = ceil($data['BoylerCapacity'] / $range) * $range;

        $data['TemperatureDiff'] = $data['TempOut'] - $data['TempIn'];
        $data['HeatingLoad'] = ((($data['SelectedVolume'] * $data['Density']) / 3.6) * $data['SpecificHeat'] * $data['TemperatureDiff']) / $data['PrepareTime'];


        return $data;
    }

    public static function boylerProjectKaydetme()
    {
        try {
            $data = Input::all();
            $id=DB::table('api__boylerproject')->insertGetId(array(
                
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "BuildType"=>$data["BuildType"],
                    "PrepareTime"=>$data["PrepareTime"],
                    "SpecificHeat"=>$data["SpecificHeat"],
                    "Density"=>$data["Density"],
                    "SelectedVolume"=>$data["SelectedVolume"],
                    "TemperatureDiff"=>$data["TemperatureDiff"],
                    "HeatingLoad"=>$data["HeatingLoad"],
                    "BoylerTempIn"=>$data["BoylerTempIn"],
                    "BoylerTempOut"=>$data["BoylerTempOut"],
                    "TempIn"=>$data["TempIn"],
                    "TempOut"=>$data["TempOut"],
                    "Piece"=>$data["Piece"],
                    "StorageFactor"=>$data["StorageFactor"],
                    "UserFactor"=>$data["UserFactor"],
                    "WaterConsumption"=>$data["WaterConsumption"],
                    "AvgWaterConsumption"=>$data["AvgWaterConsumption"],
                    "BoylerCapacity"=>$data["BoylerCapacity"],
                    "boyler_id"=>$data["boyler_id"],
                    'proje_id'=>$data["proje_id"],
                
            ));
            if(!empty($id))
            {
                foreach ($data["Equipment"] as $key => $value) {

                    $result=DB::table('api__Equipmentt')->insert(array(
                
                        'Equipment_name'=>$value["Name"],
                        'Equipment_piece'=>$value["Piece"],
                        "boyler_id"=>$id,
                        "WaterConsumption"=>$value["WaterConsumption"],
                    
                ));
                    
                }
                return $result;
                
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
    public static function boylerlistele()
    {
        $data = Input::all();
        
        $sql = DB::table('api__boylerproject')
        ->select('api__boylerproject.*','api__boylerproduct.markasi','api__boylerproduct.standard','api__boylerproduct.BBBF_No','api__boylerproduct.tipi','api__boylerproduct.adi as product_adi','api__boylerproduct.su_hacmi_lt','api__boylerproduct.yuksekligi_mm','api__boylerproduct.uzunlugu_mm','api__boylerproduct.genisligi_mm','api__boylerproduct.agirligi_kg','api__boylerproduct.aciklama','api__boylerproduct.U_Kodu','api__boylerproduct.katalog','api__boylerproduct.web','api__boylerproduct.record_time','api__boylerproducer.Urun_tipi','api__boylerproducer.U_Uretici','api__boylerproducer.U_adres1','api__boylerproducer.U_adres2','api__boylerproducer.U_semt','api__boylerproducer.U_sehir','api__boylerproducer.U_ulke','api__boylerproduct.uzunlugu_mm','api__boylerproduct.genisligi_mm','api__boylerproduct.agirligi_kg','api__boylerproduct.aciklama','api__boylerproduct.U_Kodu','api__boylerproduct.katalog','api__boylerproduct.web','api__boylerproduct.record_time','api__boylerproducer.Urun_tipi','api__boylerproducer.U_Uretici','api__boylerproducer.U_adres1','api__boylerproducer.U_adres2','api__boylerproducer.U_semt','api__boylerproducer.U_sehir','api__boylerproducer.U_ulke','api__boylerproduct.uzunlugu_mm','api__boylerproduct.genisligi_mm','api__boylerproduct.agirligi_kg','api__boylerproduct.aciklama','api__boylerproduct.U_Kodu','api__boylerproduct.katalog','api__boylerproduct.web','api__boylerproduct.record_time','api__boylerproducer.Urun_tipi','api__boylerproducer.U_Uretici','api__boylerproducer.U_adres1','api__boylerproducer.U_adres2','api__boylerproducer.U_semt','api__boylerproducer.U_sehir','api__boylerproducer.U_ulke','api__boylerproducer.U_posta_kodu','api__boylerproducer.U_telefon','api__boylerproducer.U_faks','api__boylerproducer.U_Kodu','api__boylerseller.S_Uretici','api__boylerseller.S_Satici','api__boylerseller.S_adres1','api__boylerseller.S_adres2','api__boylerseller.S_semt','api__boylerseller.S_sehir','api__boylerseller.S_ulke','api__boylerseller.S_posta_kodu','api__boylerseller.S_telefon','api__boylerseller.S_faks','api__boylerseller.U_Kodu')
        ->leftJoin('api__boylerproduct', 'api__boylerproject.boyler_id', '=', 'api__boylerproduct.id')
        ->leftJoin('api__boylerproducer', 'api__boylerproducer.U_Kodu', '=', 'api__boylerproduct.U_Kodu')
        ->leftJoin('api__boylerseller', 'api__boylerseller.U_Kodu', '=', 'api__boylerproducer.U_Kodu')
        ->where('api__boylerproject.kullanici_id', $data['kullanici_id']);
        
        if(!empty($data["id"]))
        {
            $sql=$sql->where("api__boylerproject.id",$data["id"]);
            $data["Equipment"]=DB::table('api__Equipmentt')->where('boyler_id',$data["id"])->get();
            //$data["Product"] =DB::table("api__boylerproduct")->where('id',$data["id"])
        }
        
        $data[] = $sql->get();
        return $data;
    }
    public static function boylerProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__boylerproject')->where('id', $data['id'])->delete();
        $result=DB::table('api__Equipmentt')->where('boyler_id',$data["id"])->delete();

        return $result;
    }

    public static function rainWater() {

        $data = Input::all();

        $data['RainAvg'] = DB::table('api__city_rain')->where('city', $data['Location'])->value('avg_rain');
        $data['UnloadFactor'] = DB::table('api__roof_type')->where('roof_type', $data['RoofType'])->value('unload_factor');
        $data['SuddenNeed'] = ($data['RainArea'] / 10000) * $data['RainAvg'] * $data['UnloadFactor'];
        $data['FlowSection'] = $data['SuddenNeed'] / $data['RainPipe'];
        $data['ColumnDiameter'] = DB::table('api__rain_column_diameter')->where('flow', '>=', $data['FlowSection'])->value('diameter');

        return $data;
    }

    public static function rainwaterProjectKaydetme()
    {
        try {
            $data = Input::all();
            $result=DB::table('api__rainwaterproject')->insert([
                [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "Location"=>$data["Location"],
                    "RainArea"=>$data["RainArea"],
                    "RoofType"=>$data["RoofType"],
                    "RainPipe"=>$data["RainPipe"],
                    "RainAvg"=>$data["RainAvg"],
                    "UnloadFactor"=>$data["UnloadFactor"],
                    "SuddenNeed"=>$data["SuddenNeed"],
                    "FlowSection"=>$data["FlowSection"],
                    "ColumnDiameter"=>$data["ColumnDiameter"],
                    "proje_id"=>$data["proje_id"],
                ],
            ]);
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function rainwaterlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__rainwaterproject')->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function rainwaterProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__rainwaterproject')->where('id', $data['id'])->delete();

        return $result;
    }

    public static function hydrophore() {

        $data = Input::all();
        General::getDefaults($data, ['UsingConcurrentFactor', 'HydrophoreFactor', 'Piece']);

        $data['DailyWaterConsumption'] = DB::table('api__build_water_consumption')->where('build_type', $data['BuildType'])->value('daily_water');

        $data['SuddenNeed'] = (($data['DailyWaterConsumption'] * $data['NumberOfPeople'] * $data['UsingConcurrentFactor']) / 1000) / $data['Piece'];
        $data['TankVolume'] = ($data['DailyWaterConsumption'] * $data['NumberOfPeople'] * $data['StoredTime']) / 1000;
        $data['HydrophoreFlow'] = $data['SuddenNeed'] / $data['HydrophoreFactor'];
        $data['TotalLoss'] = $data['PipePressureLoss'] + $data['OtherLosses'];
        return $data;
    }

    public static function hydrophoreProjectKaydetme()
    {
        try {
            $data = Input::all();
            $result=DB::table('api__hydrophoreproject')->insert([
                [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "BuildType"=>$data["BuildType"],
                    "NumberOfPeople"=>$data["NumberOfPeople"],
                    "StoredTime"=>$data["StoredTime"],
                    "Piece"=>$data["Piece"],
                    "PipePressureLoss"=>$data["PipePressureLoss"],
                    "OtherLosses"=>$data["OtherLosses"],
                    "UsingConcurrentFactor"=>$data["UsingConcurrentFactor"],
                    "HydrophoreFactor"=>$data["HydrophoreFactor"],
                    "DailyWaterConsumption"=>$data["DailyWaterConsumption"],
                    "SuddenNeed"=>$data["SuddenNeed"],
                    "TankVolume"=>$data["TankVolume"],
                    "HydrophoreFlow"=>$data["HydrophoreFlow"],
                    "TotalLoss"=>$data["TotalLoss"],
                    "hydrophorep_id"=>$data["hydrophorep_id"],
                ],
            ]);
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function hydrophorelistele()
    {
        $data = Input::all();
        $sql = DB::table('api__hydrophoreproject')->select('api__hydrophoreproject.*','api__hydrophoreproduct.markasi','api__hydrophoreproduct.standardi','api__hydrophoreproduct.bbbf_no','api__hydrophoreproduct.tipi','api__hydrophoreproduct.tank_hacmi','api__hydrophoreproduct.kap_1_m3','api__hydrophoreproduct.basyuk_1_mSS','api__hydrophoreproduct.kap_2_m3','api__hydrophoreproduct.basyuk_2_mSS','api__hydrophoreproduct.kap_3_m3','api__hydrophoreproduct.basyuk_3_mSS','api__hydrophoreproduct.kap_4_m3','api__hydrophoreproduct.basyuk_4_mSS','api__hydrophoreproduct.kap_5_m3','api__hydrophoreproduct.basyuk_5_mSS','api__hydrophoreproduct.devir','api__hydrophoreproduct.motor_gucu','api__hydrophoreproduct.agirligi','api__hydrophoreproduct.baglanti_ebadi','api__hydrophoreproduct.aciklama','api__hydrophoreproduct.U_kodu','api__hydrophoreproduct.katalog','api__hydrophoreproduct.web','api__hydrophoreproduct.record_time','api__hydrophoreproducer.Urun_tipi','api__hydrophoreproducer.U_Uretici','api__hydrophoreproducer.U_adres1','api__hydrophoreproducer.U_adres2','api__hydrophoreproducer.U_semt','api__hydrophoreproducer.U_sehir','api__hydrophoreproducer.U_ulke','api__hydrophoreproducer.U_posta_kodu','api__hydrophoreproducer.U_telefon','api__hydrophoreproducer.U_faks','api__hydrophoreproducer.U_Kodu','api__hydrophoreseller.S_Uretici','api__hydrophoreseller.S_Satici','api__hydrophoreseller.S_adres1','api__hydrophoreseller.S_adres2','api__hydrophoreseller.S_semt','api__hydrophoreseller.S_sehir','api__hydrophoreseller.S_ulke','api__hydrophoreseller.S_posta_kodu','api__hydrophoreseller.S_telefon','api__hydrophoreseller.S_faks','api__hydrophoreseller.U_Kodu')
        ->leftJoin('api__hydrophoreproduct', 'api__hydrophoreproject.hydrophorep_id', '=', 'api__hydrophoreproduct.id')
        ->leftJoin('api__hydrophoreproducer', 'api__hydrophoreproducer.U_Kodu', '=', 'api__hydrophoreproduct.U_kodu')
        ->leftJoin('api__hydrophoreseller', 'api__hydrophoreseller.U_Kodu', '=', 'api__hydrophoreproducer.U_Kodu')
        ->where('api__hydrophoreproject.kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("api__hydrophoreproject.id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function hydrophoreProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__hydrophoreproject')->where('id', $data['id'])->delete();

        return $result;
    }

    public static function collector() {

        $data = Input::all();
        General::getDefaults($data, ['UsingConcurrentFactor', 'HydrophoreFactor', 'Piece']);

        $data['WaterSpeed'] = DB::table('api__water_speed')->where('regime', $data['WaterRegime'])->value('water_speed');
        $data['CollectorFlow'] = ($data['CollectorCapacity'] / ($data['SpecificHeat'] * $data['Density'] * $data['TempratureAvg'])) * 3600;

        $data['CollectorDiameter'] = sqrt(($data['CollectorFlow'] * 4) / (3600 * $data['WaterSpeed'] * 3.14)) * 1000;
        $data['CollectorDiameter'] = $data['CollectorDiameter'] * (1 + ($data['TransferorWater'] / 100));

        $data['CollectorInch'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['CollectorDiameter'])->value('inch');


        return $data;
    }

    public static function collectorProjectKaydetme()
    {
        try {
            $data = Input::all();
            $result=DB::table('api__collectorproject')->insert([
                [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "WaterRegime"=>$data["WaterRegime"],
                    "CollectorCapacity"=>$data["CollectorCapacity"],
                    "TempratureAvg"=>$data["TempratureAvg"],
                    "TransferorWater"=>$data["TransferorWater"],
                    "SpecificHeat"=>$data["SpecificHeat"],
                    "Density"=>$data["Density"],
                    "UsingConcurrentFactor"=>$data["UsingConcurrentFactor"],
                    "HydrophoreFactor"=>$data["HydrophoreFactor"],
                    "Piece"=>$data["Piece"],
                    "WaterSpeed"=>$data["WaterSpeed"],
                    "CollectorFlow"=>$data["CollectorFlow"],
                    "CollectorDiameter"=>$data["CollectorDiameter"],
                    "CollectorInch"=>$data["CollectorInch"],
                    "proje_id"=>$data["proje_id"],
                ],
            ]);
            return $result;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function collectorlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__collectorproject')->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function collectorProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__collectorproject')->where('id', $data['id'])->delete();

        return $result;
    }

    public static function solarEnergy() {

        $data = Input::all();
        General::getDefaults($data, [
            'TempIn',
            'TempOut',
            ["SpecificHeat", "SpecificHeatK"],
            ["Density", "DensityK"],
            ["CorrectionFactor", "CollectorCorrectionFactor"]
        ]);

        $collector = DB::table('api__collector')->where('brand', $data['CollectorBrand'])->where('type', $data['CollectorType'])->first();

        $data['CollectorEfficiency'] = $collector->efficiency;
        $data['CollectorSurface'] = $collector->area;


        $data['DailyWaterConsumption'] = DB::table('api__build_water_consumption')->where('build_type', $data['BuildType'])->value('daily_water');
        $data['TemperatureDiff'] = $data['TempOut'] - $data['TempIn'];

        $fe = 1 + ($data['SafetyFactor'] / 100);
        $fnk = $data['CollectorEfficiency'] / 100;
        $fkap = $data['CapacityCoverageRate'] / 100;

        $data['DailyWaterNeed'] = ($data['DailyWaterConsumption'] * $data['NumberOfPeople']) / $data['Density'];
        $data['DailyEnergyNeed'] = ($data['DailyWaterNeed'] / 3.6) * $data['SpecificHeat'] * $data['Density'] * $data['TemperatureDiff'] * $fe;
        $data['DailyEnergyNeed'] = $data['DailyEnergyNeed'] * $fkap;

        $city = DB::table('api__solar_radiation')->where('city', $data['Location'])->first();

        $maxSystemNeed = 0;
        $data['Months'] = [];

        for ($i = 1; $i <= 12; $i++) {
            $pName = "month_$i";
            if (isset($city->{$pName})) {

                $solarRadiation = $city->{$pName};

                $systemNeed = $solarRadiation * $data['CorrectionFactor'] * $data['CollectorSurface'] * $fnk;

                if ($systemNeed > $maxSystemNeed) {
                    $maxSystemNeed = $systemNeed;
                }

                $data['Months']['Month' . $i] = [
                    'SolarRadiation' => round($solarRadiation),
                    'SystemNeed' => round($systemNeed),
                    'NumberOfPanel' => round($data['DailyEnergyNeed'] / $systemNeed) + 1,
                ];
            }
        }

        $data['CollectorUsefulEnergy'] = $maxSystemNeed;
        $data['CollectorNeeded'] = round($data['DailyEnergyNeed'] / $maxSystemNeed) + 1;
        $data['TotalSurfaceArea'] = $data['CollectorNeeded'] * $data['CollectorSurface'];

        return $data;
    }

    public static function solarenergyProjectKaydetme()
    {

        try {
            $data = Input::all();
            $id=DB::table('api__solarenergyproject')->insertGetId(array(
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "Location"=>$data["Location"],
                    "BuildType"=>$data["BuildType"],
                    "NumberOfPeople"=>$data["NumberOfPeople"],
                    "SafetyFactor"=>$data["SafetyFactor"],
                    "CapacityCoverageRate"=>$data["CapacityCoverageRate"],
                    "CollectorBrand"=>$data["CollectorBrand"],
                    "CollectorType"=>$data["CollectorType"],
                    "TempIn"=>$data["TempIn"],
                    "TempOut"=>$data["TempOut"],
                    "SpecificHeat"=>$data["SpecificHeat"],
                    "Density"=>$data["Density"],
                    "CorrectionFactor"=>$data["CorrectionFactor"],
                    "CollectorEfficiency"=>$data["CollectorEfficiency"],
                    "CollectorSurface"=>$data["CollectorSurface"],
                    "DailyWaterConsumption"=>$data["DailyWaterConsumption"],
                    "TemperatureDiff"=>$data["TemperatureDiff"],
                    "DailyWaterNeed"=>$data["DailyWaterNeed"],
                    "DailyEnergyNeed"=>$data["DailyEnergyNeed"],
                    "CollectorUsefulEnergy"=>$data["CollectorUsefulEnergy"],
                    "CollectorNeeded"=>$data["CollectorNeeded"],
                    "TotalSurfaceArea"=>$data["TotalSurfaceArea"],
                    "proje_id"=>$data["proje_id"],
            ));
            
            

            $result = $result=DB::table('api__solarenergymount')->insert([
                    [
                        'solarenergy_id'=>$id,
                        "mount1_SolarRadiation"=>$data["Months"]["Month1"]["SolarRadiation"],
                        "mount1_SystemNeed"=>$data["Months"]["Month1"]["SystemNeed"],
                        "mount1_NumberOfPanel"=>$data["Months"]["Month1"]["NumberOfPanel"],
                        "mount2_SolarRadiation"=>$data["Months"]["Month2"]["SolarRadiation"],
                        "mount2_SystemNeed"=>$data["Months"]["Month2"]["SystemNeed"],
                        "mount2_NumberOfPanel"=>$data["Months"]["Month2"]["NumberOfPanel"],
                        "mount3_SolarRadiation"=>$data["Months"]["Month3"]["SolarRadiation"],
                        "mount3_SystemNeed"=>$data["Months"]["Month3"]["SystemNeed"],
                        "mount3_NumberOfPanel"=>$data["Months"]["Month3"]["NumberOfPanel"],
                        "mount4_SolarRadiation"=>$data["Months"]["Month4"]["SolarRadiation"],
                        "mount4_SystemNeed"=>$data["Months"]["Month4"]["SystemNeed"],
                        "mount4_NumberOfPanel"=>$data["Months"]["Month4"]["NumberOfPanel"],
                        "mount5_SolarRadiation"=>$data["Months"]["Month5"]["SolarRadiation"],
                        "mount5_SystemNeed"=>$data["Months"]["Month5"]["SystemNeed"],
                        "mount5_NumberOfPanel"=>$data["Months"]["Month5"]["NumberOfPanel"],
                        "mount6_SolarRadiation"=>$data["Months"]["Month6"]["SolarRadiation"],
                        "mount6_SystemNeed"=>$data["Months"]["Month6"]["SystemNeed"],
                        "mount6_NumberOfPanel"=>$data["Months"]["Month6"]["NumberOfPanel"],
                        "mount7_SolarRadiation"=>$data["Months"]["Month7"]["SolarRadiation"],
                        "mount7_SystemNeed"=>$data["Months"]["Month7"]["SystemNeed"],
                        "mount7_NumberOfPanel"=>$data["Months"]["Month7"]["NumberOfPanel"],
                        "mount8_SolarRadiation"=>$data["Months"]["Month8"]["SolarRadiation"],
                        "mount8_SystemNeed"=>$data["Months"]["Month8"]["SystemNeed"],
                        "mount8_NumberOfPanel"=>$data["Months"]["Month8"]["NumberOfPanel"],
                        "mount9_SolarRadiation"=>$data["Months"]["Month9"]["SolarRadiation"],
                        "mount9_SystemNeed"=>$data["Months"]["Month9"]["SystemNeed"],
                        "mount9_NumberOfPanel"=>$data["Months"]["Month9"]["NumberOfPanel"],
                        "mount10_SolarRadiation"=>$data["Months"]["Month10"]["SolarRadiation"],
                        "mount10_SystemNeed"=>$data["Months"]["Month10"]["SystemNeed"],
                        "mount10_NumberOfPanel"=>$data["Months"]["Month10"]["NumberOfPanel"],
                        "mount11_SolarRadiation"=>$data["Months"]["Month11"]["SolarRadiation"],
                        "mount11_SystemNeed"=>$data["Months"]["Month11"]["SystemNeed"],
                        "mount11_NumberOfPanel"=>$data["Months"]["Month11"]["NumberOfPanel"],
                        "mount12_SolarRadiation"=>$data["Months"]["Month12"]["SolarRadiation"],
                        "mount12_SystemNeed"=>$data["Months"]["Month12"]["SystemNeed"],
                        "mount12_NumberOfPanel"=>$data["Months"]["Month12"]["NumberOfPanel"],
                    ],
                ]);
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function solarenergylistele()
    {
        $data = Input::all();
        $sql = DB::table('api__solarenergyproject')
        ->join("api__solarenergymount","api__solarenergyproject.id","=",'api__solarenergymount.solarenergy_id')
        ->where('api__solarenergyproject.kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("api__solarenergyproject.id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function solarenergyProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__solarenergyproject')->where('id', $data['id'])->delete();
        $result_2 = DB::table('api__solarenergymount')->where('solarenergy_id', $data['id'])->delete();
        return $result_2;
    }



    public static function Paddlebox() {

        $data = Input::all();

       

        $data['ReductionFactor'] = DB::table('api__reduction_factor')->where('position', $data['ReductionFactorPos'])->value('r_factor');
        $data['ConcurrencyFactor'] = DB::table('api__kitchen')->where('kitchen_type', $data['KitchenType'])->where('kitchen_density', $data['KitchenDensity'])->value('ro_concurrency');

        $data['TotalVapor'] = 0;
        $data['SensibleHeatConvective'] = 0;
        $deviceData = [];
       
        foreach ($data['Devices'] as &$d) {

            $d['RatedPower'] = 0;
            $d['TotalPower'] = 0;
            $d['Vapor'] = 0;
            $d['TotalVapor'] = 0;
            $d['SensibleHeat'] = 0;
            $d['SensibleHeatConvective'] = 0;

            try {
                $device = DB::table('api__under_paddlebox_device')->where('title', $d['Name'])->first();

                if ($device) {
                    $d['RatedPower'] = $device->rated_power;
                    $d['TotalPower'] = $device->rated_power * $d['Piece'];

                    $d['Vapor'] = $device->vapor;
                    $d['TotalVapor'] = $device->vapor * $d['RatedPower'];

                    $d['SensibleHeat'] = $device->sensible_heat;
                    $d['SensibleHeatConvective'] = $device->sensible_heat * $d['RatedPower'];
                }
            } catch (\Exception $dxc) {
                
            }

            $data['TotalVapor'] += $d['TotalVapor'];
            $data['SensibleHeatConvective'] += $d['SensibleHeatConvective'];
        }

        $dhyrd = 2 * ($data['PaddleboxWidth'] * $data['PaddleboxHeight']) / ($data['PaddleboxWidth'] + $data['PaddleboxHeight']);
        $ka = pow(18, 4 / 3);
        $kb = pow($data['SensibleHeatConvective'], -1 / 3);
        $kc = pow($data['HeatSourceDistance'], -1);
        $k = $ka * $kb * $kc;

        $vth1 = $k * pow($data['SensibleHeatConvective'], 1 / 3);
        $vth2 = pow(($data['HeatSourceDistance'] + (1.7 * $dhyrd)), 5 / 3);

        $data['ThermalAirFlow'] = $vth1 * $vth2 * $data['ConcurrencyFactor'] * $data['ReductionFactor'];
        $data['AirFlow'] = $data['ThermalAirFlow'] * $data['OverflowAirFactor'];
        return $data;
    }

    public static function paddleboxProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $id=DB::table('api__paddleboxproject')->insertGetId(
                    [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "KitchenType"=>$data["KitchenType"],
                    "KitchenDensity"=>$data["KitchenDensity"],
                    "KitchenArea"=>$data["KitchenArea"],
                    "KitchenHeight"=>$data["KitchenHeight"],
                    "Piece"=>$data["Piece"],
                    "PaddleboxWidth"=>$data["PaddleboxWidth"],
                    "PaddleboxHeight"=>$data["PaddleboxHeight"],
                    "HeatSourceDistance"=>$data["HeatSourceDistance"],
                    "OverflowAirFactor"=>$data["OverflowAirFactor"],
                    "ReductionFactorPos"=>$data["ReductionFactorPos"],
                    "ReductionFactor"=>$data["ReductionFactor"],
                    "ConcurrencyFactor"=>$data["ConcurrencyFactor"],
                    "TotalVapor"=>$data["TotalVapor"],
                    "SensibleHeatConvective"=>$data["SensibleHeatConvective"],
                    "ThermalAirFlow"=>$data["ThermalAirFlow"],
                    "AirFlow"=>$data["AirFlow"],
                    ]
                );


                if(!empty($id))
                {
                    foreach ($data["Devices"] as $key => $value) {

                        $result=DB::table('api__paddleboxDevices')->insert(array(
                    
                            'Devices_name'=>$value["Name"],
                            'Devices_piece'=>$value["Piece"],
                            "Paddlebox_id"=>$id,
                            "Devices_RatedPower"=>$value["RatedPower"],
                            "Devices_TotalPower"=>$value["TotalPower"],
                            "Devices_Vapor"=>$value["Vapor"],
                            "Devices_TotalVapor"=>$value["TotalVapor"],
                            "Devices_SensibleHeat"=>$value["SensibleHeat"],
                            "Devices_SensibleHeatConvective"=>$value["SensibleHeatConvective"],
                        
                    ));
                        
                    }
                    return $result;

                }
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function paddleboxlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__paddleboxproject')
        ->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]);
            $data["Devices"]=DB::table('api__paddleboxDevices')->where('Paddlebox_id',$data["id"])->get();
        }
        $data[] = $sql->get();
        return $data;
    }
    public static function paddleboxProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__paddleboxproject')->where('id', $data['id'])->delete();
        $result=DB::table('api__paddleboxDevices')->where('Paddlebox_id',$data["id"])->delete();
        return $result;
    }

    public static function pool() {

        $data = Input::all();

        
      

        // denge tankı hesabı
        $vv = 0.075 * $data['NumberOfUser'];
        $vw = 0.06 * $data['PoolArea'];
        $vr = 1;
        $vk = 0.5;

        $data['BalanceTankArea'] = ($vv + $vw + $vr + $vk) / 1.5;
        $data['PumpFlow'] = $data['FilterCapacity'] = $data['PoolVolume'] / $data['CirculationPeriod'];

        $data['FeedingNozzle'] = ceil($data['PoolArea'] / 8);
        $data['FeedingNozzleFlow'] = $data['PumpFlow'] / $data['FeedingNozzle'];

        $data['SuctionStrainerAreaNet'] = $data['SuctionStrainerArea'] * 0.75;
        $data['FilterSuctionSpeed'] = $data['PumpFlow'] / ($data['SuctionStrainerAreaNet'] * 3600);

        $val1 = 4 / 3.14;
        $data['FeedPipe'] = pow(($val1 * $data['PumpFlow'] / $data['FeedWaterSpeed'] / 3600), 0.5) * 1000;
        $data['SuctionPipe'] = pow(($val1 * $data['PumpFlow'] / $data['SuctionWaterSpeed'] / 3600), 0.5) * 1000;
        $data['SuctionCollector'] = pow(($val1 * $data['PumpFlow'] / $data['SuctionCollectorSpeed'] / 3600), 0.5) * 1000;

        $data['FeedPipeDiameter'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['FeedPipe'])->value('nominal_d');
        $data['SuctionPipeDiameter'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['SuctionPipe'])->value('nominal_d');
        $data['SuctionCollectorDiameter'] = DB::table('api__nominal_diameter')->where('inner_d', '>=', $data['SuctionCollector'])->value('nominal_d');

        $data['TankDailyReinforcement'] = $data['ReinforcementPerPerson'] * $data['NumberOfUser'];
        $data['NumberOfLightingLamp'] = ceil(($data['PoolArea'] * $data['LightingIntensity']) / $data['LightingLampIntensity']);

        return $data;
    }

    public static function poolProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $result=DB::table('api__poolproject')->insert([
                    [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "PoolVolume"=>$data["PoolVolume"],
                    "PoolArea"=>$data["PoolArea"],
                    "NumberOfUser"=>$data["NumberOfUser"],
                    "CirculationPeriod"=>$data["CirculationPeriod"],
                    "LightingIntensity"=>$data["LightingIntensity"],
                    "SuctionStrainerArea"=>$data["SuctionStrainerArea"],
                    "FeedWaterSpeed"=>$data["FeedWaterSpeed"],
                    "SuctionWaterSpeed"=>$data["SuctionWaterSpeed"],
                    "SuctionCollectorSpeed"=>$data["SuctionCollectorSpeed"],
                    "ReinforcementPerPerson"=>$data["ReinforcementPerPerson"],
                    "LightingLampIntensity"=>$data["LightingLampIntensity"],
                    "BalanceTankArea"=>$data["BalanceTankArea"],
                    "FilterCapacity"=>$data["FilterCapacity"],
                    "PumpFlow"=>$data["PumpFlow"],
                    "FeedingNozzle"=>$data["FeedingNozzle"],
                    "FeedingNozzleFlow"=>$data["FeedingNozzleFlow"],
                    "SuctionStrainerAreaNet"=>$data["SuctionStrainerAreaNet"],
                    "FilterSuctionSpeed"=>$data["FilterSuctionSpeed"],
                    "FeedPipe"=>$data["FeedPipe"],
                    "SuctionPipe"=>$data["SuctionPipe"],
                    "SuctionCollector"=>$data["SuctionCollector"],
                    "FeedPipeDiameter"=>$data["FeedPipeDiameter"],
                    "SuctionPipeDiameter"=>$data["SuctionPipeDiameter"],
                    "SuctionCollectorDiameter"=>$data["SuctionCollectorDiameter"],
                    "TankDailyReinforcement"=>$data["TankDailyReinforcement"],
                    "NumberOfLightingLamp"=>$data["NumberOfLightingLamp"],
                    ]
                ]);
            
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function poollistele()
    {
        $data = Input::all();
        $sql = DB::table('api__poolproject')
        ->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function poolProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__poolproject')->where('id', $data['id'])->delete();
        return $result;
    }

    public static function coldStorage() {

        $data = Input::all();
        

        $product = DB::table('api__product')->where('product_type', $data['ProductType'])->first();
        $data['StorageTemperature'] = $product->enclosure_temp;
        $data['FreezingPoint'] = $product->freezing_point;
        $data['StorageTime'] = $product->storage_time;

        $data['ProcessingTime'] = DB::table('api__enclosure_type')->where('min_temp', '<=', $data['StorageTemperature'])->where('max_temp', '>', $data['StorageTemperature'])->value('time_h');

        $data['AirExchangeNumber'] = DB::table('api__human_heat')->where('temprature', '>=', $data['StorageTemperature'])->value('heat');

        $field = $data["StorageTemperature"] > 0 ? "above_zero" : "below_zero";
        $data['UnitHumanLoad'] = DB::table('api__air_exchange')->where('room_volume', '>=', $data['StorageVolume'])->value($field);


        $data['PeopleLoad'] = $data['UnitHumanLoad'] * $data['NumberOfPeople'] * $data['ShiftLength'] * 3.6;
        $data['TotalLightingLoad'] = $data['LightingLoad'] * $data['ShiftLength'] * 3.6;
        $data['ElectricalLoad'] = $data['EngineLoad'] * 3.6 * 16;
        $data['TotalOtherLoads'] = $data['OtherLoads'] * 3.6 * 24;
        $data['VentilationLoad'] = ($data['StorageVolume'] * $data['AirExchangeNumber']) * $data['AirDensity'] * ($data['OutdoorEnthalpy'] - $data['IndoorEnthalpy']);
        $data['TotalHeatGain'] = $data['HeatGain'] * 86.4;

        //donma öncesi
        $dt = $data["EntryTemp"] - $data["StorageTemperature"];
        $cp = $product->before_freezing;
        $data["BeforeFreezingLoad"] = $dt * $cp * $data["ProductQuantity"];

        //donma yükleri
        $cp = $product->freezing_heat;
        $data["FreezingLoad"] = $cp * $data["ProductQuantity"];

        //donma sonrası
        $dt = $data["FreezingPoint"] - $data["StorageTemperature"];
        $cp = $product->after_freezing;
        $data["AfterFreezingLoad"] = $dt * $cp * $data["ProductQuantity"];

        $cp = ($data["BreathingHeat"] * pow(10, -3)) * 10;
        $data["BreathingHeatLoad"] = $cp * $data["BreathingHeatExample"] * 86.4;

        $data['ProductTemperature'] = $data['BeforeFreezingLoad'] + $data['BreathingHeatLoad'];
        $data['SystemSafetyOverhead'] = ($data['PeopleLoad'] + $data['TotalLightingLoad'] + $data['ElectricalLoad'] + $data['TotalOtherLoads'] + $data['VentilationLoad'] + $data['TotalHeatGain']) * ($data['SystemSafetyHike'] / 100);
        $data['SystemLoad'] = $data['PeopleLoad'] + $data['TotalLightingLoad'] + $data['ElectricalLoad'] + $data['TotalOtherLoads'] + $data['VentilationLoad'] + $data['TotalHeatGain'] + $data['SystemSafetyOverhead'];

        if ($data['StorageTemperature'] != 0) {
            $data['SystemLoadDaily'] = ($data['SystemLoad'] / $data['StorageTemperature']) * 3.6;
        } else {
            $data['SystemLoadDaily'] = 0;
        }
        return $data;
    }

    public static function coldstorageProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $result=DB::table('api__coldstorageproject')->insert([
                    [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "ProductType"=>$data["ProductType"],
                    "EnclosureType"=>$data["EnclosureType"],
                    "ProductQuantity"=>$data["ProductQuantity"],
                    "EntryTemp"=>$data["EntryTemp"],
                    "HeatGain"=>$data["HeatGain"],
                    "LightingLoad"=>$data["LightingLoad"],
                    "EngineLoad"=>$data["EngineLoad"],
                    "OtherLoads"=>$data["OtherLoads"],
                    "NumberOfPeople"=>$data["NumberOfPeople"],
                    "StorageVolume"=>$data["StorageVolume"],
                    "SystemSafetyHike"=>$data["SystemSafetyHike"],
                    "BreathingHeat"=>$data["BreathingHeat"],
                    "BreathingHeatExample"=>$data["BreathingHeatExample"],
                    "OutdoorEnthalpy"=>$data["OutdoorEnthalpy"],
                    "IndoorEnthalpy"=>$data["IndoorEnthalpy"],
                    "AirDensity"=>$data["AirDensity"],
                    "ShiftLength"=>$data["ShiftLength"],
                    "SystemUptime"=>$data["SystemUptime"],
                    "StorageTemperature"=>$data["StorageTemperature"],
                    "FreezingPoint"=>$data["FreezingPoint"],
                    "StorageTime"=>$data["StorageTime"],
                    "ProcessingTime"=>$data["ProcessingTime"],
                    "AirExchangeNumber"=>$data["AirExchangeNumber"],
                    "UnitHumanLoad"=>$data["UnitHumanLoad"],
                    "PeopleLoad"=>$data["PeopleLoad"],
                    "TotalLightingLoad"=>$data["TotalLightingLoad"],
                    "ElectricalLoad"=>$data["ElectricalLoad"],
                    "TotalOtherLoads"=>$data["TotalOtherLoads"],
                    "VentilationLoad"=>$data["VentilationLoad"],
                    "TotalHeatGain"=>$data["TotalHeatGain"],
                    "BeforeFreezingLoad"=>$data["BeforeFreezingLoad"],
                    "FreezingLoad"=>$data["FreezingLoad"],
                    "AfterFreezingLoad"=>$data["AfterFreezingLoad"],
                    "BreathingHeatLoad"=>$data["BreathingHeatLoad"],
                    "ProductTemperature"=>$data["ProductTemperature"],
                    "SystemSafetyOverhead"=>$data["SystemSafetyOverhead"],
                    "SystemLoad"=>$data["SystemLoad"],
                    "SystemLoadDaily"=>$data["SystemLoadDaily"],
                    ]
                ]);
            
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function coldstoragelistele()
    {
        $data = Input::all();
        $sql = DB::table('api__coldstorageproject')
        ->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function coldstorageProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__coldstorageproject')->where('id', $data['id'])->delete();
        return $result;
    }

    public static function wastewater() {

        $data = Input::all();

        $data['TotalConsumptionQuantity'] = 0;
        $data['TotalConsumptionUnit'] = 0;

        foreach ($data['Equipment'] as &$d) {

            try {
                $equipment = DB::table('api__wastewater_equipment')->where('equipment', $d['Name'])->first();

                if ($equipment) {

                    $d['ConsumptionQuantity'] = $equipment->consumption_quantity;
                    $d['ConsumptionUnit'] = $equipment->consumption_unit;

                    $data['TotalConsumptionQuantity'] += $d['ConsumptionQuantity'];
                    $data['TotalConsumptionUnit'] += $d['ConsumptionUnit'];
                }
            } catch (\Exception $dxc) {
                
            }
        }

        if ($data['SystemType'] == "Yatay") {
            $data['PipeDiameter'] = DB::table('api__wastewater_diameter')
                    ->where("emissivity", $data['Emissivity'])
                    ->where("slope", $data['Slope'])
                    ->where('consumption_quantity', '>=', $data['TotalConsumptionQuantity'])
                    ->value('diameter');
        } else {
            $data['PipeDiameter'] = DB::table('api__vertical_wastewater_diameter')
                    ->where('min_consumption_unit', '>=', $data['TotalConsumptionUnit'])
                    ->where('max_consumption_unit', '<=', $data['TotalConsumptionUnit'])
                    ->value('diameter');
        }

        return $data;
    }

    public static function pipeInsulation() {

        $data = Input::all();
        

        $diameterPipe = DB::table('api__nominal_diameter')->where('nominal_d', $data['PipeDiameter'])->first();
        $pipe = DB::table('api__pipe')
                ->where('standart', $data['SheathPipeType'])
                ->where('nominal_d', $data['PipeDiameter'])
                ->first();

        $data['NominalOuterD'] = $diameterPipe->outer_d;
        $data['NominalInnerD'] = $diameterPipe->inner_d;

        $data['InsulationThickness'] = $pipe->outer_d;
        $data['InsulationInnerD'] = $pipe->inner_d;

        $data['ServicePipeLamda'] = DB::table('api__pipe_type')
                ->where('pipe_type', $data['ServicePipeType'])
                ->value('conductivity_coefficient');

        $data['SheathPipeLamda'] = DB::table('api__pipe_type')
                ->where('pipe_type', $data['SheathPipeType'])
                ->value('conductivity_coefficient');

        $data['SoilLamda'] = DB::table('api__soil_type')
                ->where('type', $data['SoilType'])
                ->value('conductivity_coefficient');

        $data['SurfaceTensionFillerHeight'] = ($data['SoilFillingHeight'] + 0.1) * 1000;


        $a1 = log($data['NominalOuterD'] / $data['NominalInnerD'], M_E);
        $a2 = 1 / (2 * 3.14 * $data['ServicePipeLamda']);
        $data['ServicePipeResistance'] = $a1 + $a2;

        $a1 = log($data['InsulationInnerD'] / $data['NominalOuterD'], M_E);
        $a2 = 1 / (2 * 3.14 * $data['MaterialLamda']);
        $data['InsulationMaterialResistance'] = $a1 + $a2;

        $a1 = log($data['InsulationThickness'] / $data['InsulationInnerD'], M_E);
        $a2 = 1 / (2 * 3.14 * $data['SheathPipeLamda']);
        $data['SheathPipeResistance'] = $a1 + $a2;

        $a1 = log((4 * $data['SurfaceTensionFillerHeight']) / $data['InsulationThickness'], M_E);
        $a2 = 1 / (2 * 3.14 * $data['SoilLamda']);
        $data['SoilResistance'] = $a1 + $a2;

        $data['CoefficientU'] = 1 / ($data['ServicePipeResistance'] + $data['InsulationMaterialResistance'] + $data['SheathPipeResistance'] + $data['SoilResistance']);
        $data['TotalHeatLoss'] = $data['CoefficientU'] * ($data['FluidAvgTemperature'] - $data['SoilTemperature']) * $data['LineLength'];

        $mpcp = ($data['WaterFlow'] / 3600) * $data['WaterMass'] * $data['SpecificHeatOfWater'] * 1000;
        $data['EndOfLineTemp'] = $data['FluidAvgTemperature'] - ($data['FluidAvgTemperature'] / $data['SoilTemperature']) * $data['LineLength'];
        $data['TempDiff'] = $data['EndOfLineTemp'] - ($data['TotalHeatLoss'] / $mpcp);

        return $data;
    }

    public static function pipeinsulationProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $result=DB::table('api__pipeinsulationproject')->insert([
                    [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "ServicePipeType"=>$data["ServicePipeType"],
                    "PipeDiameter"=>$data["PipeDiameter"],
                    "SheathPipeType"=>$data["SheathPipeType"],
                    "SoilType"=>$data["SoilType"],
                    "SoilTemperature"=>$data["SoilTemperature"],
                    "FluidAvgTemperature"=>$data["FluidAvgTemperature"],
                    "SoilFillingHeight"=>$data["SoilFillingHeight"],
                    "LineLength"=>$data["LineLength"],
                    "WaterFlow"=>$data["WaterFlow"],
                    "WaterMass"=>$data["WaterMass"],
                    "SpecificHeatOfWater"=>$data["SpecificHeatOfWater"],
                    "MaterialLamda"=>$data["MaterialLamda"],
                    "NominalOuterD"=>$data["NominalOuterD"],
                    "NominalInnerD"=>$data["NominalInnerD"],
                    "InsulationThickness"=>$data["InsulationThickness"],
                    "InsulationInnerD"=>$data["InsulationInnerD"],
                    "ServicePipeLamda"=>$data["ServicePipeLamda"],
                    "SheathPipeLamda"=>$data["SheathPipeLamda"],
                    "SoilLamda"=>$data["SoilLamda"],
                    "SurfaceTensionFillerHeight"=>$data["SurfaceTensionFillerHeight"],
                    "ServicePipeResistance"=>$data["ServicePipeResistance"],
                    "InsulationMaterialResistance"=>$data["InsulationMaterialResistance"],
                    "SheathPipeResistance"=>$data["SheathPipeResistance"],
                    "SoilResistance"=>$data["SoilResistance"],
                    "CoefficientU"=>$data["CoefficientU"],
                    "TotalHeatLoss"=>$data["TotalHeatLoss"],
                    "EndOfLineTemp"=>$data["EndOfLineTemp"],
                    "TempDiff"=>$data["TempDiff"],
                    ]
                ]);
            
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function pipeinsulationlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__pipeinsulationproject')
        ->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function pipeinsulationProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__pipeinsulationproject')->where('id', $data['id'])->delete();
        return $result;
    }

    //parkingventilation
    public static function parkingVentilation() {

        $data = Input::all();

        $parkType = DB::table('api__parking_type')->where('type', $data['ParkingType'])->first();

        $data['FreshAir'] = $parkType->min_flow;
        $data['NumberOfExchanges'] = $parkType->number_of_changes;


        if ($data["CalculationType"] == 0) {
            $data['TotalCukvertArea'] = $parkType->number_of_changes;
        } else if ($data['ParkingArea'] <= 100) {
            $data['TotalCukvertArea'] = $data['ParkingCulvertBelow100'] * $data['NumberOfVehicles'];
        } else {
            $data['TotalCukvertArea'] = $data['ParkingCulvertAbove100'] * $data['NumberOfVehicles'];
        }

        unset($data['ParkingCulvertBelow100']);
        unset($data['ParkingCulvertAbove100']);

        $data['MotionlessVo'] = 0.55 * (20 / 3600);
        $data['ActiveVo'] = 0.6 * ($data['DrivingRoadLength'] / ($data['ParkingSpeed'] / 1000));
        $data['AirFlowPerVehicle'] = (($data['ActiveVo'] + $data['MotionlessVo']) * ($data['VehicleExitFrequency'] / 100)) / (($data['WasteGasCO'] - $data['OutdoorCO']) * pow(10, -6));

        if ($data["FlowCalcMethod"] == 0) {
            $data['AirFlow'] = $data['FreshAir'] * $data['ParkingArea'];
        } else if ($data["FlowCalcMethod"] == 1) {
            $data['AirFlow'] = ($data['FreshAir'] / $data['ParkingHeight']) * $data['ParkingArea'] * $data['ParkingHeight'];
        } else if ($data["FlowCalcMethod"] == 2) {
            $data['AirFlow'] = 20 * $data['FreshAir'] * $data['NumberOfVehicles'];
        } else if ($data["FlowCalcMethod"] == 3) {
            $data['AirFlow'] = $data['AirFlowPerVehicle'] * $data['NumberOfVehicles'];
        }

        $data['MinCulvertArea'] = $parkType->number_of_changes;

        return $data;
    }

    public static function parkingventilationProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $result=DB::table('api__parkingventilationproject')->insert([
                    [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "CalculationType"=>$data["CalculationType"],
                    "ParkingType"=>$data["ParkingType"],
                    "FlowCalcMethod"=>$data["FlowCalcMethod"],
                    "ParkingArea"=>$data["ParkingArea"],
                    "ParkingHeight"=>$data["ParkingHeight"],
                    "NumberOfVehicles"=>$data["NumberOfVehicles"],
                    "DrivingRoadLength"=>$data["DrivingRoadLength"],
                    "WasteGasCO"=>$data["WasteGasCO"],
                    "OutdoorCO"=>$data["OutdoorCO"],
                    "VehicleExitFrequency"=>$data["VehicleExitFrequency"],
                    "ParkingSpeed"=>$data["ParkingSpeed"],
                    "DetectorDensity"=>$data["DetectorDensity"],
                    "FreshAir"=>$data["FreshAir"],
                    "NumberOfExchanges"=>$data["NumberOfExchanges"],
                    "TotalCukvertArea"=>$data["TotalCukvertArea"],
                    "MotionlessVo"=>$data["MotionlessVo"],
                    "ActiveVo"=>$data["ActiveVo"],
                    "AirFlowPerVehicle"=>$data["AirFlowPerVehicle"],
                    "AirFlow"=>$data["AirFlow"],
                    "MinCulvertArea"=>$data["MinCulvertArea"],
                        
                    ]
                ]);
            
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function parkingventilationlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__parkingventilationproject')
        ->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function parkingventilationProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__parkingventilationproject')->where('id', $data['id'])->delete();
        return $result;
    }

    public static function shelterVentilation() {

        $data = Input::all();
        General::getDefaults($data, ['SandFilterAirFlow', 'SandFilterHeight', 'SandFilterAirSpeed', 'SandFilterPermeabilityRate']);

        $freshAirRow = DB::table('api__shelter_fresh_air')->where('flow', $data['NeedFreshAir'])->first();

        $data['Filtration'] = $data["CalculationType"] == 0 ? $freshAirRow->{"0_danger_filter"} : $freshAirRow->{"1_danger_filter"};

        $data['NumberOfPeople'] = $data['ShelterArea'];
        $data['TotalFlow'] = $data['NeedFreshAir'] * $data['NumberOfPeople'];



        if ($data["CalculationType"] == 1) {
            $data['SandFilterPool'] = ($data['TotalFlow'] / $data['SandFilterAirFlow']) / $data['SandFilterHeight'];
            $data['SandPoolDiffuserCalc'] = ($data['TotalFlow'] / ($data['SandFilterAirSpeed'] * 3600)) / ($data['SandFilterPermeabilityRate'] / 100);
        } else {
            $data['SandFilterPool'] = 0;
            $data['SandPoolDiffuserCalc'] = 0;
        }

        if ($data["ShelterArea"] > 100) {
            $data['SmokeEvacuation'] = $data['ShelterArea'] * $data['ShelterHeight'] * 10;
        } else {
            $data['SmokeEvacuation'] = 0;
        }

        return $data;
    }

    public static function shelterventilationProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $result=DB::table('api__shelterventilationproject')->insert([
                    [
                    'kullanici_id'=>$data["kullanici_id"],
                    'adi'=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "CalculationType"=>$data["CalculationType"],
                    "NeedFreshAir"=>$data["NeedFreshAir"],
                    "ShelterArea"=>$data["ShelterArea"],
                    "ShelterHeight"=>$data["ShelterHeight"],
                    "SandFilterAirFlow"=>$data["SandFilterAirFlow"],
                    "SandFilterHeight"=>$data["SandFilterHeight"],
                    "SandFilterAirSpeed"=>$data["SandFilterAirSpeed"],
                    "SandFilterPermeabilityRate"=>$data["SandFilterPermeabilityRate"],
                    "Filtration"=>$data["Filtration"],
                    "NumberOfPeople"=>$data["NumberOfPeople"],
                    "TotalFlow"=>$data["TotalFlow"],
                    "SandFilterPool"=>$data["SandFilterPool"],
                    "SandPoolDiffuserCalc"=>$data["SandPoolDiffuserCalc"],
                    "SmokeEvacuation"=>$data["SmokeEvacuation"],
                        
                    ]
                ]);
            
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function shelterventilationlistele()
    {
        $data = Input::all();
        $sql = DB::table('api__shelterventilationproject')
        ->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function shelterventilationProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__shelterventilationproject')->where('id', $data['id'])->delete();
        return $result;
    }

    public static function getMainLine($lines) {
        $root = [];
        $names = [];

        foreach ($lines as $line) {
            if (!in_array($line['Name'], $names)) {
                $names[] = $line['Name'];
            }

            if (!in_array($line['Connection'], $root)) {
                $root[] = $line['Connection'];
            }
        }

        $root = array_values(array_diff($root, $names));

        if (count($root) == 1) {
            return $root[0];
        } else {
            return response(['success' => false, 'error_code' => "invalid_root"], Response::HTTP_NOT_ACCEPTABLE)->send();
        }
    }

    public static function getChilds(&$lines, &$parent) {
        if (!isset($parent['PartLoad'])) {
            $parent['PartLoad'] = 0;
        }

        $parent['LineLoad'] = 0;
        $parent['Childs'] = [];

        foreach ($lines as $line) {
            if ($line['Connection'] == $parent['Name']) {
                $c = self::getChilds($lines, $line);

                $parent['LineLoad'] += $line['LineLoad'];
                $parent['Childs'][] = $line;
            }
        }

        $parent['LineLoad'] += $parent['PartLoad'];

        if (isset($lines[$parent['Name']])) {
            $lines[$parent['Name']]["LineLoad"] = $parent['LineLoad'];
        }
    }

    public static function pipePressureLoss() {

        $data = Input::all();

        $data['TempDiff'] = $data['GoingTemp'] - $data['ReturnTemp'];
        $data['TempAvg'] = ($data['ReturnTemp'] + $data['GoingTemp']) / 2;
        return $data;

        $fluid = DB::table('api__fluid_type')->where('temp_avg', $data['TempAvg'])->first();

        if (!$fluid) {
            $fluid = DB::table('api__fluid_type')->where('temp_avg', '<', $data['TempAvg'])
                            ->orderBy('temp_avg', 'desc')->first();

            if ($fluid) {
                $fluidMax = DB::table('api__fluid_type')->where('temp_avg', '>', $data['TempAvg'])
                                ->orderBy('temp_avg', 'asc')->first();

                if ($fluidMax) {
                    $fluid->specific_heat = General::interpolation($data['TempAvg'], $fluid->temp_avg, $fluidMax->temp_avg, $fluid->specific_heat, $fluidMax->specific_heat);
                    $fluid->density = General::interpolation($data['TempAvg'], $fluid->temp_avg, $fluidMax->temp_avg, $fluid->density, $fluidMax->density);
                    $fluid->viscosity = General::interpolation($data['TempAvg'], $fluid->temp_avg, $fluidMax->temp_avg, $fluid->viscosity, $fluidMax->viscosity);

                    $fluid->temp_avg = $data['TempAvg'];
                }
            } else {
                return response(['success' => false, 'error_code' => "invalid_fluid"], Response::HTTP_NOT_ACCEPTABLE);
            }
        }

        $data['WaterDensity'] = $fluid->density;
        $data['SpecificHeatOfWater'] = $fluid->specific_heat;
        $data['WaterViscosity'] = $fluid->viscosity / 1000;
        $data['DynamicViscosity'] = 0.045;

        /////////////////////////////////////////////////////
//        $data['WaterDensity'] = 997.3564;
//        $data['SpecificHeatOfWater'] = 4.198;
//        $data['WaterViscosity'] = 0.000976727;


        $lines = [];

        foreach ($data['Lines'] as $line) {
            if (isset($lines[$line['Name']])) {
                return response(['success' => false, 'error_code' => "invalid_linr"], Response::HTTP_NOT_ACCEPTABLE);
            } else {
                $lines[$line['Name']] = $line;
            }
        }

        $tree = [];

        $tree['Name'] = self::getMainLine($lines);
        self::getChilds($lines, $tree);

        $lines = array_values($lines);
        $data['Lines'] = $lines;
        $data['LineLoad'] = $tree['LineLoad'];
        $data['Flow'] = ($tree['LineLoad'] / 1000) / ($data['WaterDensity'] * $data['SpecificHeatOfWater'] * $data['TempDiff']) * 3600;

        foreach ($data['Lines'] as &$line) {
            // bilinenler : Name, Connection, PartLoad, LineLoad, Length, PipeType, PipeType

            $line['Flow'] = ($line['LineLoad'] / 1000) / ($data['WaterDensity'] * $data['SpecificHeatOfWater'] * $data['TempDiff']) * 3600;
            $line['StartSpeed'] = General::interpolation($line['LineLoad'], $data['MinLoad'], $data['MaxLoad'], $data['MinSpeed'], $data['MaxSpeed']);

            if ($data['DiameterAdvice'] == 'Hız Düşümü') {
                $line["EquivalentDiameter"] = sqrt(($line['Flow'] * 4) / (3.14 * $line['StartSpeed'] * 3600)) * 1000;
            } else {
                $diameter = DB::table('api__diameter_advice')
                        ->where('type', $data['DiameterAdvice'])
                        ->where('line_load', '>=', $data['LineLoad'])
                        ->orderBy('line_load', 'asc')
                        ->first();

                $line['EquivalentDiameter'] = $diameter->diameter;
            }

            if (isset($line['PipeType'])) {
                $pipe = DB::table('api__pipe')
                        ->where('standart', $line['PipeType'])
                        ->where('inner_d', '>=', $line['EquivalentDiameter'])
                        ->orderBy('inner_d', 'asc')
                        ->first();

                if (!$pipe) {
                    return response(['success' => false, 'error_code' => "invalid_pipe"], Response::HTTP_NOT_ACCEPTABLE);
                }

                $line["PipeInch"] = $pipe->inch;
                $line["PipeInnerD"] = $pipe->inner_d;
                $line["Emstivity"] = $pipe->emstivity;
                $line["FrictionCoefficient"] = 0;

                /////////////////////////
//            $line["PipeInnerD"] = 13;

                $line['NetSpeed'] = ($line['Flow'] * 4) / (3.14 * 3600 * pow($line['PipeInnerD'] / 1000, 2));

                /////////////////////
//            $line['NetSpeed'] = 0.014195;

                $line['Resistance'] = ($data['WaterDensity'] * $line['NetSpeed'] * ($line['PipeInnerD'] / 1000)) / $data['WaterViscosity'];

//            Emstivity  ???
                $line['KinematicViscosity'] = $data['WaterViscosity'] / $data['WaterDensity'];
                $line['Reynold'] = ($data['WaterDensity'] * $line['NetSpeed'] * ($line['PipeInnerD'] / 1000)) / $data['WaterViscosity'];
                $line['CoRoughness'] = ($line['Emstivity'] * pow(10, -3)) / ($line['PipeInnerD'] / 1000);

                if ($line['Reynold'] == 0) {
                    $line['Reynold'] = 1;
                }

                if ($line['Reynold'] <= 2100) {
                    $line['FrictionCoefficient'] = 64 / $line['Reynold'];
                } else if ($line['Reynold'] <= 100000) {
                    $line['FrictionCoefficient'] = 0.3164 / pow($line['Reynold'], 0.25);
                } else if ($line['Reynold'] <= 3000000) {
                    $line['FrictionCoefficient'] = 0.0032 + (0.221 / pow($line['Reynold'], 0.237));
                }

                $line['RelativeSmoothness'] = ($line['Emstivity'] * pow(10, -3)) / ($line['PipeInnerD'] / 1000);
                $line['PressureDrop'] = $line['FrictionCoefficient'] * (1 / ($line['PipeInnerD'] / 1000) * (($data['WaterDensity'] * pow($line['NetSpeed'], 2)) / 2));
                $line['TotalPressureDrop'] = $line['PressureDrop'] * $line['Length'];
            }
        }

        return $data;
    }

    public static function pipeProjectKaydetme()
    {
        
        try {
            $data = Input::all();
            $result=DB::table('api__pipeproject')->insert([
                    [
                        "kullanici_id"=>$data["kullanici_id"],
                        'adi'=>$data["adi"],
                        "aciklama"=>$data["aciklama"],
                        "MinSpeed"=>$data["MinSpeed"],
                        "MaxSpeed"=>$data["MaxSpeed"],
                        "MinLoad"=>$data["MinLoad"],
                        "MaxLoad"=>$data["MaxLoad"],
                        "GoingTemp"=>$data["GoingTemp"],
                        "ReturnTemp"=>$data["ReturnTemp"],
                        "FluidType"=>$data["FluidType"],
                        "DiameterAdvice"=>$data["DiameterAdvice"],
                        "Lines_Name"=>$data["Lines_Name"],
                        "Lines_Connection"=>$data["Lines_Connection"],
                        "Lines_PartLoad"=>$data["Lines_PartLoad"],
                        "Lines_LineLoad"=>$data["Lines_LineLoad"],
                        "Lines_Length"=>$data["Lines_Length"],
                        "Lines_PipeType"=>$data["Lines_PipeType"],
                        "Lines_Flow"=>$data["Lines_Flow"],
                        "Lines_StartSpeed"=>$data["Lines_StartSpeed"],
                        "Lines_EquivalentDiameter"=>$data["Lines_EquivalentDiameter"],
                        "Lines_PipeInch"=>$data["Lines_PipeInch"],
                        "Lines_PipeInnerD"=>$data["Lines_PipeInnerD"],
                        "Lines_Emstivity"=>$data["Lines_Emstivity"],
                        "Lines_FrictionCoefficient"=>$data["Lines_FrictionCoefficient"],
                        "Lines_NetSpeed"=>$data["Lines_NetSpeed"],
                        "Lines_Resistance"=>$data["Lines_Resistance"],
                        "Lines_KinematicViscosity"=>$data["Lines_KinematicViscosity"],
                        "Lines_Reynold"=>$data["Lines_Reynold"],
                        "Lines_CoRoughness"=>$data["Lines_CoRoughness"],
                        "Lines_RelativeSmoothness"=>$data["Lines_RelativeSmoothness"],
                        "Lines_PressureDrop"=>$data["Lines_PressureDrop"],
                        "Lines_TotalPressureDrop"=>$data["Lines_TotalPressureDrop"],
                        "TempDiff"=>$data["TempDiff"],
                        "TempAvg"=>$data["TempAvg"],
                        "WaterDensity"=>$data["WaterDensity"],
                        "SpecificHeatOfWater"=>$data["SpecificHeatOfWater"],
                        "WaterViscosity"=>$data["WaterViscosity"],
                        "DynamicViscosity"=>$data["DynamicViscosity"],
                        "LineLoad"=>$data["LineLoad"],
                        "Flow"=>$data["Flow"],
                        
                    ]
                ]);
            
            return $result;
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function pipelistele()
    {
        $data = Input::all();
        $sql = DB::table('api__pipeproject')
        ->where('kullanici_id', $data['kullanici_id']);
        if(!empty($data["id"]))
        {
            $sql=$sql->where("id",$data["id"]); 
        }
        return $sql->get();
    }
    public static function pipeProjectDelete()
    {
        $data = Input::all();
        $result = DB::table('api__pipeproject')->where('id', $data['id'])->delete();
        return $result;
    }

    public static function heatexchangerProjectUpdate()
    {
        try {
            $data = Input::all();
            $result =DB::table('api__heatexchangerproject')
                ->where('id',$data['id'])
                ->where('kullanici_id',$data['kullanici_id'])
                ->update(array(
                    "heatexchanger_id"=>$data["heatexchanger_id"],
                    "adi"=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "HeatNeed"=>$data["HeatNeed"],
                    "TotalPass"=>$data["TotalPass"],
                    "Piece"=>$data["Piece"],
                    "HeaterFluidAvg"=>$data["HeaterFluidAvg"],
                    "HeatedFluidAvg"=>$data["HeatedFluidAvg"],
                    "PollutionFactor"=>$data["PollutionFactor"],
                    "LogHeatDiff"=>$data["LogHeatDiff"],
                    "HeatSurface"=>$data["HeatSurface"],
                    "HeatExchangerCapacity"=>$data["HeatExchangerCapacity"],
                ));
            return $result;
        } catch (\Throwable $th) {
            return 2;
        }
        
    }
    
    public static function burnerProjectUpdate()
    {
        $data = Input::all();
        $result =DB::table('api__burnerproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "LiquitFuelType"=>$data["LiquitFuelType"],
                "BoilerCapacity"=>$data["BoilerCapacity"],
                "BurnerEfficiency"=>$data["BurnerEfficiency"],
                "LowerHeatValue"=>$data["LowerHeatValue"],
                "Capacity"=>$data["Capacity"],
                "Burner_id"=>$data["Burner_id"],
                'proje_id'=>$data["proje_id"],
            ));
        return $result;
    }


    public static function fuelamountProjectUpdate()
    {
        $data = Input::all();
        $result =DB::table('api__fuelamountproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "FuelType"=>$data["FuelType"],
                "Piece"=>$data["Piece"],
                "BoilerEfficiency"=>$data["BoilerEfficiency"],
                "BoilerCapacity"=>$data[ "BoilerCapacity"],
                "LiquitFuelType"=>$data["LiquitFuelType"],
                "AvgFuelTemperature"=>$data["AvgFuelTemperature"],
                "FuelTemperature" =>$data["FuelTemperature"],
                "DailyWorkingTime"=>$data["DailyWorkingTime"],
                "YearlyWorkingTime"=>$data["YearlyWorkingTime"],
                "StoredDays"=>$data["StoredDays"],
                "LiquidOccupancyRate"=>$data["LiquidOccupancyRate"],
                "SolidStackLoad"=>$data["SolidStackLoad"],
                "LowerHeatValue" =>$data["LowerHeatValue"],
                "FuelAmount"=>$data["FuelAmount"],
                "FuelDensity"=>$data["FuelDensity"],
                "LiquidTank"=>$data["LiquidTank"],
                "SolidFuelSurface"=>$data["SolidFuelSurface"],
                "PreheaterLoad"=>$data["PreheaterLoad"],
                'proje_id'=>$data["proje_id"],
            ));
        return $result;
    }
    
   
    public static function fuelamountyearlyProjectUpdate()
    {
        try {
            $data = Input::all();
        $result =DB::table('api__fuelamountyearlyproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "FuelType" =>$data["FuelType"],
                "Piece"=>$data["Piece"],
                "BoilerEfficiency"=>$data["BoilerEfficiency"],
                "BoilerCapacity"=>$data["BoilerCapacity"],
                "BurnerEfficiency"=>$data["BurnerEfficiency"],
                "LiquitFuelType"=>$data["LiquitFuelType"],
                "AvgFuelTemperature"=>$data["AvgFuelTemperature"],
                "FuelTemperature"=>$data["FuelTemperature"],
                "YearlyHeatingEnergy"=>$data["YearlyHeatingEnergy"],
                "YearlyWorkingTime"=>$data["YearlyWorkingTime"],
                "BuildingArea"=>$data["BuildingArea"],
                "LiquidOccupancyRate"=>$data["LiquidOccupancyRate"],
                "SolidStackLoad"=>$data["SolidStackLoad"],
                "LowerHeatValue" =>$data["LowerHeatValue"],
                "FuelAmount"=>$data["FuelAmount"],
                "FuelDensity"=>$data["FuelDensity"],
                "LiquidTank"=>$data["LiquidTank"],
                "SolidFuelSurface"=>$data["SolidFuelSurface"],
                "PreheaterLoad"=>$data["PreheaterLoad"],
                "proje_id"=>$data["proje_id"],
                
            ));
        return $result;
        } catch (\Throwable $th) {
            return 2;
        }
        
    }



    public static function openexpansionProjectUpdate()
    {
        
        try {
            $data = Input::all();
            $result =DB::table('api__openexpansionproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi" =>$data["adi"],
                "aciklama" =>$data["aciklama"],
                "HeaterType" =>$data["HeaterType"],
                "WaterHeat" =>$data["WaterHeat"],
                "BoilerCapacity" =>$data["BoilerCapacity"],
                "Expansion" =>$data["Expansion"],
                "WaterExpansion" =>$data["WaterExpansion"],
                "WaterVolume" =>$data["WaterVolume"],
                "TankVolume" =>$data["TankVolume"],
                "DiameterG" =>$data["DiameterG"],
                "DiameterD" =>$data["DiameterD"],
                "DiameterH" =>$data["DiameterH"],
                "DiameterInchG" =>$data["DiameterInchG"],
                "DiameterInchD" =>$data["DiameterInchD"],
                "DiameterInchH"=>$data["DiameterInchH"],
                
            ));
        return $result;
        } catch (\Throwable $th) {
            return 2;
        }
        
    }


    public static function closeexpansionProjectUpdate()
    {
         
        try {
            
            $data = Input::all();
            $result =DB::table('api__closeexpansionproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "BoilerCapacity"=>$data["BoilerCapacity"],
                "HeaterType"=>$data["HeaterType"],
                "WaterHeat"=>$data["WaterHeat"],
                "StaticHeight"=>$data["StaticHeight"],
                "OpeningPressure"=>$data["OpeningPressure"],
                "ValveType"=>$data["ValveType"],
                "Piece"=>$data["Piece"],
                "Expansion"=>$data["Expansion"],
                "WaterExpansion"=>$data["WaterExpansion"],
                "UpperPressure"=>$data["UpperPressure"],
                "TankPrePressure"=>$data["TankPrePressure"],
                "WaterVolume"=>$data["WaterVolume"],
                "ExpandingWater"=>$data["ExpandingWater"],
                "StartPreWaterVolume"=>$data["StartPreWaterVolume"],
                "TankVolume"=>$data["TankVolume"],
                "ValveDiameter"=>$data["ValveDiameter"],
                "ValveInch"=>$data["ValveInch"],
                 
            ));
           
        return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }
   

    public static function circulationpumpProjectUpdate()
    {
         
        try {
            
            $data = Input::all();
            $result =DB::table('api__circulationpumpproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "BoilerCapacity"=>$data["BoilerCapacity"],
                "TemperatureDiff"=>$data["TemperatureDiff"],
                "Piece"=>$data["Piece"],
                "PressureDrop"=>$data["PressureDrop"],
                "PumpEfficiency"=>$data["PumpEfficiency"],
                "EngineEfficiency"=>$data["EngineEfficiency"],
                "SpecificHeat"=>$data["SpecificHeat"],
                "Density"=>$data["Density"],
                "PumpFlow"=>$data["PumpFlow"],
                "MotorPower"=>$data["MotorPower"],
                 
            ));
           
        return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }


    public static function boylerProjectUpdate()
    {
         
        try {
            
            $data = Input::all();
            $result =DB::table('api__boylerproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "BuildType"=>$data["BuildType"],
                "Equipment_name"=>$data["Equipment_name"],
                "Equipment_piece"=>$data["Equipment_piece"],
                "Equipment_waterconsumption"=>$data["Equipment_waterconsumption"],
                "PrepareTime"=>$data["PrepareTime"],
                "SpecificHeat"=>$data["SpecificHeat"],
                "Density"=>$data["Density"],
                "SelectedVolume"=>$data["SelectedVolume"],
                "TemperatureDiff"=>$data["TemperatureDiff"],
                "HeatingLoad"=>$data["HeatingLoad"],
                "BoylerTempIn"=>$data["BoylerTempIn"],
                "BoylerTempOut"=>$data["BoylerTempOut"],
                "TempIn"=>$data["TempIn"],
                "TempOut"=>$data["TempOut"],
                "StorageFactor"=>$data["StorageFactor"],
                "UserFactor"=>$data["UserFactor"],
                "WaterConsumption"=>$data["WaterConsumption"],
                "AvgWaterConsumption"=>$data["AvgWaterConsumption"],
                "BoylerCapacity"=>$data["BoylerCapacity"],
                "proje_id"=>$data["proje_id"],
                 
            ));
           
        return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }


    public static function rainwaterProjectUpdate()
    {
         
        try {
            
            $data = Input::all();
            $result =DB::table('api__rainwaterproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "Location"=>$data["Location"],
                "RainArea"=>$data["RainArea"],
                "RoofType"=>$data["RoofType"],
                "RainPipe"=>$data["RainPipe"],
                "RainAvg"=>$data["RainAvg"],
                "UnloadFactor"=>$data["UnloadFactor"],
                "SuddenNeed"=>$data["SuddenNeed"],
                "FlowSection"=>$data["FlowSection"],
                "ColumnDiameter"=>$data["ColumnDiameter"],
                 
            ));
           
        return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }


    public static function hydrophoreProjectUpdate()
    {
         
        try {
            
            $data = Input::all();
            $result =DB::table('api__hydrophoreproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "BuildType"=>$data["BuildType"],
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "NumberOfPeople"=>$data["NumberOfPeople"],
                "StoredTime"=>$data["StoredTime"],
                "Piece"=>$data["Piece"],
                "PipePressureLoss"=>$data["PipePressureLoss"],
                "OtherLosses"=>$data["OtherLosses"],
                "UsingConcurrentFactor"=>$data["UsingConcurrentFactor"],
                "HydrophoreFactor"=>$data["HydrophoreFactor"],
                "DailyWaterConsumption"=>$data["DailyWaterConsumption"],
                "SuddenNeed"=>$data["SuddenNeed"],
                "TankVolume"=>$data["TankVolume"],
                "HydrophoreFlow"=>$data["HydrophoreFlow"],
                "TotalLoss"=>$data["TotalLoss"],
                 
            ));
           
        return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }


    public static function collectorProjectUpdate()
    {
         
        try {
             
            $data = Input::all();
            $result =DB::table('api__collectorproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "WaterRegime"=>$data["WaterRegime"],
                "CollectorCapacity"=>$data["CollectorCapacity"],
                "TempratureAvg"=>$data["TempratureAvg"],
                "TransferorWater"=>$data["TransferorWater"],
                "SpecificHeat"=>$data["SpecificHeat"],
                "Density"=>$data["Density"],
                "UsingConcurrentFactor"=>$data["UsingConcurrentFactor"],
                "HydrophoreFactor"=>$data["HydrophoreFactor"],
                "Piece"=>$data["Piece"],
                "WaterSpeed"=>$data["WaterSpeed"],
                "CollectorFlow"=>$data["CollectorFlow"],
                "CollectorDiameter"=>$data["CollectorDiameter"],
                "CollectorInch"=>$data["CollectorInch"],
                 
            ));
           
        return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }



    public static function paddleboxProjectUpdate()
    {
         
        try {
            $data = Input::all();
             
            $result =DB::table('api__paddleboxproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "KitchenType"=>$data["KitchenType"],
                "KitchenDensity"=>$data["KitchenDensity"],
                "KitchenArea"=>$data["KitchenArea"],
                "KitchenHeight"=>$data["KitchenHeight"],
                "PaddleboxWidth"=>$data["PaddleboxWidth"],
                "PaddleboxHeight"=>$data["PaddleboxHeight"],
                "HeatSourceDistance"=>$data["HeatSourceDistance"],
                "OverflowAirFactor"=>$data["OverflowAirFactor"],
                "ReductionFactorPos"=>$data["ReductionFactorPos"],
                "ReductionFactor"=>$data["ReductionFactor"],
                "ConcurrencyFactor"=>$data["ConcurrencyFactor"],
                "TotalVapor"=>$data["TotalVapor"],
                "SensibleHeatConvective"=>$data["SensibleHeatConvective"],
                "ThermalAirFlow"=>$data["ThermalAirFlow"],
                "AirFlow"=>$data["AirFlow"],
                "Devices_Name"=>$data["Devices_Name"],
                "Devices_Piece"=>$data["Devices_Piece"],
                "Devices_RatedPower"=>$data["Devices_RatedPower"],
                "Devices_TotalPower"=>$data["Devices_TotalPower"],
                "Devices_Vapor"=>$data["Devices_Vapor"],
                "Devices_TotalVapor"=>$data["Devices_TotalVapor"],
                "Devices_SensibleHeat"=>$data["Devices_SensibleHeat"],
                "Devices_SensibleHeatConvective"=>$data["Devices_SensibleHeatConvective"],
                 
            ));
           
        return $result;
         
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }




    public static function poolProjectUpdate()
    {
         
        try {
            
            $data = Input::all();
            $result =DB::table('api__poolproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "PoolVolume"=>$data["PoolVolume"],
                "PoolArea"=>$data["PoolArea"],
                "NumberOfUser"=>$data["NumberOfUser"],
                "CirculationPeriod"=>$data["CirculationPeriod"],
                "LightingIntensity"=>$data["LightingIntensity"],
                "SuctionStrainerArea"=>$data["SuctionStrainerArea"],
                "FeedWaterSpeed"=>$data["FeedWaterSpeed"],
                "SuctionWaterSpeed"=>$data["SuctionWaterSpeed"],
                "SuctionCollectorSpeed"=>$data["SuctionCollectorSpeed"],
                "ReinforcementPerPerson"=>$data["ReinforcementPerPerson"],
                "LightingLampIntensity"=>$data["LightingLampIntensity"],
                "BalanceTankArea"=>$data["BalanceTankArea"],
                "FilterCapacity"=>$data["FilterCapacity"],
                "PumpFlow"=>$data["PumpFlow"],
                "FeedingNozzle"=>$data["FeedingNozzle"],
                "FeedingNozzleFlow"=>$data["FeedingNozzleFlow"],
                "SuctionStrainerAreaNet"=>$data["SuctionStrainerAreaNet"],
                "FilterSuctionSpeed"=>$data["FilterSuctionSpeed"],
                "FeedPipe"=>$data["FeedPipe"],
                "SuctionPipe"=>$data["SuctionPipe"],
                "SuctionCollector"=>$data["SuctionCollector"],
                "FeedPipeDiameter"=>$data["FeedPipeDiameter"],
                "SuctionPipeDiameter"=>$data["SuctionPipeDiameter"],
                "SuctionCollectorDiameter"=>$data["SuctionCollectorDiameter"],
                "TankDailyReinforcement"=>$data["TankDailyReinforcement"],
                "NumberOfLightingLamp"=>$data["NumberOfLightingLamp"],

            ));
           
        return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }



    public static function coldstorageProjectUpdate()
    {
         
       try {
            
            $data = Input::all();
            $result =DB::table('api__coldstorageproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "ProductType"=>$data["ProductType"],
                "EnclosureType"=>$data["EnclosureType"],
                "ProductQuantity"=>$data["ProductQuantity"],
                "EntryTemp"=>$data["EntryTemp"],
                "HeatGain"=>$data["HeatGain"],
                "LightingLoad"=>$data["LightingLoad"],
                "EngineLoad"=>$data["EngineLoad"],
                "OtherLoads"=>$data["OtherLoads"],
                "NumberOfPeople"=>$data["NumberOfPeople"],
                "StorageVolume"=>$data["StorageVolume"],
                "SystemSafetyHike"=>$data["SystemSafetyHike"],
                "BreathingHeat"=>$data["BreathingHeat"],
                "BreathingHeatExample"=>$data["BreathingHeatExample"],
                "OutdoorEnthalpy"=>$data["OutdoorEnthalpy"],
                "IndoorEnthalpy"=>$data["IndoorEnthalpy"],
                "AirDensity"=>$data["AirDensity"],
                "ShiftLength"=>$data["ShiftLength"],
                "SystemUptime"=>$data["SystemUptime"],
                "StorageTemperature"=>$data["StorageTemperature"],
                "FreezingPoint"=>$data["FreezingPoint"],
                "StorageTime"=>$data["StorageTime"],
                "ProcessingTime"=>$data["ProcessingTime"],
                "AirExchangeNumber"=>$data["AirExchangeNumber"],
                "UnitHumanLoad"=>$data["UnitHumanLoad"],
                "PeopleLoad"=>$data["PeopleLoad"],
                "TotalLightingLoad"=>$data["TotalLightingLoad"],
                "ElectricalLoad"=>$data["ElectricalLoad"],
                "TotalOtherLoads"=>$data["TotalOtherLoads"],
                "VentilationLoad"=>$data["VentilationLoad"],
                "TotalHeatGain"=>$data["TotalHeatGain"],
                "BeforeFreezingLoad"=>$data["BeforeFreezingLoad"],
                "FreezingLoad"=>$data["FreezingLoad"],
                "AfterFreezingLoad"=>$data["AfterFreezingLoad"],
                "BreathingHeatLoad"=>$data["BreathingHeatLoad"],
                "ProductTemperature"=>$data["ProductTemperature"],
                "SystemSafetyOverhead"=>$data["SystemSafetyOverhead"],
                "SystemLoad"=>$data["SystemLoad"],
                "SystemLoadDaily"=>$data["SystemLoadDaily"],

            ));
           
            return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }



    public static function pipeinsulationProjectUpdate()
    {
         
       try {
            
            $data = Input::all();
            $result =DB::table('api__pipeinsulationproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "ServicePipeType"=>$data["ServicePipeType"],
                "PipeDiameter"=>$data["PipeDiameter"],
                "SheathPipeType"=>$data["SheathPipeType"],
                "SoilType"=>$data["SoilType"],
                "SoilTemperature"=>$data["SoilTemperature"],
                "FluidAvgTemperature"=>$data["FluidAvgTemperature"],
                "SoilFillingHeight"=>$data["SoilFillingHeight"],
                "LineLength"=>$data["LineLength"],
                "WaterFlow"=>$data["WaterFlow"],
                "WaterMass"=>$data["WaterMass"],
                "SpecificHeatOfWater"=>$data["SpecificHeatOfWater"],
                "MaterialLamda"=>$data["MaterialLamda"],
                "NominalOuterD"=>$data["NominalOuterD"],
                "NominalInnerD"=>$data["NominalInnerD"],
                "InsulationThickness"=>$data["InsulationThickness"],
                "InsulationInnerD"=>$data["InsulationInnerD"],
                "ServicePipeLamda"=>$data["ServicePipeLamda"],
                "SheathPipeLamda"=>$data["SheathPipeLamda"],
                "SoilLamda"=>$data["SoilLamda"],
                "SurfaceTensionFillerHeight"=>$data["SurfaceTensionFillerHeight"],
                "ServicePipeResistance"=>$data["ServicePipeResistance"],
                "InsulationMaterialResistance"=>$data["InsulationMaterialResistance"],
                "SheathPipeResistance"=>$data["SheathPipeResistance"],
                "SoilResistance"=>$data["SoilResistance"],
                "CoefficientU"=>$data["CoefficientU"],
                "TotalHeatLoss"=>$data["TotalHeatLoss"],
                "EndOfLineTemp"=>$data["EndOfLineTemp"],
                "TempDiff"=>$data["TempDiff"],
            ));
           
            return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }





    public static function parkingventilationProjectUpdate()
    {
         
       try {
            
            $data = Input::all();
            $result =DB::table('api__parkingventilationproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                    "adi"=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "CalculationType"=>$data["CalculationType"],
                    "ParkingType"=>$data["ParkingType"],
                    "FlowCalcMethod"=>$data["FlowCalcMethod"],
                    "ParkingArea"=>$data["ParkingArea"],
                    "ParkingHeight"=>$data["ParkingHeight"],
                    "NumberOfVehicles"=>$data["NumberOfVehicles"],
                    "DrivingRoadLength"=>$data["DrivingRoadLength"],
                    "WasteGasCO"=>$data["WasteGasCO"],
                    "OutdoorCO"=>$data["OutdoorCO"],
                    "VehicleExitFrequency"=>$data["VehicleExitFrequency"],
                    "ParkingSpeed"=>$data["ParkingSpeed"],
                    "DetectorDensity"=>$data["DetectorDensity"],
                    "FreshAir"=>$data["FreshAir"],
                    "NumberOfExchanges"=>$data["NumberOfExchanges"],
                    "TotalCukvertArea"=>$data["TotalCukvertArea"],
                    "MotionlessVo"=>$data["MotionlessVo"],
                    "ActiveVo"=>$data["ActiveVo"],
                    "AirFlowPerVehicle"=>$data["AirFlowPerVehicle"],
                    "AirFlow"=>$data["AirFlow"],
                    "MinCulvertArea"=>$data["MinCulvertArea"],
            ));
           
            return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }




    public static function shelterventilationProjectUpdate()
    {
         
       try {
            
            $data = Input::all();
            $result =DB::table('api__shelterventilationproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                    "adi"=>$data["adi"],
                    "aciklama"=>$data["aciklama"],
                    "CalculationType"=>$data["CalculationType"],
                    "NeedFreshAir"=>$data["NeedFreshAir"],
                    "ShelterArea"=>$data["ShelterArea"],
                    "ShelterHeight"=>$data["ShelterHeight"],
                    "SandFilterAirFlow"=>$data["SandFilterAirFlow"],
                    "SandFilterHeight"=>$data["SandFilterHeight"],
                    "SandFilterAirSpeed"=>$data["SandFilterAirSpeed"],
                    "SandFilterPermeabilityRate"=>$data["SandFilterPermeabilityRate"],
                    "Filtration"=>$data["Filtration"],
                    "NumberOfPeople"=>$data["NumberOfPeople"],
                    "TotalFlow"=>$data["TotalFlow"],
                    "SandFilterPool"=>$data["SandFilterPool"],
                    "SandPoolDiffuserCalc"=>$data["SandPoolDiffuserCalc"],
                    "SmokeEvacuation"=>$data["SmokeEvacuation"],
                    
            ));
           
            return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }


    public static function pipePressureLossProjectUpdate()
    {
         
       try {
            
            $data = Input::all();
            $result =DB::table('api__pipeproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "MinSpeed"=>$data["MinSpeed"],
                "MaxSpeed"=>$data["MaxSpeed"],
                "MinLoad"=>$data["MinLoad"],
                "MaxLoad"=>$data["MaxLoad"],
                "GoingTemp"=>$data["GoingTemp"],
                "ReturnTemp"=>$data["ReturnTemp"],
                "FluidType"=>$data["FluidType"],
                "DiameterAdvice"=>$data["DiameterAdvice"],
                "Lines_Name"=>$data["Lines_Name"],
                "Lines_Connection"=>$data["Lines_Connection"],
                "Lines_PartLoad"=>$data["Lines_PartLoad"],
                "Lines_LineLoad"=>$data["Lines_LineLoad"],
                "Lines_Length"=>$data["Lines_Length"],
                "Lines_PipeType"=>$data["Lines_PipeType"],
                "Lines_Flow"=>$data["Lines_Flow"],
                "Lines_StartSpeed"=>$data["Lines_StartSpeed"],
                "Lines_EquivalentDiameter"=>$data["Lines_EquivalentDiameter"],
                "Lines_PipeInch"=>$data["Lines_PipeInch"],
                "Lines_PipeInnerD"=>$data["Lines_PipeInnerD"],
                "Lines_Emstivity"=>$data["Lines_Emstivity"],
                "Lines_FrictionCoefficient"=>$data["Lines_FrictionCoefficient"],
                "Lines_NetSpeed"=>$data["Lines_NetSpeed"],
                "Lines_Resistance"=>$data["Lines_Resistance"],
                "Lines_KinematicViscosity"=>$data["Lines_KinematicViscosity"],
                "Lines_Reynold"=>$data["Lines_Reynold"],
                "Lines_CoRoughness"=>$data["Lines_CoRoughness"],
                "Lines_RelativeSmoothness"=>$data["Lines_RelativeSmoothness"],
                "Lines_PressureDrop"=>$data["Lines_PressureDrop"],
                "Lines_TotalPressureDrop"=>$data["Lines_TotalPressureDrop"],
                "TempDiff"=>$data["TempDiff"],
                "TempAvg"=>$data["TempAvg"],
                "WaterDensity"=>$data["WaterDensity"],
                "SpecificHeatOfWater"=>$data["SpecificHeatOfWater"],
                "WaterViscosity"=>$data["WaterViscosity"],
                "DynamicViscosity"=>$data["DynamicViscosity"],
                "LineLoad"=>$data["LineLoad"],
                "Flow"=>$data["Flow"],
                    
            ));
           
            return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }



    public static function solarenergyProjectUpdate()
    {
         
       try {
            
            $data = Input::all();
            $result =DB::table('api__solarenergyproject')
            ->where('id',$data['id'])
            ->where('kullanici_id',$data['kullanici_id'])
            ->update(array(
                "adi"=>$data["adi"],
                "aciklama"=>$data["aciklama"],
                "Location"=>$data["Location"],
                "BuildType"=>$data["BuildType"],
                "NumberOfPeople"=>$data["NumberOfPeople"],
                "SafetyFactor"=>$data["SafetyFactor"],
                "CapacityCoverageRate"=>$data["CapacityCoverageRate"],
                "CollectorBrand"=>$data["CollectorBrand"],
                "CollectorType"=>$data["CollectorType"],
                "TempIn"=>$data["TempIn"],
                "TempOut"=>$data["TempOut"],
                "SpecificHeat"=>$data["SpecificHeat"],
                "Density"=>$data["Density"],
                "CorrectionFactor"=>$data["CorrectionFactor"],
                "CollectorEfficiency"=>$data["CollectorEfficiency"],
                "CollectorSurface"=>$data["CollectorSurface"],
                "DailyWaterConsumption"=>$data["DailyWaterConsumption"],
                "TemperatureDiff"=>$data["TemperatureDiff"],
                "DailyWaterNeed"=>$data["DailyWaterNeed"],
                "DailyEnergyNeed"=>$data["DailyEnergyNeed"],
                //"Months"=>$data["Months"],
                "CollectorUsefulEnergy"=>$data["CollectorUsefulEnergy"],
                "CollectorNeeded"=>$data["CollectorNeeded"],
                "TotalSurfaceArea"=>$data["TotalSurfaceArea"],
                    
            ));
           
            return $result;
       
        } catch (\Throwable $th) {
            
            return 2;
        }
        
    }

     
   



    public static function boilerupdate()
    {
        $data = Input::all();
        

    }

 




}
