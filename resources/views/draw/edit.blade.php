@section('title', '提现申请')
@section('content')
    <div class="layui-form-item">
        <label class="layui-form-label">提现额度：</label>
        <div class="layui-input-block">
            <input type="number" name="money" required lay-verify="money" id="money" placeholder="请输入提现额度" autocomplete="off" class="layui-input">
            <input type="hidden" value="{{$balance['balance']}}" id="balance">
            <div class="layui-form-mid layui-word-aux">您的可提现余额为：￥<b style="color: red;">{{$balance['balance']}}</b>&nbsp;<span class="layui-breadcrumb"><a href="#" data-key="{{$balance['balance']}}" id="draw">全部提现</a></span></div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">银行卡选择：</label>
        <div class="layui-input-block">
            <select name="bank_card">
                <option value="">请选择</option>
                @foreach($banklist as $info)
                    <option value="{{$info['id']}}">{{$info['deposit_card']}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">提现密码：</label>
        <div class="layui-input-block">
            <input type="text" name="paypassword" required lay-verify="paypassword" placeholder="请输入您的提现密码" autocomplete="off" class="layui-input">
        </div>
    </div>
@endsection
@section('id',$id)
@section('js')
    <script>
        layui.use(['form','jquery','laypage', 'layer'], function() {
            var form = layui.form(),
                $ = layui.jquery;
            form.render();
            //全部提现
            $('#draw').click(function () {
                var _this = $(this);
                var money = _this.attr('data-key');
                $('#money').val(money);
            });
            var layer = layui.layer;
            form.verify({

            });
            form.on('submit(formDemo)', function(data) {
                var number = $('#money').val();
                if(number==0||number<0){
                    layer.msg('余额不足，不能提现！',{shift: 6,icon:5});
                }else{
                    $.ajax({
                        url:"{{url('/agent/draw')}}",
                        data:$('form').serialize(),
                        type:'post',
                        dataType:'json',
                        success:function(res){
                            if(res.status == 1){
                                layer.msg(res.msg,{icon:6});
                                var index = parent.layer.getFrameIndex(window.name);
                                setTimeout('parent.layer.close('+index+')',2000);
                            }else{
                                layer.msg(res.msg,{shift: 6,icon:5});
                            }
                        },
                        error : function(XMLHttpRequest, textStatus, errorThrown) {
                            layer.msg('网络失败', {time: 1000});
                        }
                    });
                }
                return false;
            });
        });
    </script>
@endsection
@extends('common.edit')
