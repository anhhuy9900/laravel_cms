<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class TagsModel extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'id',
        'type_id',
        'tag_name',
        'type',
        'status',
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

}