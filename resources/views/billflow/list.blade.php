@section('title', '提现列表')
@section('header')
    <div class="layui-inline">
    <button class="layui-btn layui-btn-small layui-btn-warm freshBtn"><i class="layui-icon">&#x1002;</i></button>
    </div>
    <div class="layui-inline">
        <input class="layui-input" name="creatime" placeholder="创建时间" lay-verify="creatime"  onclick="layui.laydate({elem: this, festival: true})" value="{{ $input['creatime'] or '' }}">
    </div>
    <div class="layui-inline">
        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">搜索</button>
        <button class="layui-btn layui-btn-normal" id="res">重置</button>
        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo" name="export" value="export">导出</button>
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
            <col class="hidden-xs" width="150">
        </colgroup>
        <thead>
        <tr>
            <th class="hidden-xs">订单号</th>
            <th class="hidden-xs">积分</th>
            <th class="hidden-xs">商户号</th>
            <th class="hidden-xs">状态</th>
            <th class="hidden-xs">支付类型</th>
            <th class="hidden-xs">备注</th>
            <th class="hidden-xs">创建时间</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $info)
            <tr>
                <td class="hidden-xs">{{$info['order_sn']}}</td>
                <td>{{$info['score']}}</td>
                <td class="hidden-xs">{{$info['agent_id']}}</td>
                <td class="hidden-xs">
                    @if($info['status']==1)
                        支付
                    @elseif($info['status']==2)
                        利润
                    @endif
                </td>
                <td class="hidden-xs">
                    @if($info['paycode']==1)
                        微信
                    @elseif($info['paycode'])
                        支付宝
                    @endif
                </td>
                <td class="hidden-xs">{{$info['remark']}}</td>
                <td class="hidden-xs">{{$info['creatime']}}</td>
            </tr>
        @endforeach
        </tbody>
        <div class="page-wrap">
            {{$list->render()}}
        </div>
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
            $('#res').click(function () {
                $("input[name='creatime']").val('');
                $('form').submit();
            });
        });
    </script>
@endsection
@extends('common.list')
