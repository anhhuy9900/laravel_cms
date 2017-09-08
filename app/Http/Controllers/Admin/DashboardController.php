<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Admin\AdminController;
use App\Helper\AdminHelper;
use DB;
use Cookie;

class DashboardController extends AdminController
{

    public function __construct(){
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dump(Cookie::get('remember_me'));
        return view('admin.dashboard.index');
    }

}
