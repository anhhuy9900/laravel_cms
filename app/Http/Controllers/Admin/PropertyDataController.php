<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestUrl;
use App\Models\PropertyDataModel;
use App\Http\Requests\Admin\PropertyDataRequest;
use Carbon\Carbon;
use DB;
use Form;

class PropertyDataController extends AdminController
{

    protected $module_alias;

    /**
     *  create alias module url
     */
    public function __construct(){
        parent::__construct();
        $this->module_alias = RequestUrl::segment(2);
        $this->data['module_title'] = 'Manage Categories News Modules';
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

        $record = PropertyDataModel::_get_list_datas($limit, $offset, array('key' => $key, 'date_range' => $date_range), $arr_order);

        $this->data['results'] = $record['data'];
        $this->data['pagination'] = __pagination($record['total'], $page_offset, $limit, 3, $this->module_alias);

        //render html form filter data
        $this->data['filter_options'] = $this->filter_options($request);

        return view('admin.property_data.list')->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $this->data['id'] = 0;
        $this->data['result'] = array();
        $this->data['form_element_type'] = $this->render_element_property_type($request);
        return view('admin.property_data.create')->with($this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyDataRequest $request)
    {
        $obj = new PropertyDataModel;
        $obj->key = toSlug($request['key']);
        $obj->value = $request['value'];
        $obj->type = $request['type'];
        $obj->status = $request['status'];
        $obj->updated_date = Carbon::now()->timestamp;
        $obj->created_date = Carbon::now()->timestamp;
        $obj->save();

        session()->flash('flash_message', 'New module has been created successfully');
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
        //show
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $result = PropertyDataModel::find($id);
        $this->data['id'] = $id;
        $this->data['result'] = $result;
        $this->data['form_element_type'] = $this->render_element_property_type($request, $result->value);
        return view('admin.property_data.edit')->with($this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyDataRequest $request, $id)
    {
        $obj = PropertyDataModel::find($id);
        $obj->key = toSlug($request['key']);
        $obj->value = $request['value'];
        $obj->type = $request['type'];
        $obj->status = $request['status'];
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
        CategoriesNewsModel::destroy($id);
        
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


    /*
     * This function used to render element form for property value
     */
    private function render_element_property_type($request, $value = ''){
        switch($request->input('type')){
            case 'textfield':
                $form_element = Form::text('value', $value, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'value', 'placeholder'=> 'Value']);
                break;
            case 'textarea':
                $form_element = Form::textarea('value', $value, ['size' => '200x5', 'class'  => 'col-xs-10 col-sm-5', 'data' => 'description', 'placeholder'=> 'Value']);
                break;
            case 'editor':
                $form_element = Form::textarea('value', $value, ['size' => '5x5',' class'  => 'col-xs-10 col-sm-5 ckeditor', 'data' => 'content']);
                break;
            default:
                $form_element = Form::text('value', $value, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'value', 'placeholder'=> 'Value']);
                break;
        }
        
        return $form_element;
    }

}
