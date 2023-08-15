<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;

class Trigger {

    public static function pre($methodParams) {
        $request = app('request')->header('Request-Type', 'Run');

        if ($request == 'Before') {
            $params = self::params($methodParams['Before']);

            return ['Before', $params];
        } else {
            $validParams = $request == 'After' ? $methodParams['After'] : $methodParams['Before'];

            $rules = [];

            foreach ($validParams as $vKey => $vParam) {
                if (isset($vParam['Rules'])) {
                    $rules[$vKey] = $vParam['Rules'];
                }
            }

            $data = Input::all();

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return [
                    'ValidFail',
                    response(['success' => false, 'errorCode' => 'validation_error', 'validations' => $validator->messages(), 'message' => 'Geçersiz veriler'], Response::HTTP_FORBIDDEN)
                ];
            }
        }
        return [$request, []];
    }

    public static function params($methodParams) {

        $params = DB::table('api__params')
                ->whereIn('param_key', array_keys($methodParams))
                ->get();

        foreach ($methodParams as $key => $arr) {
            $methodParams[$key]['Key'] = $key;
            $methodParams[$key]['DefaultValue'] = '';

            unset($methodParams[$key]['Rules']);
        }

        foreach ($params as $param) {
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

            if (isset($methodParams[$param->param_key])) {
                $methodParams[$param->param_key]['DefaultValue'] = $value;
            }
        }

        return array_values($methodParams);
    }

}
