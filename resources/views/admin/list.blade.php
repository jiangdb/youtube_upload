@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 center-block" style="float: none;">
                <div class="panel panel-default">
                    <div class="panel-heading">系统用户列表<a href="{{route('admin.create')}}" style="display: block; float: right; width: 100px; height:100%; font-size: 16px; text-align: center; text-decoration: underline; cursor: pointer;">添加系统用户</a><a href="javascript:AdminPub(0);" style="display: block; float: right; width: 60px; height:100%; font-size: 16px; text-align: center; text-decoration: underline; cursor: pointer;">禁止</a><a href="javascript:AdminPub(1);" style="display: block; float: right; width: 60px; height:100%; font-size: 16px; text-align: center; text-decoration: underline; cursor: pointer;">激活</a></div>

                    <div class="panel-body">
                        <table cellspacing="0" cellpadding="0" width="100%" align="center" class="table table-hover">
                            <thead>
                            <tr>
                                <th style="text-align: center; width: 40px;"><input type="checkbox" onclick="checkAll(this);"></th>
                                <th style="text-align: center;">ID编号</th>
                                <th style="text-align: center;">昵称</th>
                                <th style="text-align: center;">邮箱</th>
                                <th style="text-align: center;">账号状态</th>
                                <th style="text-align: center;">添加日期</th>
                                <th style="text-align: center;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($list) > 0)
                                @foreach($list as $item)
                                    <tr>
                                        <td style="text-align: center;">@if($item->is_admin == 0)<input type="checkbox" class="input_chk" value="{{$item->id}}">@endif</td>
                                        <td style="text-align: center;">{{$item->id}}</td>
                                        <td style="text-align: center;">{{$item->name}}</td>
                                        <td style="text-align: center;">{{$item->email}}</td>
                                        <td style="text-align: center;">
                                            @if($item->status == 1)
                                                已激活
                                            @else
                                                未激活
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{$item->created_at}}</td>
                                        <td style="text-align: center;">
                                            <a href="/admin/{{$item->id}}/edit">编辑</a>@if($item->is_admin == 0)&nbsp;|&nbsp;<a href="javascript:del_item({{$item->id}});">删除</a>@endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="7" align="center">暂无信息...</td></tr>
                            @endif
                            </tbody>
                            @if(count($list) > 0)
                                <tfoot>
                                <tr>
                                    <td colspan="7">{{$list->links()}}</td>
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
            confirm("您是否确定删除当前系统用户？", function(){
                $.post('/admin/'+id, {_method:'DELETE', _token:'{{csrf_token()}}'}, function(res){
                    alert(res);
                    location.reload();
                });
            });
        }

        function checkAll(obj) {
            var is_sel = obj.checked;
            var chk = document.querySelectorAll('.input_chk');
            for(var i = 0; i < chk.length; i++) {
                if(is_sel == true) {
                    chk[i].checked = true;
                } else {
                    chk[i].checked = false;
                }
            }
        }

        function GetChkStr() {
            var chk = document.querySelectorAll('.input_chk');
            var str = '';
            for(var i = 0; i < chk.length; i++) {
                if(chk[i].checked == true)
                    str += chk[i].value + ",";
            }
            if(str != '') str = str.substring(0, str.length - 1);
            return str;
        }

        function AdminPub(pub) {
            var ids = GetChkStr();
            if(ids == '') return alert("请至少选择一个用户");

            $.post('/admin/publish', {_token:'{{csrf_token()}}', ids:ids, status:pub}, function (res) {
                alert(res);
                location.reload();
            });
        }
    </script>
@endsection