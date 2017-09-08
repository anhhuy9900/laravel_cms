<?php
namespace App\Http\Controllers\Front;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\AppHelpers;
use Mockery\CountValidator\Exception;

class FrontController extends Controller {

    function __construct()
    {

    }

    public function test(){
        try{
            $this->help('aaa');
        } catch(Exception $e){
            print 'Error :' .$e->getMessage();
        }

    }

    function help($param){
        if(!is_numeric($param)){
            throw new Exception('The parameter must is numberic');
        }

        return $param;
    }
}
