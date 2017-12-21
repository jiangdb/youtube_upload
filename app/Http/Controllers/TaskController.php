<?php

namespace App\Http\Controllers;

use App\Task;
use App\YouTubeAccount;
use Illuminate\Http\Request;
use App\Http\Requests\TaskPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * FileServerController constructor.
     * 用户需登录认证
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 视频信息添加
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $youtube_accounts = YouTubeAccount::all();
        return view('task.index', compact('youtube_accounts'));
    }

    /**
     * 视频历史信息列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists()
    {
        $sear = [];
        if (!empty($_GET['filename'])) {
            $sear[] = ['filename', 'like', '%' . $_GET['filename'] . '%'];
        }
        if (!empty($_GET['status'])) {
            $_GET['status'] = $_GET['status'] == -1 ? 0 : $_GET['status'];
            $sear[] = ['status', '=', intval($_GET['status'])];
        }
        if (!empty($_GET['begin'])) {
            $sear[] = ['created_at', '>=', $_GET['begin']];
        }
        if (!empty($_GET['end'])) {
            $sear[] = ['created_at', '<=', $_GET['end']];
        }
        if (Auth::user()->is_admin == 0) {
            $sear[] = ['uid', '=', Auth::id()];
        }

        $list = Task::where($sear)->orderBy('updated_at', 'desc')->paginate(15);
        if (!empty($list)) {
            foreach ($list as $key => $val) {
                if ($val->status == 2) {
                    $arr = explode('/', $val->csv_path);
                    if (Storage::disk('local')->exists($arr[0] . '/' . $val->xml_name)) {
                        $val['xml_path'] = $arr[0] . '/' . $val->xml_name;
                    } else {
                        $val['xml_path'] = '';
                    }
                }
                if (!empty($val->youtube_account_id)) {
                    $account = YouTubeAccount::where('id', $val->youtube_account_id)->first();
                    $val->display_name = $account->display_name;
                }
                $list[$key] = $val;
            }
        }
        return view('task.lists', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskPost   $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskPost $request)
    {
        $csv_path = null;
        $real_filename = null;

        $file = $request->file('csv_path');
        if ($file->isValid()) {
            $real_filename = $file->getClientOriginalName();
            $newFileName = time() . random_int(10000, 99999) . ".csv";
            $csv_path = Storage::disk('local')->putFileAs('csv_temp', $file, $newFileName);
        }

        $res = Task::create([
            'uid' => Auth::id(),
            'filename' => $request->input('filename'),
            'xmlname' => $request->input('xmlname'),
            'csv_path' => $csv_path,
            'csv_filename' => $real_filename,
            'vid' => '',
            'youtube_account_id' => $request->input('youtube_account_id'),
        ]);

        $stat = $res ? 1 : -1;

        return redirect('/task')->with('stat', $stat);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Task $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $file, $task_id)
    {
        $info = $file->find($task_id);
        if (empty($info)) {
            return redirect('/task');
        } else {
            $youtube_accounts = YouTubeAccount::all();
            return view('task.index', ['form' => $info, 'youtube_accounts' => $youtube_accounts]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $file)
    {
        $validator = Validator::make($request->all(), [
            'filename' => 'required|max:255',
            'youtube_account_id' => 'required',
        ], [
            'filename.required' => 'xml目录名称不能为空',
            'filename.max' => 'xml目录名称长度不能大于255个字节',
            'xmlname.required' => 'xml文件名称不能为空',
            'xmlname.max' => 'xml文件名称长度不能大于255个字节',
            'csv_path.required' => '请上传视频csv文件',
            'csv_path.mimes' => '上传的文件必须是csv格式',
            'youtube_account_id.required' => '请选择YouTube账户',
        ]);

        $validator->sometimes('csv_path', 'required|file:csv', function ($input) {
            return empty($input->csv_path) ? false : true;
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('form', $file);
        } else {
            $update_datas = [
                'filename' => $request->input('filename'),
                'xmlname' => $request->input('xmlname'),
                'youtube_account_id' => $request->input('youtube_account_id'),
            ];

            $csv = $request->file('csv_path');
            if (!empty($csv) && $csv->isValid()) {
                $update_datas['csv_filename'] = $csv->getClientOriginalName();
                $update_datas['csv_path'] = Storage::disk('local')->putFileAs('csv_temp', $csv, time() . random_int(10000, 99999) . ".csv");
            } elseif ($request->filled('csv_path_val')) {
                $update_datas['csv_path'] = $request->input('csv_path_val');
            }

            $res = $file::where('id', $request->id)->update($update_datas);

            $stat = $res ? 1 : -1;

            return redirect('/task')->with('stat', $stat);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $task_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($task_id)
    {
        if (intval($task_id) > 0) {
            $info = Task::find($task_id);

            $res = $info->delete();
            if ($res) {
                if (!empty($info->csv_path)) {
                    $csv_path = $info->csv_path;
                    $real_path = storage_path('app') . '/' . $csv_path;
                    if (file_exists($real_path)) {
                        Storage::delete($csv_path);
                    }
                }
                return "删除成功!";
            } else {
                return "删除失败!";
            }
        } else {
            return "无效的操作!";
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        return response()->download(storage_path('app') . '/' . $request->get('path'));
    }
}
