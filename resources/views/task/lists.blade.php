@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 center-block" style="float: none;">
                <div class="panel panel-default">
                    <div class="panel-heading">YouTube视频列表<a href="{{route('task.index')}}" style="display: block; float: right; width: 100px; height:100%; font-size: 16px; text-align: center; text-decoration: underline; cursor: pointer;">添加视频信息</a></div>

                    <div style="width: 100%; height: 50px; border-bottom: 1px #d3e0e9 solid;">
                        <form action="{{ route('task.lists') }}" method="get">
                        <table cellpadding="0" cellspacing="0" style="margin:0 auto; margin-top:8px;">
                            <tr>
                                <td width="100" style="text-align: right;">文件名称：</td><td width="120"><input type="text" name="filename"  value="{{ isset($_GET['filename']) ? $_GET['filename'] : '' }}" placeholder="输入文件名称" class="form-control"></td>
                                <td width="100" style="text-align: right;">处理状态：</td><td width="120"><select name="status" class="form-control"><option value="">请选择</option><option value="-1" {{ (isset($_GET['status']) && $_GET['status'] == 0) ? 'selected' : '' }}>未开始</option><option value="1" {{ (isset($_GET['status']) && $_GET['status'] == 1) ? 'selected' : '' }}>处理中</option><option value="2" {{ (isset($_GET['status']) && $_GET['status'] == 2) ? 'selected' : '' }}>处理成功</option><option value="3" {{ (isset($_GET['status']) && $_GET['status'] == 3) ? 'selected' : '' }}>处理失败</option></select></td>
                                <td width="100" style="text-align: right;">添加日期：</td><td width="350"><input type="text" name="begin" value="{{ isset($_GET['begin']) ? $_GET['begin'] : '' }}" placeholder="开始日期" class="form-control" style="width:170px; float:left;" onclick="SetDate(this,'yyyy-MM-dd hh:mm:ss')"><input type="text" name="end" value="{{ isset($_GET['end']) ? $_GET['end'] : '' }}" placeholder="结束日期" class="form-control" style="width:170px; float:left;" onclick="SetDate(this,'yyyy-MM-dd hh:mm:ss')"></td>
                                <td width="130" style="text-align: right;">
                                    <button type="submit" class="btn btn-primary" style="width: 60px;">
                                        搜索
                                    </button>
                                    <button type="button" onclick="reset_list();" class="btn btn-primary" style="width: 60px; background: #cccccc; border-color: #cccccc;">
                                        重置
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="panel-body">
                        <table cellspacing="0" cellpadding="0" align="center" class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width:60px;">ID编号</th>
                                    <th style="text-align: center;">xml文件目录</th>
                                    <th style="text-align: center;">xml文件名称</th>
                                    <th style="text-align: center;">CSV文件</th>
                                    <th style="text-align: center; width:120px;">YouTube账户</th>
                                    <th style="text-align: center; width:100px;">处理状态</th>
                                    <th style="text-align: center; width:100px;">添加日期</th>
                                    <th style="text-align: center; width:100px;">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($list) > 0)
                                    @foreach($list as $item)
                                    <tr>
                                        <td style="text-align: center;">{{$item->id}}</td>
                                        <td><span style="display: block; max-width:200px; word-wrap:break-word;">{{$item->filename}}</span></td>
                                        <td><span style="display: block; max-width:200px; word-wrap:break-word;">{{$item->xmlname}}</span></td>
                                        <td><span style="display: block; max-width:200px; word-wrap:break-word;"><a href="{{asset($item->csv_path)}}">{{$item->csv_filename}}</a></span></td>
                                        <td style="text-align: center;">{{$item->display_name}}</td>
                                        <td style="text-align: center;">
                                            @if($item->status == 1)
                                                处理中
                                            @elseif($item->status == 2)
                                                处理成功
                                            @elseif($item->status == 3)
                                                处理失败
                                            @else
                                                未开始
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{$item->created_at}}</td>
                                        <td style="text-align: center;">
                                            @if($item->status == 0 || $item->status == 3)
                                                <a href="/task/{{$item->id}}/edit">编辑</a>
                                                @if(!empty($item->xml_path))
                                                    &nbsp|&nbsp<a href="/task/download?path={{ $item->xml_path }}">下载xml</a>
                                                @else
                                                    &nbsp;|&nbsp;
                                                @endif
                                            @elseif($item->status == 2)
                                                <a href="/task/download?path={{ $item->xml_path }}">下载xml</a>&nbsp;|&nbsp;
                                            @endif
                                            <a href="javascript:del_item({{$item->id}});">删除</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="8" align="center">暂无信息...</td></tr>
                                @endif
                            </tbody>
                            @if(count($list) > 0)
                            <tfoot>
                                <tr>
                                    <td colspan="8">{{$list->appends($_GET)->links()}}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function del_item(id) {
            $.post('/task/'+id, {_method:'DELETE', _token:'{{csrf_token()}}'}, function(res){
                alert(res);
                location.reload();
            });
        }
        function reset_list() {
            location.href = '/task/lists';
        }
    </script>
@endsection