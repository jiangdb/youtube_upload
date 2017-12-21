@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">添加YouTube视频信息<a href="{{route('task.lists')}}" style="display: block; float: right; width: 100px; height:100%; font-size: 16px; text-align: center; text-decoration: underline; cursor: pointer;">视频列表</a></div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ !empty($form) ? route('task.update', $form->id) : route('task.store') }}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(!empty($form))
                                {{method_field('PUT')}}
                                <input type="hidden" name="id" value="{{$form->id}}">
                            @endif
                            <div class="form-group">
                                <label for="filename" class="col-md-3 control-label">xml文件目录</label>
                                <div class="col-md-6">
                                    <input id="filename" type="text" class="form-control" name="filename" value="{{ old('filename') ?  old('filename') : (!empty($form) ? $form->filename : '') }}" required autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="xmlname" class="col-md-3 control-label">xml文件名称</label>
                                <div class="col-md-6">
                                    <input id="xmlname" type="text" class="form-control" name="xmlname" value="{{ old('xmlname') ?  old('xmlname') : (!empty($form) ? $form->xmlname : '') }}" required autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="csv_path" class="col-md-3 control-label">视频csv文件</label>
                                <div class="col-md-6">
                                    <input type="hidden" name="csv_path_val" value="{{!empty($form) ? $form->csv_path : ''}}">
                                    <input id="csv_path" type="file" accept=".csv" class="form-control" name="csv_path" {{(!empty($form) && !empty($form->csv_path)) ? '' : 'required'}}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="xmlname" class="col-md-3 control-label">YouTube账户</label>
                                <div class="col-md-6">
                                    <select id="youtube_account_id" class="form-control" name="youtube_account_id" required autofocus>
                                        <option value="">请选择</option>
                                        @foreach($youtube_accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('youtube_account_id') ?  (old('youtube_account_id') == $account->id ? 'selected' : '') : (!empty($form) ? ($form->youtube_account_id  == $account->id ? 'selected' : '') : '') }}>{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
        @if($errors->has('filename'))
            alert("{{ $errors->first('filename') }}");
        @endif

        @if($errors->has('csv_path'))
            alert("{{ $errors->first('csv_path') }}");
        @endif

        @if($errors->has('youtube_account_id'))
            alert("{{ $errors->first('youtube_account_id') }}");
        @endif

        @if(Session::has('stat'))
            @if(Session::get('stat') == 1)
                alert("保存成功");
                location.href = '/task/lists';
            @elseif(Session::get('stat') == -1)
                alert("保存失败");
            @endif
        @endif
    </script>
@endsection
