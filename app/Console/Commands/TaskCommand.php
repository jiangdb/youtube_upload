<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class TaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:handle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info(null, array('task_command' => 'handle start.....'));
        DB::table('file_record')->where('status', '<>', 2)->orderBy('id', 'asc')->chunk(100, function ($videos) {
            foreach($videos as $video) {
                DB::table('file_record')->where('id', $video->id)->update(['status' => 1]);
                $failed = 0;
                if(Storage::disk('local')->exists($video->csv_path)) {

                    $path_arr = explode('/', $video->csv_path);
                    $name_arr = explode('.', $path_arr[1]);
                    $local_xml_name = $name_arr[0].'_result.xml';       //存在本地的视频xml文件名称

                    //检测youtube上的视频目录并把视频result.xml保存到本地一份
                    $shell = '/bin/bash ' . app_path() . '/Console/Commands/filecheck.sh '.$video->filename.' '.$local_xml_name;
                    $res = shell_exec($shell);

                    if($res == -1) {
                        $failed = 1;
                        Log::info(null, array('task_command' => 'youtube视频文件目录：'.$video->filename.'不存在.'));
                    } elseif ($res == 0) {
                        $failed = 1;
                        Log::info(null, array('task_command' => 'youtube视频文件目录：'.$video->filename.'下的result.xml文件不存在.'));
                    } else {

                        if (Storage::disk('local')->exists($path_arr[0].'/'.$local_xml_name) == false) {
                            $failed = 1;
                            Log::info(null, array('task_command' => 'youtube视频文件目录：'.$video->filename.'下的result.xml文件未下载到本地.'));
                        } else {
                            $video_xml = Storage::disk('local')->get($path_arr[0].'/'.$local_xml_name);

                            $data = simplexml_load_string($video_xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
                            if (is_object($data)) $data = (array)$data;

                            $vid = $data['vedio_id'];

                            if(empty($vid)) {
                                $failed = 1;
                                Log::info(null, array('task_command' => 'youtube视频文件目录:'.$video->filename.'的视频id不存在.'));
                            } else {

                                //组合csv文件，同步
                                $csv_path = storage_path('app').'/'.$video->csv_path;
                                $handle = fopen($csv_path, 'r');
                                $csv_datas = array();
                                $n = 0;
                                while($d = fgetcsv($handle)){
                                    if($n == 0)
                                        $d[] = 'Vedio ID';
                                    elseif($n == 1)
                                        $d[] = $vid;
                                    $csv_datas[] = $d;
                                    $n++;
                                }
                                fclose($handle);

                                if(empty($csv_datas)) {
                                    $failed = 1;
                                    Log::info(null, array('task_command' => 'csv文件：'.$video->csv_path.'是空文件.'));
                                } else {

                                    //组合新的csv文件
                                    $new_csv_path = storage_path('app').'/'.$path_arr[0].'/'.$name_arr[0].'_new.'.$name_arr[1];

                                    $fp = fopen($new_csv_path, 'w');

                                    foreach ($csv_datas as $line) {
                                        fputcsv($fp, $line);
                                    }

                                    fclose($fp);

                                    //创建新的视频目录存放组合的csv
                                    $create_csv_dir = $video->filename.'_new';
                                    $shell = '/bin/bash ' . app_path() . '/Console/Commands/filesync.sh '.$create_csv_dir.' '.$new_csv_path.' '.$path_arr[1];
                                    $res = shell_exec($shell);

                                    if($res == 1) {
                                        DB::table('file_record')->where('id', $video->id)->update(['status' => 2, 'vid' => $vid]);
                                        Log::info(null, array('task_command' => '视频文件目录：'.$video->filename.',视频ID:'.$vid.'操作成功.'));
                                    } else {
                                        $failed = 1;
                                        Log::info(null, array('task_command' => '视频文件目录：'.$video->filename.',视频ID:'.$vid.'操作失败.'));
                                    }

                                }
                            }
                        }

                    }

                } else {
                    $failed = 1;
                    Log::info(null, array('task_command' => 'csv文件：'.$video->csv_path.'不存在.'));
                }

                if($failed == 1) {
                    DB::table('file_record')->where('id', $video->id)->update(['status' => 3]);
                }
            }
        });
        Log::info(null, array('task_command' => 'handle over.....'));
    }
}
