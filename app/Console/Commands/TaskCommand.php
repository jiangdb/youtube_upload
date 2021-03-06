<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskCommand extends Command
{
    const LOG_TAG = "[youtube:check_video_handle]: ";

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
        Log::info(self::LOG_TAG . 'handle start.....');
        DB::table('task')
            ->join('youtube_account', 'task.youtube_account_id', '=', 'youtube_account.id')
            ->select('task.*', 'youtube_account.ssk_key_filename', 'youtube_account.account_name')
            ->where('task.status', '<>', 2)
            ->orderBy('task.id', 'asc')
            ->chunk(100, function ($videos) {
                foreach ($videos as $video) {
                    Log::info(self::LOG_TAG . 'VideoID:' . $video->id);
                    DB::table('task')->where('id', $video->id)->update(['status' => 1]);
                    $failed = 0;
                    $xml_name = '';
                    if (Storage::disk('local')->exists($video->csv_path)) {
                        $path_arr = explode('/', $video->csv_path);
                        $name_arr = explode('.', $path_arr[1]);

                        //检测youtube上的视频目录并把视频result.xml保存到本地一份
                        $shell = '/bin/bash ' . app_path() . '/Console/Commands/ytdownload.sh ' . $video->filename . ' report-' . $video->xmlname . ' ' . storage_path() . '/app/csv_temp/ ' . $video->account_name . ' ' . $video->ssk_key_filename;

                        Log::info(self::LOG_TAG . 'ytdownload：' . $shell);
                        $res = trim(shell_exec($shell));
                        Log::info(self::LOG_TAG . '获取youtube视频文件目录' . $video->filename . '下的csv文件的结果：' . $res);

                        if (strpos($res, $video->xmlname)) {
                            $local_xml_name = 'report-'.$video->xmlname; //存在本地的视频csv文件名称

                            if (Storage::disk('local')->exists($path_arr[0] . '/' . $local_xml_name) == false) {
                                $failed = 1;
                                Log::info(self::LOG_TAG . 'youtube视频文件目录' . $video->filename . '下的' . $local_xml_name . '文件未下载到本地.');
                            } else {
                                Log::info(self::LOG_TAG . '下载到本地XML文件路径:' . $path_arr[0] . '/' . $local_xml_name . '.');
                                $xml_name = $local_xml_name;
                                $xml_path = storage_path('app') . '/' . $path_arr[0] . '/' . $local_xml_name;
                                $handle = fopen($xml_path, 'r');
                                if ($handle) {
                                    $n = 0;
                                    while ($data = fgetcsv($handle)) {
                                        $n++;
                                        if ($n == 2) {
                                            $vid = $data[2];
                                            Log::info(self::LOG_TAG . 'youtube视频vid:' . $vid . '.');
                                            break;
                                        }
                                    }

                                    if (empty($vid)) {
                                        $failed = 1;
                                        Log::info(self::LOG_TAG . 'youtube视频文件目录' . $video->filename . '下的' . $local_xml_name . '文件未有Video ID.');
                                    } else {
                                        //组合csv文件，同步
                                        $csv_path = storage_path('app') . '/' . $video->csv_path;
                                        $handle = fopen($csv_path, 'r');
                                        $csv_datas = [];
                                        $n = 0;
                                        while ($d = fgetcsv($handle)) {
                                            if ($n > 0 && !empty($d[2])) {
                                                $d[2] = $vid;
                                            }
                                            $csv_datas[] = $d;
                                            $n++;
                                        }
                                        fclose($handle);

                                        if (empty($csv_datas)) {
                                            $failed = 1;
                                            $xml_name = '';
                                            Log::info(self::LOG_TAG . 'csv文件' . $video->csv_path . '是空文件.');
                                        } else {
                                            //组合新的csv文件
                                            $new_csv = $name_arr[0] . '_new.' . $name_arr[1];
                                            $new_csv_path = storage_path('app').'/'.$path_arr[0].'/'.$new_csv;
                                            Log::info(self::LOG_TAG . '新组合的csv文件路径:' . $new_csv_path . '.');

                                            $fp = fopen($new_csv_path, 'w');

                                            foreach ($csv_datas as $line) {
                                                fputcsv($fp, $line);
                                            }

                                            fclose($fp);

                                            //创建新的视频目录存放组合的csv
                                            $create_csv_dir = $video->filename . '_new';
                                            $delivery_complete = resource_path() . '/assets/delivery.complete';
                                            $shell = '/bin/bash ' . app_path() . '/Console/Commands/ytupload.sh ' . storage_path('app') . '/' . $path_arr[0] . '/ ' . $new_csv . ' ' . $create_csv_dir . ' ' . $video->account_name . ' ' . $video->ssk_key_filename . ' ' . $delivery_complete;
                                            Log::info(self::LOG_TAG . 'ytupload：' . $shell);
                                            $res = shell_exec($shell);
                                            Log::info(self::LOG_TAG . '上传youtube视频文件目录' . $create_csv_dir . '下的csv文件的结果：' . $res);

                                            if (strpos($res, $new_csv)) {
                                                DB::table('task')->where('id', $video->id)->update(['status' => 2, 'xml_name' => $local_xml_name, 'vid' => $vid]);
                                                Log::info(self::LOG_TAG . '视频文件目录' . $video->filename . ',视频ID:' . $vid . '操作成功.');
                                            } else {
                                                $failed = 1;
                                                $xml_name = '';
                                                Log::info(self::LOG_TAG . '视频文件目录' . $video->filename . ',视频ID:' . $vid . '操作失败.');
                                            }
                                        }
                                    }

                                } else {
                                    $failed = 1;
                                    Log::info(self::LOG_TAG . 'youtube视频文件目录' . $video->filename . '下的' . $local_xml_name . '文件下载到本地出现异常.');
                                }
                            }
                        } else {
                            Log::info(self::LOG_TAG . 'youtube视频文件目录' . $video->filename . '下的xml文件无效.');
                        }
                    } else {
                        $failed = 1;
                        Log::info(self::LOG_TAG . 'csv文件' . $video->csv_path . '不存在.');
                    }

                    if ($failed == 1) {
                        DB::table('task')->where('id', $video->id)->update(['status' => 3, 'xml_name' => $xml_name]);
                    }
                }
            });
        Log::info(self::LOG_TAG . 'handle over.....');
    }
}
