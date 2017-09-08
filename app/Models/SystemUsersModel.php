<?php

namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;
use DB;

class SystemUsersModel extends Model  implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'system_users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $fillable = [
        'id',
    	'role_id',
    	'username',
    	'email',
        'password',
        'user_token',
        'status',
        'updated_date',
        'created_date',
    ];

    /**
     * A user is owned by a roles
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function role(){

        return $this->belongsTo('App\Models\SystemRoles');

    }

    /**
     * Get list role of article with the current user
     *
     * @return  array
     */
    public function getRoleListAttribute(){

        return $this->role->lists('role_id');
        //return App\Post::find(1)->comments()->where('title', 'foo')->first();

    }

    public function getRoleName($role_id){
        return SystemRole::where('role_id', $role_id)->first()->role_name;
    }

    public function setUpdatedAt($value)
    {
       //Do-nothing
    }

    public function getUpdatedAtColumn()
    {
        //Do-nothing
    }

    public function setCreatedAt($value)
    {
       //Do-nothing
    }

    public function getCreatedAtColumn()
    {
        //Do-nothing
    }

    public static function _get_list_datas($limit, $offset, $where = array(), $order = array('field'=>'id', 'by'=>'DESC'))
    {
        $obj = new SystemUsersModel;
        $query = DB::table($obj->table . ' as pk');
        $query->select('pk.*', 'fk.role_name');
        $query->leftJoin('system_roles as fk', 'pk.role_id', '=', 'fk.id');
        if(isset($where['key']) && $where['key']) {
            $like = "%" . $where['key'] ."%";
            $query->where('pk.username', 'LIKE', $like);
            $query->orWhere('pk.email', 'LIKE', $like);
        }

        if(isset($where['date_range']) && $where['date_range']) {
            $date_from = strtotime(date('Ymd H:i:s',strtotime($where['date_range']['from'])));
            $date_to = strtotime(date('Ymd H:i:s',strtotime($where['date_range']['to'])));
            $query->where('pk.created_date', '>=', $date_from);
            $query->where('pk.created_date', '<=', $date_to);
        }
        $query->orderBy('pk.'.$order['field'], $order['by']);

        //get total all records
        $total = $query->count();

        //get data for pagination
        $query->offset($offset)->limit($limit);
        $results = $query->get();

        return array('data'=> $results, 'total' => $total);
    }
}
