<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/10/20
 * Time: 15:20
 */

namespace App\Util;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;


class FileHandle
{
    static public function fileStorageShow()
    {
        $file_path = Request::path();
        $exists =Storage::disk('local')->exists($file_path);
        if($exists) {
           $arr = explode('.', $file_path);
           $ext =$arr[count($arr) - 1];
           if($ext == 'csv') {
                header('Content-type:text/csv');
                echo Storage::disk('local')->get($file_path);
                exit;
           }
        } else {
            header('HTTP/1.1 404 Not Found');
            header('STATUS:404 Not Found');
            exit;
        }
    }
}