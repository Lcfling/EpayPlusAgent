@section('title', '添加银行卡')
@section('content')
    <div class="layui-form-item">
        <label class="layui-form-label">开户姓名：</label>
        <div class="layui-input-block">
            <input type="text" value="{{$info['name'] or ''}}" name="name" required lay-verify="name" placeholder="请输入开户行姓名" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">银行卡号：</label>
        <div class="layui-input-block">
            <input type="text" value="{{$info['deposit_card'] or ''}}" id="bank_idcard" name="deposit_card" required lay-verify="deposit_card" placeholder="请输入银行卡号" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开户行：</label>
        <div class="layui-input-block">
            <input type="text" value="{{$info['deposit_name'] or ''}}" id="bank_name" name="deposit_name" required lay-verify="deposit_name" placeholder="请输入2-12位汉字" autocomplete="off" class="layui-input">
        </div>
    </div>
@endsection
@section('id',$id)
@section('js')
    <script>
        layui.use(['form','jquery','laypage', 'layer'], function() {
            var form = layui.form(),
                $ = layui.jquery;
            var banklist={!! $banklist!!};//不转义字符
            form.render();
            var layer = layui.layer;
            form.verify({
                name: [/[\u4e00-\u9fa5]{2,30}$/, '请输入正确的姓名'],
            });
            $("#bank_idcard").blur(function(){
                var value=$(this).val();
                $.post("https://ccdcapi.alipay.com/validateAndCacheCardInfo.json",{cardNo:value,cardBinCheck:'true'},function(res){
                    //console.log(res); //不清楚返回值的打印出来看
                    //{"cardType":"DC","bank":"ICBC","key":"622200****412565805","messages":[],"validated":true,"stat":"ok"}
                    if(res.validated){
                        var name=banklist[res.bank];
                        //console.log(name);
                        $('#bank_name').val(name);
                        $('#bank_name').text(name);
                    }else{
                        layer.msg('银行卡号错误',{icon:5});
                        //setTimeout($("#deposit_card").focus(),1000); //获取焦点
                        $('#bank_name').val('');
                        $('#bank_name').text('');
                        return false;
                    }
                },'json');
            });
            form.on('submit(formDemo)', function(data) {
                $.ajax({
                    url:"{{url('/agent/bank')}}",
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
                return false;
            });
        });
    </script>
@endsection
@extends('common.edit')
