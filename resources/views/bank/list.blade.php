@section('title', '提现列表')
@section('header')
    <div class="layui-inline">
    <button class="layui-btn layui-btn-small layui-btn-normal addBtn" data-desc="添加银行卡" data-url="{{url('/agent/bank/0/edit')}}"><i class="layui-icon">&#xe654;</i></button>
    <button class="layui-btn layui-btn-small layui-btn-warm freshBtn"><i class="layui-icon">&#x1002;</i></button>
    </div>
@endsection
@section('table')
    <table class="layui-table" lay-even lay-skin="nob">
        <colgroup>
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
        </colgroup>
        <thead>
        <tr>
            <th class="hidden-xs">开户人</th>
            <th class="hidden-xs">开户行</th>
            <th class="hidden-xs">开户卡号</th>
            <th class="hidden-xs">添加时间</th>
            <th class="hidden-xs">状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $info)
            <tr>
                <td class="hidden-xs">{{$info['name']}}</td>
                <td class="hidden-xs">{{$info['deposit_name']}}</td>
                <td class="hidden-xs">{{$info['deposit_card']}}</td>
                <td class="hidden-xs">{{$info['creatime']}}</td>
                <td class="hidden-xs">
                    @if($info['status']==1)
                        正常
                    @else
                        异常
                    @endif
                </td>
                <td>
                    <div class="layui-inline">
                        {{--<button class="layui-btn layui-btn-small layui-btn-normal edit-btn" data-id="{{$info['id']}}" data-desc="修改角色" data-url="{{url('/admin/roles/'. $info['id'] .'/edit')}}"><i class="layui-icon">&#xe642;</i></button>--}}
                        <button class="layui-btn layui-btn-small layui-btn-danger del-btn" data-id="{{$info['id']}}" data-url="{{url('/business/bank/'.$info['id'])}}"><i class="layui-icon">&#xe640;</i></button>
                    </div>
                </td>
            </tr>
            @if(!$list[0])
                <tr><td colspan="6" style="text-align: center;color: orangered;">暂无数据</td></tr>
            @endif
        @endforeach
        </tbody>
    </table>
    <div class="page-wrap">
        {{$list->render()}}
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form', 'jquery','laydate', 'layer'], function() {
            var form = layui.form(),
                $ = layui.jquery,
                laydate = layui.laydate,
                layer = layui.layer
            ;
            laydate({istoday: true});
            form.render();
            form.on('submit(formDemo)', function(data) {
            });
        });
    </script>
@endsection
@extends('common.list')
