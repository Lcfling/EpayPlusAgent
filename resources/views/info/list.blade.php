@section('title', '提现列表')
@section('header')
    <div class="layui-inline">
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
        </colgroup>
        <thead>
        <tr>
            <th class="hidden-xs">代理商ID</th>
            <th class="hidden-xs">代理商名称</th>
            <th class="hidden-xs">账号</th>
            <th class="hidden-xs">手机号</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td class="hidden-xs">{{$list['id']}}</td>
                <td class="hidden-xs">{{$list['agent_name']}}</td>
                <td class="hidden-xs">{{$list['account']}}</td>
                <td class="hidden-xs">{{$list['mobile']}}</td>
            </tr>
        </tbody>
    </table>
    <table class="layui-table" lay-even lay-skin="nob">
        <colgroup>
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
            <col class="hidden-xs" width="150">
        </colgroup>
        <thead>
        <tr>
            <th class="hidden-xs">商户ID</th>
            <th class="hidden-xs">商户名</th>
            <th class="hidden-xs">代理商费率</th>
            <th class="hidden-xs">商户费率</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $data)
            <tr>
                <td class="hidden-xs">{{$data['business_code']}}</td>
                <td class="hidden-xs">{{$data['nickname']}}</td>
                <td class="hidden-xs">{{$data['agent1_fee']*100}}%</td>
                <td class="hidden-xs">{{$data['fee']*100}}%</td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
