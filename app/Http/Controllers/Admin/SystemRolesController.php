<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Models\SystemRolesModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Request as RequestUrl;
use App\Http\Requests\Admin\SystemRolesRequest;
use Carbon\Carbon;
use DB;

class SystemRolesController extends AdminController
{
    protected $module_alias;
    
    /**
     *  create alias module url
     */
    public function __construct(){
        parent::__construct();
        $this->module_alias = RequestUrl::segment(2);
        $this->data['module_title'] = 'Manage System Roles';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $key = $request->input('key') ? __xss_clean_string($request->input('key')) : '';
        $arr_order = $request->input('order') ? $this->admin_helpers->admin_handle_param_order_in_url($request->input('order')) : array('field'=>'id', 'by'=>'DESC');
        $date_range = $request->input('date_range') ? $this->admin_helpers->admin_handle_param_date_range_in_url($request->input('date_range')) : array();

        $limit = $request->input('lm') ? (int)$request->input('lm') : 10;
        $page_offset = $request->input('p') ? (int)$request->input('p') : 0;
        $offset = $page_offset > 0 ? ($page_offset - 1) * $limit : $page_offset * $limit;

        $record = SystemRolesModel::_get_list_datas($limit, $offset, array('key' => $key, 'date_range' => $date_range), $arr_order);

        $this->data['results'] = $record['data'];
        $this->data['pagination'] = __pagination($record['total'], $page_offset, $limit, 3, $this->module_alias);

        //render html form filter data
        $this->data['filter_options'] = $this->filter_options($request);

        return view('admin.system_roles.list')->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = 0;
        $result = array();
        $list_modules = DB::table('system_modules')->get();
        $list_modules = DB::table('system_modules')->get();
        if(!empty($list_modules)){
            foreach ($list_modules as $key => $value) {
                $value->view = $this->check_role_system($id, $value->id, 'view');
                $value->add = $this->check_role_system($id, $value->id, 'add');
                $value->edit = $this->check_role_system($id, $value->id, 'edit');
                $value->delete = $this->check_role_system($id, $value->id, 'delete');
            }
        }
        return view('admin.system_roles.create', compact('id', 'result' ,'list_modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SystemRolesRequest $request)
    {
        $obj = new SystemRolesModel;
        $obj->role_name = $request['role_name'];
        $obj->role_type = $this->filter_role_type($request['role_type']);
        $obj->role_status = $request['role_status'];
        $obj->updated_date = Carbon::now()->timestamp;
        $obj->created_date = Carbon::now()->timestamp;
        $obj->save();

        session()->flash('flash_message', 'New role has been created successfully');
        session()->flash('flash_message_important', true);
        
        return redirect('ooadmin/'.$this->module_alias);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = SystemRolesModel::find($id);
        $list_modules = DB::table('system_modules')->get();
        if(!empty($list_modules)){
            foreach ($list_modules as $key => $value) {
                $value->view = $this->check_role_system($id, $value->id, 'view');
                $value->add = $this->check_role_system($id, $value->id, 'add');
                $value->edit = $this->check_role_system($id, $value->id, 'edit');
                $value->delete = $this->check_role_system($id, $value->id, 'delete');
            }
        }
       
        return view('admin.system_roles.edit',compact('result','id','list_modules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SystemRolesRequest $request, $id)
    {
        $obj = SystemRolesModel::find($id);
        $obj->role_name = $request['role_name'];
        $obj->role_type = $this->filter_role_type($request['role_type']);
        $obj->role_status = $request['role_status'];
        $obj->updated_date = Carbon::now()->timestamp;
        $obj->save();

        return redirect('ooadmin/'.$this->module_alias);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SystemRolesModel::destroy($id);

        session()->flash('flash_message', 'This item has been deleted');
        session()->flash('flash_message_important', true);

        return redirect('ooadmin/'.$this->module_alias);
    }


    /*
     * This function used to render form html filter for data
     */
    private function filter_options($request){
        $key = $request->input('key') ? $request->input('key') : '';
        $date_range = $request->input('date_range') ? $request->input('date_range') : '';

        $array_filters = array();

        $array_filters['key'] =  array(
            'type' => 'input',
            'title' => 'Search',
            'default_value' => $key
        );

        $array_filters['date_range'] =  array(
            'type' => 'date_picker',
            'title' => 'Date Range',
            'options' => '',
            'default_value' => $date_range
        );

        return $this->admin_helpers->admin_handle_element_form_filter($array_filters);
    }

    protected function check_role_system($role_id, $module_id, $action = ''){
        $result_role_active = SystemRolesModel::find($role_id);
        if(!empty($result_role_active)){
            if(!empty($result_role_active->role_type)){
                $role_type = unserialize($result_role_active->role_type);
                if(!empty($role_type[$module_id][$action])) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }
    }

    protected function filter_role_type($role_type){
        if(!empty($role_type)){
            foreach ($role_type as $key => $value) {
               $arr_val= array();
               if(!empty($value['view'])){
                    $arr_val['view'] = 1;
               }else{
                    $arr_val['view'] = 0;
               }

               if(!empty($value['add'])){
                    $arr_val['add'] = 1;
               }else{
                    $arr_val['add'] = 0;
               }

               if(!empty($value['edit'])){
                    $arr_val['edit'] = 1;
               }else{
                    $arr_val['edit'] = 0;
               }

               if(!empty($value['delete'])){
                    $arr_val['delete'] = 1;
               }else{
                    $arr_val['delete'] = 0;
               }
               $role_type[$key] = $arr_val;
            }
        }
        return serialize($role_type);
    }
}
