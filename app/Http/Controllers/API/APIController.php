<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    private $config_path = array(
        'api_test' => array (
            'controller' => 'App\Http\Controllers\API\GET\ApiTestController',
            'function' => '',
        )
    );

    private $path_method = array(
        'GET'       => 'GET',
        'POST'      => 'POST',
        'PUT'       => 'PUT',
        'PATCH'     => 'PATCH',
        'DELETE'    => 'DELETE'
    );

    /*
    * This function is major function use get data from request
    */
    public function api(Request $request){
        $data_request = json_decode($request->input('data_request'));
        $params = array(
            'action' => $data_request->action,
            'method' => $request->method(),
            'data' => $data_request->data,
        );

        return response()->json($this->detect_method_restful($params));
    }

    /*
    * This function used to detect restful from API
    */
    private function detect_method_restful($data = array()){

    	switch ($data['method']) {
    		case 'GET':
    			$response = $this->set_paths_api($data['method'], $data['action'], array());
    			break;
    		case 'POST':

    			break;
    		case 'PUT':

    			break;
    		case 'PATCH':

    			break;
    		case 'DELETE':

    			break;			
    		default:
    			$response = array();
    			break;
    	}

        return $response;
    }

    /*
    * This function use to set config for get class that you want hande in API
    */
    private function set_paths_api($method, $action, $data_value = array()){
        

        $arr_path = array(
            'controller' => $this->config_path[$action]['controller'],
            'function' => $this->config_path[$action]['function'],
            'data' => $data_value
        );

        return $this->api_handle_function_return($arr_path, $action);
    }

    /*
    * This function use to return class, controller of API you wanna call
    */
    private function api_handle_function_return($element, $action){

        $class = new $element['controller'];
    	$data = $class->get_data($element['data']);
        return $data;

    }

}
