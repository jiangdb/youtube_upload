<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YouTubeAccount extends Model
{
    //模型关联数据表
    protected $table = 'youtube_account';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'display_name', 'account_name', 'ssk_key_filename'
    ];
}
