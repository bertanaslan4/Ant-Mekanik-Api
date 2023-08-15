<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;

class General {

    public static $mergeAdvice = true;

    //kapasite paylaşımı 
    public static function calcQk($qh, $piece,$yedek) {

        $qh = $qh * 1.1;
        if ($piece == 2 && $yedek==0) {

            return $qh * (2 / 3);

        } else if($piece==3 && $yedek==0) {
            
            return $qh * (1 / 3);

        }else if($piece==3 && $yedek>0)
        {
            return $qh * 0.5;     
        }else{
            return $qh / $piece;   
        }
    }

    public static function validation($method) {

        $method = self::getMethod($method, true);
        $rules = [];

        if (isset($method['data']['Params']['Input'])) {
            foreach ($method['data']['Params']['Input'] as $vKey => $vParam) {
                if (isset($vParam['Rules'])) {
                    $rules[$vKey] = $vParam['Rules'];
                }
            }
        }

        $data = Input::all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            response(['success' => false, 'errorCode' => 'validation_error', 'validations' => $validator->messages(), 'message' => 'Geçersiz veriler'], Response::HTTP_FORBIDDEN)->send();
            die();
        }
    }

    public static function getProducer($code) {

        $res = DB::table('api__producer')->where('code', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;
    }

    public static function getSeller($code) {

        $res = DB::table('api__seller')->where('code', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;
    }
    public static function getSellerBoiler($code,$type) {

        $res = DB::table('api__seller')->where('code', $code)->where('p_type',$type);
        // $arr = [];

        // if ($res) {
        //     foreach ($res as $key => $value) {
        //         $key = ucfirst(\Illuminate\Support\Str::camel($key));
        //         $arr[$key] = $value;
        //     }
        // }

        $response= $res->get()->toArray();
       
      
        return $response;
    }
    public static function getCloseExpansionProducer($code)
    {

        $res = DB::table('api__closeexpansionproducer')->where('U_kodu', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;

    }
    public static function getCloseExpansionSeller($code)
    {

        $res = DB::table('api__closeexpansionseller')->where('U_kodu', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;
        
    }

    public static function getBoylerProducer($code)
    {

        $res = DB::table('api__boylerproducer')->where('U_Kodu', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;

    }
    public static function getBoylerSeller($code)
    {

        $res = DB::table('api__boylerseller')->where('U_Kodu', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;
        
    }
    public static function getHydrophoreProducer($code)
    {

        $res = DB::table('api__hydrophoreproducer')->where('U_kodu', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;

    }
    public static function getHydrophoreSeller($code)
    {

        $res = DB::table('api__hydrophoreseller')->where('U_Kodu', $code)->first();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;
        
    }

    public static function getParams($params, $methodParams = false, $method = 'all') {


        $params = DB::table('api__params')
                ->where('param_key', $params)
                ->where('param_method', 'all')
                ->get();

        $data = [];
        foreach ($params as $param) {
            $value = self::getValue($param);

            $data[] = [
                'Key' => $data->param_key,
                'DefaultValue' => $value
            ];
        }

        $res = DB::table('api__params')->where('param_key', $params)->get();
        $arr = [];

        if ($res) {
            foreach ($res as $key => $value) {
                $key = ucfirst(\Illuminate\Support\Str::camel($key));
                $arr[$key] = $value;
            }
        }

        return $arr;
    }

    public static function getValue($param) {
        switch ($param->type) {
            case "string":
                $value = (string) $param->default_value;
                break;
            case "int":
                $value = (int) $param->default_value;
                break;
            case "float":
                $value = (float) $param->default_value;
                break;
            default:
                $value = (string) $param->default_value;
        }

        return $value;
    }

    public static function getDefault($p) {
        $param = DB::table('api__params')->where('param_key', $p)->first();
        return self::getValue($param);
    }

    public static function getDefaults(&$data, $params) {

        foreach ($params as $p) {

            if (is_array($p)) {
                $pDb = $p[1];
                $p = $p[0];
            } else {
                $pDb = $p;
            }

            if (!isset($data[$p])) {
                $param = DB::table('api__params')->where('param_key', $pDb)->first();
                $data[$p] = self::getValue($param);
            }
        }
    }

    public static function getData($type, $cond = [], $columns = '') {
        $json = File::get(storage_path('data/data.json'));

        try {
            $full = json_decode($json, true);
        } catch (\Exception $ex) {
            $full = [];
        }

        if (isset($full[$type])) {
            $data = $full[$type];

            $cols = array_flip($data['columns']);

            $condition = [];
            if (count($cond) > 0) {
                if (is_string($cond[0])) {
                    $cond = [$cond];
                }

                foreach ($cond as $c) {
                    if (!isset($c[0])) {
                        continue;
                    }

                    if (!isset($c[1])) {
                        continue;
                    }

                    $key = $c[0];
                    $value = $c[1];
                    $opr = isset($c[2]) ? $c[2] : '=';

                    if (isset($cols[$key])) {
                        $condition[] = [$cols[$key], $opr, $value];
                    }
                }
            }

            $columns = $columns == '' ? '*' : $columns;

            $res = DB::table($data['table'])->select(DB::raw($columns))->where($condition)->get();
            $dList = [];

            foreach ($res as $r) {
                $d = [];
                foreach ($data['columns'] as $key => $value) {
                    if (isset($r->{$key})) {
                        $d[$value] = $r->{$key};
                    }
                }
                $dList[] = $d;
            }

            return ['success' => true, 'data' => $dList];
        } else {
            return ['success' => false, 'errorCode' => 'invalid_type', 'message' => 'Geçersiz veri tipi.'];
        }
    }

    public static function getMethod($type, $fetchSpe = false) {
        $json = File::get(storage_path('data/method/' . $type . '.json'));

        try {
            $f = json_decode($json, true);
        } catch (\Exception $ex) {
            $f = false;
        }

        if ($f !== false) {
            $data = [];

            $data['Title'] = (string) $f['Title'];
            $data['Description'] = (string) $f['Description'];
            $params = ['Input' => [], 'Output' => []];

            foreach ($f['Params'] as $key => $value) {
                $t = $value['Type'];
                $params[$t][$key] = $value;
                unset($params[$t][$key]['Type']);

                if (!$fetchSpe) {
                    unset($params[$t][$key]['Rules']);
                }

                if (isset($value['Options'])) {
                    if ($value['Options']['Type'] == 'Static') {
                        $params[$t][$key]['Options'] = $params[$t][$key]['Options']['Value'];
                    } else {
                        $cols = isset($value['Options']['Columns']) ? $value['Options']['Columns'] : '';
                        $res = self::getData($value['Options']['Value'], [], $cols);
                        unset($params[$t][$key]['Options']);

                        if ($res['success']) {
                            $c = explode(',', $cols);

                            if ($cols == '' || count($c) > 1) {
                                $params[$t][$key]['Options'] = $res['data'];
                            } else {
                                $dArray = [];

                                foreach ($res['data'] as $d) {
                                    $v = trim(array_values($d)[0]);
                                    if ($v != '') {
                                        $dArray[] = $v;
                                    }
                                }

                                $params[$t][$key]['Options'] = $dArray;
                            }
                        }
                    }
                }

                if (isset($value['Default'])) {
                    unset($params[$t][$key]['Default']);

                    if ($value['Default']['Type'] == 'static') {
                        $params[$t][$key]['Default'] = $value['Default']['Value'];
                    } else {
                        $params[$t][$key]['Default'] = self::getDefault($value['Default']['Value']);
                    }
                }
            }

            $data['Params'] = $params;

            return ['success' => true, 'data' => $data];
        } else {
            return ['success' => false, 'errorCode' => 'invalid_type', 'message' => 'Geçersiz veri tipi.'];
        }
    }

    public static function interpolation($x, $x0, $x1, $y0, $y1) {
        return $y0 + (($x - $x0) * (($y1 - $y0) / ($x1 - $x0)));
    }

}
