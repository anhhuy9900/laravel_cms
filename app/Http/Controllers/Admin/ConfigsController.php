<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Request as RequestUrl;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\ConfigsRequest;
use Carbon\Carbon;
use DB;
use Input;

class ConfigsController extends AdminController
{
    protected $module_alias;

    /**
     *  create alias module url
     */
    public function __construct(){
        parent::__construct();
        $this->module_alias = RequestUrl::segment(2);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$results = ConfigsModel::latest('id')->paginate(5);

        $limit = $request->input('limit') ? $request->input('limit') : 5;
        $key = $request->input('key') ? $request->input('key') : '';

        $query = DB::table('config')->select('*');

        if($key){
            $like = "%%%" . $key ."%%%";
            $query->where('title_config', 'LIKE', $like);

        }

        $results = $query->paginate($limit);

        $results->setPath($this->module_alias);
        
        return view('backend.configs.index',compact('results','limit','key'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = 0;
        return view('backend.configs.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfigsRequest $request)
    {

        switch($request['type_config']){
            case 'text':
                $value_config = $request['text_config'];
                break;
            case 'image':
                if($request->file('file')){
                    $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
                    $fileName = 'config_'.rand(11111,99999).time().'.'.$extension;
                    $file_path = __make_folder_upload('configs');

                    $request->file('file')->move( base_path() . $file_path, $fileName );
                    $value_config = $file_path.$fileName;
                }
                break;
            case 'textarea':
                $value_config = $request['textarea_config'];
                break;
            case 'editor':
                $value_config = $request['editor_config'];
                break;
            default:
                $value_config = '';
                break;
        }

        //dd($request->all());
        $data = array(
            'title_config' => $request['title_config'],
            'type_config' => $request['type_config'],
            'key_config' => $request['key_config'],
            'value_config' => $value_config,
            'updated_date' => strtotime(Carbon::now())  ,
            'created_date' => strtotime(Carbon::now())
        );

        $id = DB::table('config')->insertGetId($data);

        if($id > 0){

            /* Save logs */
            $save_logs = array(
                'nid' => $id,
                'title' => $request['title'],
                'type' => 'create'
            );
            $this->_save_logs_data($save_logs);
            /* End Save logs */
        }

        session()->flash('flash_message', 'New item has been created successfully');
        session()->flash('flash_message_important', true);
        
        return redirect('admin/'.$this->module_alias);
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
        $result = DB::table('config')->select('*')->where('id', $id)->first();

        if(!empty($result)){
            switch($result->type_config){
                case 'text':
                    $result->text_config = $result->value_config;
                    break;
                case 'image':
                    $result->image_config = $result->value_config;
                    break;
                case 'textarea':
                    $result->textarea_config = $result->value_config;
                    break;
                case 'editor':
                    $result->editor_config = $result->value_config;
                    break;
                default:
                    //do something
                    break;
            }
        }

        return view('backend.configs.edit',compact('result','id','list_files_gallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ConfigsRequest $request, $id)
    {

        switch($request['type_config']){
            case 'text':
                $value_config = $request['text_config'];
                break;
            case 'image':
                if($request->file('file')){
                    $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
                    $fileName = 'config_'.rand(11111,99999).time().'.'.$extension;
                    $file_path = __make_folder_upload('configs');

                    $request->file('file')->move( base_path() . $file_path, $fileName );
                    $value_config = $file_path.$fileName;
                }
                break;
            case 'textarea':
                $value_config = $request['textarea_config'];
                break;
            case 'editor':
                $value_config = $request['editor_config'];
                break;
            default:
                $value_config = '';
                break;
        }

        //dd($request->all());
        $data = array(
            'title_config' => $request['title_config'],
            'type_config' => $request['type_config'],
            'key_config' => $request['key_config'],
            'value_config' => $value_config,
            'updated_date' => strtotime(Carbon::now())  ,
            'created_date' => strtotime(Carbon::now())
        );


        $result = DB::table('config')
            ->where('id', $id)
            ->update($data);

        if($id > 0){

             /* Save logs */
            $save_logs = array(
                'nid' => $id,
                'title' => $request['title_config'],
                'type' => 'update'
            );
            $this->_save_logs_data($save_logs);
            /* End Save logs */

        }

        session()->flash('flash_message', 'Updated item successfully');
        session()->flash('flash_message_important', true);

        return redirect('admin/'.$this->module_alias);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = DB::table('config')->select('*')->where('id', $id)->first();

        /* Save logs */
        $save_logs = array(
            'nid' => $id,
            'title' => (!empty($result) ? $result->title_config : ''),
            'type' => 'delete'
        );
        $this->_save_logs_data($save_logs);
        /* End Save logs */

        DB::table('config')->where('id', '=', $id)->delete();
        session()->flash('flash_message', 'This item has been deleted');
        session()->flash('flash_message_important', true);

        return redirect('admin/'.$this->module_alias);
    }

}
