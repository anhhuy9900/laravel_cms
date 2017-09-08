<?php
namespace App\Http\Controllers\API\GET;

use Illuminate\Http\Request;
use App\Http\Requests;

class ApiTestController {

	public function get_data($params_data = array()){
		
		$data = array(
			'status' => 1,
			'data' => array(),
			'errors' => array()
		);
		return $data;
	}	
}