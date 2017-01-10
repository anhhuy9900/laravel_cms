<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as RequestUrl;
use App\Http\Requests\Admin\AuthenticateRequest;
use DB;
use Validator;
use App\Helpers\AdminHelpers;

class AuthenticateController extends AdminController
{

    /**
     *  construct
     */
    public function __construct(){
        parent::__construct();
        $this->data['title'] = '';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $this->data['title'] = 'Admin Login';

        return view('admin.authenticate.login')->with($this->data);
    }

    /**
     * Submit user valid when login to admin dashboard
     */
    public function post_login(AuthenticateRequest $request)
    {

        $helper = new AdminHelpers();
        //get user info login
        $user_data = $helper->admin_check_valid_user($request['username'], $request['password']);
        //check you remember yes or no
        $remember = $request['remember'] == 'on' ? 1 : 0;
        //get session for user
        $helper->admin_authenticaion($user_data, $remember);

        return redirect('/ooadmin');
    }

    /**
     * Logout account
     * destroy session
     */
    public function logout(Request $request)
    {
        if(!empty($request->session()->get('_userad_authentication'))){
            $request->session()->forget('_userad_authentication');
            $request->session()->flush();
        }

        return redirect()->route('admin_login');
    }

}