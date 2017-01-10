<?php

namespace App\Providers\Admin;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request as RequestUrl;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Helpers\AdminHelpers;


class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('admin_check_valid_user', function ($attribute, $value, $parameters, $validator) {
            $request = request();
            $helper = new AdminHelpers();
            if($helper->admin_check_valid_user($request->username, $request->password))
                return true;
            return false;
        });

        //if(RequestUrl::is('ooadmin/*')){
           // dump(111);die;
            view()->composer('admin.*', function ($view) {
                $helper = new AdminHelpers();
                $left_menu = $helper->lists_modules_left_theme(0);
                $view->with('left_menu', $left_menu);
               
            });
        //}
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
