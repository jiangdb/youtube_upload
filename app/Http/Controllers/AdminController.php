<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\AdminUserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin.right']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = User::paginate(15);
        return view('admin.list', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUserPost $request)
    {
        $res = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $error = $res ? '保存成功' : '保存失败';

        return redirect()->back()->with('stat', $error);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = DB::table('users')->where('id', $id)->first();
        if(empty($info)) {
            return redirect('/admin');
        } else {
            return view('admin.edit', ['form' => $info]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255'
        ], [
            'name.required' => '请输入用户昵称',
            'name.max'      => '用户昵称长度不能大于255个字节',
            'email.required' => '请输入用户邮箱',
            'email.email'    => '请输入有效的邮箱地址',
            'email.max'    => '邮箱地址长度不能大于255个字节',
            'email.unique'    => '当前用户邮箱地址已存在'
        ]);

        $validator->sometimes('email', 'unique:users', function ($input) {
            return ($input->old_email == $input->email) ? false : true;
        });

        $validator->sometimes('password', 'required|min:6', function ($input) {
            return ($input->old_paaaword == $input->password) ? false : true;
        });

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $update_datas = array('name' => $request->input('name'), 'email' => $request->input('email'));
            if($request->input('password') != $request->input('old_password')) {
                $update_datas['password'] = bcrypt($request->input('password'));
            }

            if(empty($user->id)) $user->id = $request->input('id');
            $res = $user::where('id', $user->id)->update($update_datas);

            $error = $res ? '修改成功' : '修改失败';

            return redirect()->back()->with('stat', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(intval($id) > 0){
            $res = DB::table('users')->delete('id', $id);
            return $res ? "删除成功" : "删除失败";

        }else{
            return "无效的操作!";
        }
    }

    /**
     *
     */
    public function publish(Request $request)
    {
        $ids = strval($request->input('ids'));
        $status = intval($request->input('status'));

        $res = DB::table('users')->whereIn('id', array($ids))->update(['status' => $status]);

        return $res ? "用户账号状态更新成功" : "用户账号状态更新失败";
    }
}
