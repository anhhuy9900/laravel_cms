<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class CategoriesNewsModel extends Model
{
    protected $table = 'categories_news';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'status',
        'update_date',
        'created_date'
    ];


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
        $obj = new CategoriesNewsModel;
        $query = DB::table($obj->table)->select('*');

        if(isset($where['key']) && $where['key']) {
            $like = '%' . $where['key'] .'%';
            $query->where('title', 'LIKE', $like);

        }

        if(isset($where['date_range']) && $where['date_range']) {
            $date_from = strtotime(date('Ymd H:i:s',strtotime($where['date_range']['from'])));
            $date_to = strtotime(date('Ymd H:i:s',strtotime($where['date_range']['to'])));
            $query->where('created_date', '>=', $date_from);
            $query->where('created_date', '<=', $date_to);
        }
        $query->orderBy($order['field'], $order['by']);

        //get total all records
        $total = $query->count();

        //get data for pagination
        $query->offset($offset)->limit($limit);
        $results = $query->get();

        return array('data'=> $results, 'total' => $total);
    }

}