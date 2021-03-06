<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //模型关联数据表
    protected $table = 'task';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'uid', 'filename', 'xmlname', 'csv_path', 'csv_filename', 'vid', 'youtube_account_id'
    ];
}
