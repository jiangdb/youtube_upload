<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\FileServerPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileServerController extends Controller
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
        return view('file.index');
    }

    /**
     * 视频历史信息列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists()
    {
        $sear = array();
        if(!empty($_GET['filename'])) {
            $sear[] = array('filename', 'like', '%'.$_GET['filename'].'%');
        }
        if(!empty($_GET['status'])) {
            $_GET['status'] = $_GET['status'] == -1 ? 0 : $_GET['status'];
            $sear[] = array('status', '=', intval($_GET['status']));
        }
        if(!empty($_GET['begin'])) {
            $sear[] = array('created_at', '>=', $_GET['begin']);
        }
        if(!empty($_GET['end'])) {
            $sear[] = array('created_at', '<=', $_GET['end']);
        }
        if(Auth::user()->is_admin == 0) {
            $sear[] = array('uid', '=', Auth::id());
        }

        $list = File::where($sear)->orderBy('updated_at', 'desc')->paginate(15);
        if(!empty($list)) {
            foreach ($list as $key => $val) {
                if($val->status == 2) {
                    $arr = explode('.', $val->csv_path);
                    if(Storage::disk('local')->exists($arr[0].'_result.xml')) {
                        $val['xml_path'] = $arr[0].'_result.xml';
                    } else {
                        $val['xml_path'] = '';
                    }
                }
                $list[$key] = $val;
            }
        }
        return view('file.lists', ['list' => $list]);
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
     * @param FileServerPost   $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileServerPost $request)
    {
        $csv_path = null;
        $real_filename = null;

        $file = $request->file('csv_path');
        if($file->isValid()) {
            $real_filename = $file->getClientOriginalName();
            $newFileName = time().random_int(10000, 99999).".csv";
            $csv_path = Storage::disk('local')->putFileAs('csv_temp', $file, $newFileName);
        }

        $res = File::create([
            'uid'           => Auth::id(),
            'filename'      => $request->input('filename'),
            'csv_path'      => $csv_path,
            'csv_filename'  => $real_filename,
            'vid'           => ''
        ]);

        $stat = $res ? 1 : -1;

        return redirect('/file')->with('stat', $stat);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        $info = $file->find($file->id);
        if(empty($info)) {
            return redirect('/file');
        } else {
            return view('file.index', ['form' => $info]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        $validator = Validator::make($request->all(), [
            'filename' => 'required|max:255'
        ], [
            'filename.required' => '文件名称不能为空',
            'filename.max'      => '文件名称长度不能大于255个字节',
            'csv_path.required' => '请上传视频csv文件',
            'csv_path.mimes'    => '上传的文件必须是csv格式'
        ]);

        $validator->sometimes('csv_path', 'required|file:csv', function ($input) {
            return empty($input->csv_path) ? false : true;
        });

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('form', $file);
        } else {
            $update_datas = array('filename' => $request->input('filename'));

            $csv = $request->file('csv_path');
            if(!empty($csv) && $csv->isValid()) {
                $update_datas['csv_filename'] = $csv->getClientOriginalName();
                $update_datas['csv_path'] = Storage::disk('local')->putFileAs('csv_temp', $csv, time().random_int(10000, 99999).".csv");
            } elseif ($request->filled('csv_path_val')) {
                $update_datas['csv_path'] = $request->input('csv_path_val');
            }

            $res = $file::where('id', $file->id)->update($update_datas);

            $stat = $res ? 1 : -1;

            return redirect('/file')->with('stat', $stat);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        if(intval($file->id) > 0){
            $info = $file->find($file->id);

            $res = $file->delete();
            if($res){
                if(!empty($info->csv_path)){
                    $csv_path = $info->csv_path;
                    $real_path = storage_path('app').'/'.$csv_path;
                    if(file_exists($real_path)){
                        Storage::delete($csv_path);
                    }
                }
                return "删除成功!";
            }else{
                return "删除失败!";
            }
        }else{
            return "无效的操作!";
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        return response()->download(storage_path('app').'/'.$request->get('path'));
    }
}
