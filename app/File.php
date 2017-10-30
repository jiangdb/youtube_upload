<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //模型关联数据表
    protected $table = 'file_record';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'uid', 'filename', 'csv_path', 'csv_filename', 'vid'
    ];
}
