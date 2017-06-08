<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestUrl;
use App\Models\CategoriesNewsModel;
use App\Http\Requests\Admin\CategoriesNewsRequest;
use Carbon\Carbon;
use DB;

class CategoriesNewsController extends AdminController
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


        $record = CategoriesNewsModel::_get_list_datas($limit, $offset, array('key' => $key, 'date_range' => $date_range), $arr_order);

        $this->data['results'] = $record['data'];
        $this->data['pagination'] = __pagination($record['total'], $page_offset, $limit, 3, $this->module_alias);

        //render html form filter data
        $this->data['filter_options'] = $this->filter_options($request);

        return view('admin.categories_news.list')->with($this->data);
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
        return view('admin.categories_news.create', compact('id', 'result'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriesNewsRequest $request)
    {   
        $obj = new CategoriesNewsModel;
        $obj->title = $request['title'];
        $obj->slug = toSlug($request['title']);
        $obj->status = $request['status'];
        $obj->updated_date = Carbon::now()->timestamp;
        $obj->created_date = Carbon::now()->timestamp;
        $obj->save();

        /*flash()->overlay('New role has been successfully created', 'Good Job');*/
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
        $result = CategoriesNewsModel::find($id);
        return view('admin.categories_news.edit',compact('result','id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriesNewsRequest $request, $id)
    {
        $obj = CategoriesNewsModel::find($id);
        $obj->title = $request['title'];
        $obj->slug = toSlug($request['title']);
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
}
