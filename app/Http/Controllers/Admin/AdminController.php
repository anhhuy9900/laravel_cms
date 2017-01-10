<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\AdminHelpers;
use DB;
use Input;
use Storage;
use Carbon\Carbon;
use Auth;


class AdminController extends Controller
{

    //use save module id
    public $module_id = 0;

    //use save user id
    public $user_id = 0;

    //use save user id
    public $admin_helpers;

    public $data;

    public function __construct(){

        $this->admin_helpers = new AdminHelpers;
        /*$list_module_ids_view = $heplers->admin_helper_filter_role_module('view');
        $list_menu = DB::table('system_modules')
            ->where('module_status', 1)
            ->whereIn('id', $list_module_ids_view)
            ->orderBy('module_order', 'ASC')
            ->get();
        view()->share('list_menu', $list_menu);
       

        view()->share('heplers', $heplers);

        //get module_id global
        $this->module_id = $this->_get_module_field('module_id');*/

        //set user id
        //$this->user_id = Auth::user()->id;

        $this->data = array(
            'title' => 'Admin DasnhBoard',
            //'user_admin' => $this->admincp_service->admin_UserAdminInfo(),
            //'left_menu' => $this->admincp_service->_lists_modules_left_theme(0)
        );
    }

    /**
    * This function use get field module
    */
    private function _get_module_field($field = ''){

        $module_alias = RequestUrl::segment(2);

        $get_module_current = DB::table('system_modules')
            ->select($field)
            ->where('module_alias', $module_alias)
            ->first();

        $module_id = !empty($get_module_current) ? $get_module_current->$field : 0;

        return $module_id;
    }

    public function _save_logs_activity_user($data = array())
    {
        if(!empty($data)){
            DB::table('system_logs')->insert($data);
        }
        
    }

    /**
    * This function use save file to database
    */
    public function _save_files_data($type_id, $type = 'default', $file = ''){
        if(file_exists(base_path().$file)){
            $gallery_name = $type.'_gallery';
            
            $where = array(
                'type' => $type,
                'type_id' => $type_id,
                'file' => $file
            );
            $get_file = DB::table('files_managed')->select('file')->where( $where )->first();

            if(empty($get_file)) {
                $file_path_gallery = __make_folder_upload($gallery_name);
                $file_name_gallery = __random_string(15).'_'.rand(11111,99999).time().'.jpg';
                $newfile = $file_path_gallery.$file_name_gallery;
                Storage::move($file, $newfile);

                $data = array(
                    'type_id' => $type_id,
                    'type' => $type,
                    'file' => $newfile,
                    'status' => 1,
                    'created_date' => time()
                );
                DB::table('files_managed')->insert($data);
            }
        }
    }

     /**
    * This function use delete file to database
    */
    public function _delete_files_data($type_id, $type = 'default', $file_id = 0){

        if(!empty($file_id)){
            $where = array(
                'type' => $type,
                'type_id' => $type_id,
                'id' => $file_id
            );
            $get_file = DB::table('files_managed')->select('file')->where( $where )->first();

            if(!empty($get_file)) {
                if(file_exists(base_path().$get_file->file)){
                    Storage::delete($get_file->file);
                }
                DB::table('files_managed')->where($where)->delete();
            }
        }
        
    }

    /**
    * Create list files for item
    */
    public function upload_files(){
        $files = Input::file();
        $image = '';
        foreach($files as $file) {
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $fileName = 'file_'.rand(11111,99999).time().'.'.$extension; 
            $file_path = __make_folder_upload('files');

            $file->move( base_path() . $file_path, $fileName );
            $image = $file_path.$fileName;
        }
        $data = array(
            'status' => TRUE,
            'files' => $image
        );
        
        print json_encode($data);
    }

    /**
    * This function save logs into database
    * When user manipulation in modules (ex : create, edit, delete) 
    * param arr_data ( module_id, nid, type)
    */
    public function _save_logs_data($arr_data = array()){

        $data = array(
            'uid' => $this->user_id,
            'module_id' => $this->module_id,
            'title' => $arr_data['title'],
            'nid' => $arr_data['nid'],
            'type' => $arr_data['type'],
            'created_date' => Carbon::now()
        );

        DB::table('system_logs')->insert($data);

        return true;
    }
}
