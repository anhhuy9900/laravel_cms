<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsModel;
use App\Models\CategoriesNewsModel;
use App\Models\TagsModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Request as RequestUrl;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\NewsRequest;
use Carbon\Carbon;
use DB;
use Input;

class NewsController extends AdminController
{
    protected $module_alias;

    /**
     *  create alias module url
     */
    public function __construct(){
        parent::__construct();
        $this->module_alias = RequestUrl::segment(2);
        $this->data['module_title'] = 'Manage News Modules';
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

        $record = NewsModel::_get_list_datas($limit, $offset, array('key' => $key, 'date_range' => $date_range), $arr_order);

        if($request->input('report')){
            $this->report($record['data']);
        }

        $this->data['results'] = $record['data'];
        $this->data['pagination'] = __pagination($record['total'], $page_offset, $limit, 3, $this->module_alias);

        //render html form filter data
        $this->data['filter_options'] = $this->filter_options($request);

        return view('admin.news.list')->with($this->data);
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
        $list_categories = CategoriesNewsModel::lists('title','id');
        $list_tags = '';
        return view('admin.news.create', compact('id', 'result', 'list_categories', 'list_tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
        $image = '';
        if($request->file('file')){
            $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
            $fileName = 'news_'.rand(11111,99999).time().'.'.$extension; 
            $file_path = __make_folder_upload('news');
            
            $request->file('file')->move( base_path() . $file_path, $fileName );
            $image = $file_path.$fileName;
        }

        $obj = new NewsModel;
        $obj->category_id = $request['category_id'];
        $obj->title = $request['title'];
        $obj->slug = toSlug($request['title']);
        $obj->image = $image;
        $obj->description = $request['description'];
        $obj->content = $request['content'];
        $obj->status = $request['status'];
        $obj->updated_date = Carbon::now()->timestamp;
        $obj->created_date = Carbon::now()->timestamp;
        $obj->save();

        //insert gallery of news
        if($obj->id > 0){
            if(!empty($request['lists_thumb'])){
                $files_gallery = json_decode($request['lists_thumb']);
                foreach ($files_gallery as $key => $value) {
                    if(!empty($value->file) && file_exists(base_path().$value->file)){
                        
                        $this->_save_files_data($obj->id, 'news', $value->file);
                    }
                }
            }

            /* Save logs */
            $save_logs = array(
                'nid' => $obj->id,
                'title' => $request['title'],
                'type' => 'create'
            );
            $this->_save_logs_data($save_logs);
            /* End Save logs */
        }

        session()->flash('flash_message', 'New item has been created successfully');
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
        $result = NewsModel::find($id);
        $list_files_gallery = '';
        if(!empty($result)){
            $where = array(
                'type' => 'news',
                'type_id' => $result->id
            );

            //get results gallery images
            $result->list_files_gallery = DB::table('files_managed')->select('id','file')->where( $where )->get();
        }

        $list_categories = CategoriesNewsModel::lists('title','id');
        $tags = DB::table('tags')->select('id','tag_name')->where( array('type_id' => $result->id) )->get();
        $list_tags = json_encode($tags);
        return view('admin.news.edit',compact('result','id','list_files_gallery','list_categories','list_tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewsRequest $request, $id)
    {
        $image = '';
        if($request->file('file')){
            $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
            $fileName = 'news_'.rand(11111,99999).time().'.'.$extension; 
            $file_path = __make_folder_upload('news');

            $request->file('file')->move( base_path() . $file_path, $fileName );
            $request['image'] = $file_path.$fileName;
            $image = $request['image'];
        }

        $obj = NewsModel::find($id);;
        $obj->category_id = $request['category_id'];
        $obj->title = $request['title'];
        $obj->slug = toSlug($request['title']);
        $obj->image = $image;
        $obj->description = $request['description'];
        $obj->content = $request['content'];
        $obj->status = $request['status'];
        $obj->updated_date = Carbon::now()->timestamp;
        $obj->save();

        //insert gallery of news
        if($id > 0){

            //insert new files
            if(!empty($request['lists_thumb'])){
                $files_gallery = json_decode($request['lists_thumb']);
                foreach ($files_gallery as $key => $value) {
                    if(!empty($value->file) && file_exists(base_path().$value->file)){
                        
                        $this->_save_files_data($id, 'news', $value->file);
                    }
                }
            }

            //delete new files
            if(!empty($request['lists_del_file'])){
                $lists_del_file = json_decode($request['lists_del_file']);
                foreach ($lists_del_file as $key_del_file => $del_file) {
                     $this->_delete_files_data($id, 'news', $del_file->id);
                }
            }


             /* Save logs */
            $save_logs = array(
                'nid' => $id,
                'title' => $request['title'],
                'type' => 'update'
            );
            $this->_save_logs_data($save_logs);
            /* End Save logs */

        }

        session()->flash('flash_message', 'Updated article successfully');
        session()->flash('flash_message_important', true);

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
        NewsModel::destroy($id);

        /* Save logs */
        $save_logs = array(
            'nid' => $id,
            'title' => (!empty($result) ? $result->title : ''),
            'type' => 'delete'
        );
        $this->_save_logs_data($save_logs);
        /* End Save logs */

        session()->flash('flash_message', 'This item has been deleted');
        session()->flash('flash_message_important', true);

        return redirect('ooadmin/'.$this->module_alias);
    }


    /**
     * Report data into file excel.
     *
     */
    private function report($arrData = array())
    {
        $file_name = 'Content-List-' . date('Ymd') . '.xlsx';

        // Create excel file
        $header = array();
        $header[] = 'ID';
        $header[] = 'Title';
        $header[] = 'Description';
        $header[] = 'Content';
        $header[] = 'Status';
        $header[] = 'Created Date';

        $data['headers'] = $header;

        $rows = array();
        if(!empty($arrData)){
            foreach($arrData as $key => $value) {

                $tmp = array();
                $tmp[] = $value->title;
                $tmp[] = $value->description;
                $tmp[] = $value->content;
                $tmp[] = $value->status == 1 ? 'Active' : 'UnActive';
                $tmp[] = date('Y-m-d H:i:s');

                $rows[] = $tmp;
            }
            $data['rows'] = $rows;
            __export_to_excel($data,$file_name);
        }
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
