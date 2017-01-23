<?php
use App\Helper\AdminHelper;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
    
});

Route::get('ooadmin/login', ['as' => 'admin_login', 'uses' => 'Admin\AuthenticateController@login']);
Route::post('ooadmin/post_login', ['as' => 'admin_post_login', 'uses' => 'Admin\AuthenticateController@post_login']);
//Route::match(['get', 'post'], 'ooadmin/login', ['as' => 'admin_login', 'uses' => 'Admin\AuthenticateController@login']);
Route::get('ooadmin/logout', ['as' => 'admin_logout', 'uses' => 'Admin\AuthenticateController@logout']);



Route::group(['middleware' => 'admin', 'prefix' => 'ooadmin'], function () {
    Route::resource('', 'Admin\DashboardController');

    Route::resource('system-modules', 'Admin\SystemModulesController');
    Route::resource('system-users', 'Admin\SystemUsersController');
    Route::resource('system-roles', 'Admin\SystemRolesController');
    Route::resource('configs', 'Admin\PropertyDataController');
    Route::resource('news', 'Admin\NewsController');
    Route::resource('categories-news', 'Admin\CategoriesNewsController');
    Route::resource('property-data', 'Admin\PropertyDataController');

    Route::post('backend/upload_files', 'Admin\AdminController@upload_files');
    //Route::get('system_role/{id}/destroy', 'Admin\SystemRoleController@destroy');
});

//Route::controllers([
//	'auth' => 'Auth\AuthController',
//	'password' => 'Auth\PasswordController'
//]);
