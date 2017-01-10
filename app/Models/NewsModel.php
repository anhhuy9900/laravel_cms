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

    
    public function setUpdatedAtAttribute($value)
	{
	    // to Disable updated_at
	}

	public function setCreatedAtAttribute($value)
	{
	    // to Disable created_at
	}

	public function _get_list_datas($limit, $offset, $where = array(), $order = array('field'=>'id', 'by'=>'DESC')){
		$query = DB::table($this->table);
		$query->select('news.*', 'categories_news.title as category_title');
		$query->join('categories_news', 'news.category_id', '=', 'categories_news.id');
		if(isset($where['key']) && $where['key']) {
			$like = "%" . $where['key'] ."%";
			$query->where($this->table.'.title', 'LIKE', $like);
		}

		if(isset($where['date_range']) && $where['date_range']) {
			$date_from = strtotime(date('Ymd H:i:s',strtotime($where['date_range']['from'])));
			$date_to = strtotime(date('Ymd H:i:s',strtotime($where['date_range']['to'])));
			$query->where($this->table.'.created_date', '>=', $date_from);
			$query->where($this->table.'.created_date', '<=', $date_to);
		}
		$query->orderBy($this->table.'.'.$order['field'], $order['by']);

		//get total all records
		$total = $query->count();

		//get data for pagination
		$query->offset($offset)->limit($limit);
		$results = $query->get();

		return array('data'=> $results, 'total' => $total);
	}

}
