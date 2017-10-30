@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{!empty($form) ? '修改' : '添加'}}用户信息信息<a href="{{route('admin.index')}}" style="display: block; float: right; width: 100px; height:100%; font-size: 16px; text-align: center; text-decoration: underline; cursor: pointer;">系统用户列表</a></div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ !empty($form) ? route('admin.update', $form->id) : route('admin.store') }}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(!empty($form))
                                {{method_field('PUT')}}
                                <input type="hidden" name="id" value="{{$form->id}}">
                            @endif

                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">昵称</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?  old('name') : (!empty($form) ? $form->name : '') }}" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">邮箱</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?  old('email') : (!empty($form) ? $form->email : '') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">密码</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" value="{{old('password') ?  old('password') : (!empty($form) ? $form->password : '') }}" required>
                                </div>
                            </div>

                            @if(empty($form))
                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">确认密码</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                            @else
                                <input type="hidden" name="old_password" value="{{$form->password}}">
                                <input type="hidden" name="old_email" value="{{$form->email}}">
                            @endif

                            <div class="form-group">
                                <div class="col-md-8" style="text-align: center;">
                                    <button type="submit" class="btn btn-primary" style="width: 90px;">
                                        提交
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if($errors->has('name'))
        alert("{{ $errors->first('name') }}");
        @endif

        @if($errors->has('email'))
        alert("{{ $errors->first('email') }}");
        @endif

        @if($errors->has('password'))
        alert("{{ $errors->first('password') }}");
        @endif

        @if(Session::has('stat'))
            alert("{{Session::get('stat')}}");
            location.href = '/admin';
        @endif
    </script>
@endsection