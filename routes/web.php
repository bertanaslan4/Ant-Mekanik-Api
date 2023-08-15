<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
 
 
Route::get('/', function () {
    return view('welcome');
});
 
 Route::prefix('/api')->group(function () {

Route::post('/login', 'Api\AuthController@login');
Route::post('/register', 'Api\AuthController@register');
Route::post('/userregister', 'Api\AuthController@Userregister');
Route::post('/userregisterupdate', 'Api\AuthController@Userregisterupdate');
Route::post('/forgotpasword', 'Api\AuthController@forgotPasword');



Route::post('getcity','Api\CityController@getcity');
Route::get('getcity','Api\CityController@getcity');

Route::get('islem','Api\CityController@islem');

//Route::post('/boruhesabi','Api\CityController@boruhesabi');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
//jwt token kontrolü
    Route::group(['middleware' => 'jwt.verify'], static function( $router){
    Route::post('/logout','Api\AuthController@logout');
    Route::get('/methods', 'Api\Method@all');
    Route::get('/method/{method}', 'Api\Method@get');          
    Route::get('/data/{type}', 'Api\Data@get');
    Route::post('/data', 'Api\Data@multi');
    Route::post('/getBrand', 'Api\Data@Brand');
     
    //istatistik
    Route::get('/istatistik', 'Api\DashboardController@get');            
    
    //boiler
    Route::post('/boiler', 'Api\Boiler@get');
    Route::post('/boilerDetail','Api\Boiler@getBoilerDetail');
    Route::post('/boilerSave','Api\Boiler@boilerprojectsave');
    Route::post('/boilerprojelistele','Api\Boiler@boilerprojectlist');
    Route::post('/boilerprojesil','Api\Boiler@boilerprojectdelete');
    Route::post('/boilerupdate','Api\Boiler@boilerupdate');
    //burner
    Route::post('/burner', 'Api\Burner@get');
    Route::post('/burnerSave','Api\Burner@burnerprojectsave');
    Route::post('/burnerprojelistele','Api\Burner@burnerprojectlist');
    Route::post('/burnerDetail','Api\Burner@getBurnerDetail');
    Route::post('/burnerprojesil','Api\Burner@burnerprojectdelete');
    Route::post('/burnerupdate','Api\Burner@burnerupdate');
    //FuelAmount
    Route::post('/fuel-amount', 'Api\FuelAmount@get');
    Route::post('/fuel-amount-Save','Api\FuelAmount@fuelamountprojectsave');
    Route::post('/fuelamountprojelistele','Api\FuelAmount@fuelamountprojectlist');
    Route::post('/fuelamountprojesil','Api\FuelAmount@fuelamountprojectdelete');
    Route::post('/fuelamountupdate','Api\FuelAmount@fuelamountupdate');
    //fuelamount yearly
    Route::post('/fuel-amount-yearly', 'Api\FuelAmount@getYearly');
    Route::post('/fuelamountyearlySave','Api\FuelAmount@fuelamountyearlyprojectsave');
    Route::post('/fuelamountyearlyprojelistele','Api\FuelAmount@fuelamountyearlyprojectlist');
    Route::post('/fuelamountyearlyprojesil','Api\FuelAmount@fuelamountyearlyprojectdelete');
    Route::post('/fuelamountyearlyupdate','Api\FuelAmount@fuelamountyearlyupdate');
    //open expansion
    Route::post('/open-expansion-tank', 'Api\ExpansionTank@getOpen');
    Route::post('/openexpansionSave','Api\ExpansionTank@openexpansionSave');
    Route::post('/openexpansionprojelistele','Api\ExpansionTank@openexpansionprojectlist');
    Route::get('/header-type','Api\ExpansionTank@headerType');
    Route::get('/valve-type','Api\ExpansionTank@valveType');
    Route::get('/water-expansion-get','Api\ExpansionTank@waterExpansionGet');
    Route::post('/openexpansionprojesil','Api\ExpansionTank@openexpansionprojectdelete');
    Route::post('/openexpansionupdate','Api\ExpansionTank@openexpansionupdate');
    //close expansion
    Route::post('/closed-expansion-tank', 'Api\ExpansionTank@getClosed');
    Route::post('/closeexpansionSave','Api\ExpansionTank@closeexpansionSave');
    Route::post('/closeexpansionprojelistele','Api\ExpansionTank@closeexpansionprojectlist');
    Route::post('/closeexpansionprojesil','Api\ExpansionTank@closeexpansionprojectdelete');
    Route::post('/closeexpansionupdate','Api\ExpansionTank@closeexpansionupdate');
    //circulationpump
    Route::post('/circulation-pump', 'Api\CirculationPump@get');
    Route::post('/circulationpumpSave','Api\CirculationPump@circulationpumpSave');
    Route::post('/circulationpumpprojelistele','Api\CirculationPump@circulationpumpprojectlist');
    Route::post('/circulationpumpprojesil','Api\CirculationPump@circulationpumpprojectdelete');
    Route::post('/circulationpumpupdate','Api\CirculationPump@circulationpumpupdate');

    //boyler
    Route::post('/boyler', 'Api\Boyler@get');
    Route::post('/boylerSave','Api\Boyler@boylerSave');
    Route::post('/boylerprojelistele','Api\Boyler@boylerprojectlist');
    Route::post('/boylerprojesil','Api\Boyler@boylerprojectdelete');
    Route::post('/boylerupdate','Api\Boyler@boylerupdate');
    //rainwater
    Route::post('/rain-water', 'Api\RainWater@get');
    Route::post('/rainwaterSave','Api\RainWater@rainwaterSave');
    Route::post('/rainwaterprojelistele','Api\RainWater@rainwaterprojectlist');
    Route::post('/rainwaterprojesil','Api\RainWater@rainwaterprojectdelete');
    Route::post('/rainwaterupdate','Api\RainWater@rainwaterupdate');
    //hydrophore
    Route::post('/hydrophore', 'Api\Hydrophore@get');
    Route::post('/hydrophoreSave','Api\Hydrophore@hydrophoreSave');
    Route::post('/hydrophoreprojelistele','Api\Hydrophore@hydrophoreprojectlist');
    Route::post('/hydrophoreprojesil','Api\Hydrophore@hydrophoreprojectdelete');
    Route::post('/hydrophoreupdate','Api\Hydrophore@hydrophoreupdate');


    //collector
    Route::post('/collector', 'Api\Collector@get');
    Route::post('/collectorSave','Api\Collector@collectorSave');
    Route::post('/collectorprojelistele','Api\Collector@collectorprojectlist');
    Route::post('/collectorprojesil','Api\Collector@collectorprojectdelete');
    Route::post('/collectorupdate','Api\Collector@collectorupdate');
    //solarenergy
    Route::post('/solar-energy', 'Api\SolarEnergy@get');
    Route::post('/solarenergySave','Api\SolarEnergy@solarenergySave');
    Route::post('/solarenergyprojelistele','Api\SolarEnergy@solarenergyprojectlist');
    Route::post('/solarenergyprojesil','Api\SolarEnergy@solarenergyprojectdelete');
    Route::post('/solarenergyupdate','Api\SolarEnergy@solarenergyupdate');
    //paddlebox
    Route::post('/paddlebox', 'Api\Paddlebox@get');
    Route::post('/paddleboxSave','Api\Paddlebox@paddleboxSave');
    Route::post('/paddleboxprojelistele','Api\Paddlebox@paddleboxprojectlist');
    Route::post('/paddleboxprojesil','Api\Paddlebox@paddleboxprojectdelete');
    Route::post('/paddleboxupdate','Api\Paddlebox@paddleboxupdate');
    //pool
    Route::post('/pool', 'Api\Pool@get');
    Route::post('/poolSave','Api\Pool@poolSave');
    Route::post('/poolprojelistele','Api\Pool@poolprojectlist');
    Route::post('/poolprojesil','Api\Pool@poolprojectdelete');
    Route::post('/poolupdate','Api\Pool@poolupdate');

    //coldstorage
    Route::post('/cold-storage', 'Api\ColdStorage@get');
    Route::post('/coldstorageSave','Api\ColdStorage@coldstorageSave');
    Route::post('/coldstorageprojelistele','Api\ColdStorage@coldstorageprojectlist');
    Route::post('/coldstorageprojesil','Api\ColdStorage@coldstorageprojectdelete');
    Route::post('/coldstorageUpdate','Api\ColdStorage@coldstorageUpdate');
    //pipeinsulation
    Route::post('/pipe-insulation', 'Api\PipeInsulation@get');
    Route::post('/pipeinsulationSave','Api\PipeInsulation@pipeinsulationSave');
    Route::post('/pipeinsulationprojelistele','Api\PipeInsulation@pipeinsulationprojectlist');
    Route::post('/pipeinsulationprojesil','Api\PipeInsulation@pipeinsulationprojectdelete');
    Route::post('/pipeinsulationupdate','Api\PipeInsulation@pipeinsulationupdate');

    //parkingventilation
    Route::post('/parking-ventilation', 'Api\ParkingVentilation@get');
    Route::post('/parkingventilationSave','Api\ParkingVentilation@parkingventilationSave');
    Route::post('/parkingventilationprojelistele','Api\ParkingVentilation@parkingventilationprojectlist');
    Route::post('/parkingventilationprojesil','Api\ParkingVentilation@parkingventilationprojectdelete');
    Route::post('/parkingventilationupdate','Api\ParkingVentilation@parkingventilationupdate');

    //shelterventilation
    Route::post('/shelter-ventilation', 'Api\ShelterVentilation@get');
    Route::post('/shelterventilationSave','Api\ShelterVentilation@shelterventilationSave');
    Route::post('/shelterventilationprojelistele','Api\ShelterVentilation@shelterventilationprojectlist');
    Route::post('/shelterventilationprojesil','Api\ShelterVentilation@shelterventilationprojectdelete');
    Route::post('/shelterventilationupdate','Api\ShelterVentilation@shelterventilationupdate');

    //pipe
    Route::post('/pipe-pressure-loss', 'Api\PipePressureLoss@get');
    Route::post('/pipeSave','Api\PipePressureLoss@pipeSave');
    Route::post('/pipeprojelistele','Api\PipePressureLoss@pipeprojectlist');
    Route::post('/pipeprojesil','Api\PipePressureLoss@pipeprojectdelete');  
    Route::post('/pipeupdate','Api\PipePressureLoss@pipeupdate');

    //heatexchanger
    Route::post('/heat-exchanger', 'Api\HeatExchanger@get');
    Route::post('/heatexchangerSave','Api\HeatExchanger@heatexchangerSave');
    Route::post('/heatexchangerprojelistele','Api\HeatExchanger@heatexchangerprojectlist');
    Route::post('/heatexchangerprojesil','Api\HeatExchanger@heatexchangerprojectdelete');  
    Route::post('/heatexchangerupdate','Api\HeatExchanger@heatexchangerupdate');
    //default ayarlar
    Route::post('/default-variable','Api\General@defaultlist');
    Route::post('/default-variable-update','Api\General@defaultupdate');
    Route::post('/default-variable-update','Api\General@defaultupdate');
    Route::post('/Musterilistele', 'Api\Customer@Musterilist');
    Route::post('/MusteriEdit', 'Api\Customer@MusteriEdit');
    Route::post('/Musteriupdate', 'Api\Customer@Musteriupdate');
    Route::post('/MusteriDelete', 'Api\Customer@MusteriDelete');
    Route::post('/Mailsend', 'Api\Customer@Mailsend');
    Route::post('/projelerkayit','Api\General@projekaydet');


     

     

          
             
          
});        
          
});










