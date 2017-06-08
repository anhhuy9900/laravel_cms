<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class NewsModel extends Model
{
    protected $table = 'news';

    protected $fillable = [
    	'id',
    	'category_id',
    	'title',
    	'slug',
    	'image',
    	'description',
    	'content',
    	'status',
    	'updated_date',
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
		$obj = new NewsModel;
		$query = DB::table($obj->table.' as pk');
		$query->select('pk.*', 'fk.title as category_title');
		$query->join('categories_news as fk', 'pk.category_id', '=', 'fk.id');
		if(isset($where['key']) && $where['key']) {
			$query->where('pk.title', 'LIKE', '%' . $where['key'] . '%');
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
