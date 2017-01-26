<?php
namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Hash;
use Session;
use Cookie;
use Illuminate\Http\Response;


class AdminHelpers
{

    function encode_password($password)
    {
        return Hash::make($password);
    }

    function verifying_password($password)
    {
        return Hash::check($password);
    }

    function admin_check_valid_user($username, $password)
    {

        $result = DB::table('system_users')
            ->select('*')
            ->where('username', $username)
            //->where('password', $password)
            ->where('status', 1)
            ->first();
        if(!empty($result)){
            if(Hash::check($password, $result->password)){
                return $result;
            }
        }

        return FALSE;
    }

    function admin_get_user_by_token($user_token)
    {
        $result = DB::table('system_users')
            ->select('id', 'username', 'email', 'permission_limit')
            ->where('user_token', $user_token)
            ->where('status', 1)
            ->first();

        if(!empty($result)){
            return $result;
        }

        return FALSE;
    }

    function admin_get_current_user_login(){
        if(!empty($this->admin_user_admin_info())) {
            $session_user = $this->admin_user_admin_info();

            //get current user
            $get_user = $this->admin_get_user_by_token($session_user['token']);
            return $get_user;
        }
        return NULL;
    }

    public static function admin_get_value_current_user($field = 'id'){
        $helper = new AdminHelpers();
        $current_user = $helper->admin_get_current_user_login();
        return $current_user->$field;
    }


    function admin_authenticaion($user_data, $remember){
        $token = str_random(40);
        $user = array(
            'username' => $user_data->username,
            'token' => $user_data->user_token,
            'ad_token' => $token,
        );

        session()->put('_userad_token', $token);
        session()->put('_userad_authentication', $user);
        session()->save();

        //set cookie for remmeber me
        if($remember){
            $response = new Response();
            $response->withCookie(cookie('remember_me', 1, time()+86400));
            return $response;
            //Cookie::make('remember_me', 1, time()+86400);
            //return response()->header('Content-Type', 1)->cookie('remember_me', 1, time()+86400);
            //return response()->view('admin')->withCookie(cookie('remember_me', 1, time()+86400));
        }

        return TRUE;
    }

    function admin_check_user_session_login(){
        $valid = FALSE;
        if (Cookie::has('remember_me') && Cookie::get('remember_me'))
        {
            $valid = TRUE;

        } else {
            if(!$this->admin_user_admin_info()){
                $valid = TRUE;
            }
        }
        return $valid;
    }

    function admin_check_valid_login(){
        if(!$this->admin_check_user_session_login()){
            /*$url = route('admin_login');
            redirect($url);
            exit();*/
            return TRUE;
        }
        return FALSE;

    }

    function admin_user_admin_info(){
        $user = session()->get('_userad_authentication');
        return $user ? $user : NULL;
    }

    public function lists_modules_left_theme($parent_id){
        //get current url
        $route = request()->path();
        $current_user = $this->admin_get_current_user_login();

        $query = DB::table('system_modules');
        $query->select('id', 'module_name', 'module_alias');
        $query->where('module_status', 1);
        $query->where('parent_id', $parent_id);
        /*if(!empty($current_user) && $current_user->permission_limit == 0){
            $query->where('module_permission = 0');
        }*/
        $query->orderBy("module_order", 'ASC');
        $results = $query->get();

        $html = '';
        if(!empty($results)){
            $html .= '<ul class="submenu">';
            foreach($results as $value){
                $html_menu = $this->lists_modules_left_theme($value->id);
                $url_redirect = $value->module_alias ? url('ooadmin/'.$value->module_alias) : '#';
                $class_active = $route == $value->module_alias ? ' class="active leftmenu-child-active"' : '';
                $html .='<li'.$class_active.'>';
                $html .='<a href="' .$url_redirect. '"' .($html_menu ? 'class="dropdown-toggle"' : ''). '>';
                $html .='<i class="menu-icon fa fa-caret-right"></i>';
                $html .= $value->module_name;
                $html .='</a>';
                $html .='<b class="arrow ' .($html_menu ? 'fa fa-angle-down' : ''). '"></b>';

                $html .= $html_menu;

                $html .='</li>';

            }
            $html .= '</ul>';
        }

        return $html;
    }

    public function admin_check_roles_user($module_id, $role_type){
        $valid = FALSE;
        $get_user = $this->admin_get_current_user_login();
        if($get_user){
            $repository = $this->em->getRepository('BooBundle:SystemUsersEntity');
            $query = $repository->createQueryBuilder('pk');
            $query->select("fk.role_type");
            $query->leftJoin("BooBundle:SystemRolesEntity", "fk", "WITH", "pk.role_id=fk.id");
            $query->where('pk.id = :id')->setParameter('id', $get_user->id);
            $query->andwhere('pk.status = 1');
            $result = $query->getQuery()->getArrayResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);

            $result_role_type = unserialize($result[0]['role_type']);
            if(!empty($result_role_type[$module_id])){
                switch($role_type){
                    case 'view':
                        if($result_role_type[$module_id][$role_type]){
                            $valid = TRUE;
                        }
                        break;
                    case 'add':
                        if($result_role_type[$module_id][$role_type]){
                            $valid = TRUE;
                        }
                        break;
                    case 'edit':
                        if($result_role_type[$module_id][$role_type]){
                            $valid = TRUE;
                        }
                        break;
                    case 'delete':
                        if($result_role_type[$module_id][$role_type]){
                            $valid = TRUE;
                        }
                        break;
                    default:
                        break;
                }
            }

            if($get_user->permission_limit == 1){
                $valid = TRUE;
            }
        }

        return $valid;

    }

    public function admin_get_current_module($module_alias, $field = ''){
        return '';
    }

    public function admin_helper_filter_role_module($action = 'view'){
		if (Auth::check()) {

            $role_user_id = Auth::user()->role_id;
            $result_role = $this->admin_get_data_users_role($role_user_id);
            
            $arr_module_id = array();
            if(!empty($result_role)){

                //full control for super admin
                if($this->admin_check_permission_access($result_role->access)){
                    
                    return $this->admin_check_permission_access($result_role->access);
                }

                $role_type = unserialize($result_role->role_type);
                if(!empty($role_type)){
	                foreach ($role_type as $key => $role) {
	                	if($role[$action]){
	                		$arr_module_id[] = $key;
	                	}
	                }
            	}
            }

            return $arr_module_id;
        }

        return array();
	}

	/**
    *check permission of user
     */
    public function admin_check_permission_user($module_id, $action = 'view')
    {
        if (Auth::check()) {
            $role_user_id = Auth::user()->role_id;
            $result_role = $this->admin_get_data_users_role($role_user_id);
            $arr_module_id = array();
            if(!empty($result_role)){

                //full control for super admin
                if($this->admin_check_permission_access($result_role->access)){
                    return TRUE;
                }

                $role_type = unserialize($result_role->role_type);
                if(!empty($role_type)){
                    if($role_type[$module_id][$action]){
                        return TRUE;
                    }
                }
            }

            return FALSE;
        }

        return FALSE;
    }

    /**
    * get data role of user
    */
    //admin_get_data_users_role
    function admin_get_data_users_role($role_user_id){
        
        $result = DB::table('system_role')
                ->select('role_type','access')
                ->where('role_id', $role_user_id)
                ->first();

        return $result;
    }

    /**
     * check permission hanlder of user
     */
    public function admin_check_permission_access($access = 0){
        if (Auth::check()) {
            $arr_menu = array();
            if($access == 1){
                $list_menu = DB::table('system_modules')
                ->where('module_status', 1)
                ->get();
                if(!empty($list_menu)){
                    foreach ($list_menu as $key => $value) {
                        $arr_menu[] = $value->module_id;
                    }
                }
                return (!empty($arr_menu) ? $arr_menu : FALSE);
            }
        }

        return FALSE;
    }

    /**
     * get values type for module configs
     */
    public function admin_helpers_get_type_module_configs(){
        $array_types = array(
            'text' => 'Text',
            'textarea' => 'Textarea',
            'editor' => 'Editor',
            'image' => 'Images'
        );

        return $array_types;
    }

    /**
     * return html filter for module
     */
    public static function admin_handle_element_form_filter($array_filters = array()){
        $html = '';
        if(!empty($array_filters)){
            //$html .= '<form name="filter_options" id="filter_options">';
            foreach($array_filters as $key => $value){
                $html .= '<div class="row">';
                    $html .= '<div class="col-xs-6 hr4">';
                    $html .= '<label for="' .$key. '" class="col-xs-3">' .$value['title']. ' : </label>';
                    switch($value['type']){
                        case 'input':
                            $html .= '<div class="input-group dataTables_filter col-xs-8">';
                            $html .= '<input type="input" id="' .$key. '" name="' .$key. '" value="' .$value['default_value']. '" class="form-control input-sm col-xs-5" placeholder="" aria-controls="dynamic-table">';
                            $html .= '</div>';
                            break;
                        case 'select':
                            $html .= '<div class="input-group col-xs-8">';
                            $html .= '<select name="' .$key. '" class="col-xs-8">';
                            foreach($value['options'] as $option_key => $option_value){
                                $selected = $value['default_value'] == $option_key ? 'selectetd="selected"' : '';
                                $html .= '<option value="' .$option_key. '" '.$selected.'>' .$option_value. '</option>';
                            }
                            $html .= '</select>';
                            $html .= '</div>';
                            break;
                        case 'date_picker':
                            $html .= '<div class="input-group col-xs-8">';
                            $html .= '<span class="input-group-addon">';
                            $html .= '<i class="fa fa-calendar bigger-110"></i>';
                            $html .= '</span>';
                            $html .= '<input class="form-control date-picker" type="text" name="' .$key. '" value="' .$value['default_value']. '" id="' .$key. '" data-type="date-picker">';
                            $html .= '</div>';
                            break;
                    }
                    $html .= '</div>';
                $html .= '</div>';
            }
            //$html .= '</form>';
        }

        return  $html;
    }

    public function admin_handle_param_order_in_url($value) {
        $arr_order = array();
        $explode = explode('|', $value);
        if(!empty($explode)){
            $arr_order = array(
                'field' => $explode[0],
                'by' => $explode[1]
            );
        }

        return $arr_order;
    }

    public function admin_handle_param_date_range_in_url($date_range){
        $arr_date_range = array();
        $explode_date = explode('-', $date_range);
        if(!empty($explode_date)){
            $arr_date_range = array(
                'from' => strtotime(date('d-m-Y 00:00:00',strtotime(trim($explode_date[0])))),
                'to' => strtotime(date('d-m-Y 00:00:00',strtotime(trim($explode_date[1])))),
            );
        }

        return $arr_date_range;
    }


    public function admin_get_link_order_filter($url, $field_type = 'id|DESC')
    {

        $filter_order = $field_type;
        $url =  urldecode($url);
        if (strpos($url, $field_type)) {

            if (strpos($url, 'order=')) {
                $replace = strpos($url, '?order=') ? '?order=' . $field_type : '&order=' . $field_type;
                $url = str_replace($replace, '', $url);
            }
            $ex_field = explode('|', $field_type);
            if (!empty($filter_order)) {
                if ($ex_field[1] == 'DESC') {
                    $filter_order = $ex_field[0] . '|' . 'ASC';
                } else {
                    $filter_order = $ex_field[0] . '|' . 'DESC';
                }
            }

        }
        $filter_url = strpos($url, '?') ? $url . '&order=' : $url . '?order=';
        $handle_url = $filter_url . $filter_order;

        return $handle_url;
    }

    public static function admin_render_data_url_order($order_value){
        $class = new AdminHelpers();
        $order_filter_url = $order_value ? $class->admin_get_link_order_filter(request()->fullUrl(), $order_value) : $class->admin_get_link_order_filter(request()->fullUrl(), 'updated_date|DESC');
        return $order_filter_url;
        
    }

  public function admin_convert_result_to_object($data, $check_list_array = 0){

    $values = array();
    if(!empty($data)){
      //$count_records = count($data);
      if($check_list_array == 0){
        foreach($data as $key => $value){
          $object = (object)$value;
          $values[] = $object;
        }
        return $values;
      } else {
        $object = (object)$data[0];
        return $object;
      }

    }
    return NULL;
  }


  public function admin_convert_array_for_selectbox($data){

    $values = array();
    if(!empty($data)){
      foreach($data as $key => $value){
        $values[$key] = $value;
      }
      return $values;

    }
    return NULL;
  }

}