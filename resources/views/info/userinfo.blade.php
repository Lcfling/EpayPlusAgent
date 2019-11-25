<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>修改用户</title>
    <link rel="stylesheet" type="text/css" href="/static/admin/layui/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="/static/admin/css/admin.css"/>
</head>
<body>
<div class="layui-tab page-content-wrap">
    <ul class="layui-tab-title">
        <input type="hidden" id="token" value="{{csrf_token()}}">
        <li class="layui-this">个人资料</li>
        <li>修改登录密码</li>
        <li>修改支付密码</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form class="layui-form"  style="width: 90%;padding-top: 20px;" id="info_form">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">昵称：</label>
                    <div class="layui-input-block">
                        <input type="text" name="agent_name"  autocomplete="off" class="layui-input" value="{{$userinfo['agent_name']}}">
                        <input type="hidden" id="agent_name" value="{{$userinfo['agent_name']}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名：</label>
                    <div class="layui-input-block">
                        <input type="text" name="account" disabled autocomplete="off" class="layui-input layui-disabled" value="{{$userinfo['account']}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号：</label>
                    <div class="layui-input-block">
                        <input type="text" name="mobile" placeholder="请输入手机号" disabled autocomplete="off" class="layui-input layui-disabled" value="{{$userinfo['mobile']}}">
                    </div>
                </div>
                <input name="id" type="hidden" value="{{$userinfo['id'] or 0}}">
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="adminInfo">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-tab-item">
            <form class="layui-form" style="width: 90%;padding-top: 20px;" id="pwd_form">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名：</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" disabled autocomplete="off" class="layui-input layui-disabled" value="{{$userinfo['account']}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">旧密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="oldpwd" required lay-verify="oldpwd" placeholder="请输入密码" autocomplete="off" class="layui-input" id="oldpwd">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="pwd" required lay-verify="pwd" placeholder="请输入密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">重复密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="pwd_confirmation" required lay-verify="pwd_confirmation" placeholder="请输入密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <input name="id" type="hidden" value="{{$userinfo['id'] or 0}}">

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="adminPassword">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-tab-item">
            <form class="layui-form" style="width: 90%;padding-top: 20px;" id="paypwd_form">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">旧支付密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="oldpaypwd" required lay-verify="oldpaypwd" id="oldpaypwd" placeholder="请输入旧支付密码" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新支付密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="paypwd" required lay-verify="paypwd" placeholder="请输入新支付密码" autocomplete="off" class="layui-input" maxlength="6">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">重复密码：</label>
                    <div class="layui-input-block">
                        <input type="password" name="paypwd_confirmation" required lay-verify="paypwd_confirmation" placeholder="请再次输入密码" autocomplete="off" maxlength="6" class="layui-input">
                    </div>
                </div>
                <input name="id" type="hidden" value="{{$userinfo['id'] or 0}}">

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="adminPayPassword">立即提交</button>
                        @if($userinfo['pay_pass']==null || $userinfo['pay_pass']=='')
                            <input class="layui-btn layui-btn-normal" onclick="setPassword()" value="设置支付密码">
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/static/admin/layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script>
    layui.use(['form','jquery','element'], function(){
        var form = layui.form(),
            $ = layui.jquery;
        form.render();
        $('#oldpwd').blur(function () {
            var _this = $(this);
            var oldpwd = _this.val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                },
                url:"{{url('/agent/info/valPwd')}}",
                data:{
                    "oldpwd":oldpwd
                },
                type:"post",
                dataType:"json",
                success:function (res) {
                    if(res.status==1){
                        layer.msg(res.msg,{shift: 6,icon:5});
                    }
                }
            });
        });
        $('#oldpaypwd').blur(function () {
            var _this = $(this);
            var oldpaypwd = _this.val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                },
                url:"{{url('/agent/info/valPaypwd')}}",
                data:{
                    "oldpaypwd":oldpaypwd
                },
                type:"post",
                dataType:"json",
                success:function (res) {
                    if(res.status==1){
                        layer.msg(res.msg,{shift: 6,icon:5});
                    }
                }
            });
        });
        form.verify({
            oldpwd:function(value){
                if(value&&!/^(?!([a-zA-Z]+|\d+)$)[a-zA-Z\d]{6,12}$/.test(value)){
                    return '旧密码必须6到12位数字加字母';
                }
            },
            pwd:function(value){
                if(value==$("input[name='oldpwd']").val()){
                    return '新密码不能与旧密码一样';
                }
                if(value&&!/^(?!([a-zA-Z]+|\d+)$)[a-zA-Z\d]{6,12}$/.test(value)){
                    return '旧密码必须6到12位数字加字母';
                }
            },
            pwd_confirmation: function(value) {
                if(value && $("input[name='pwd']").val() != value) {
                    return '两次输入密码不一致';
                }
            },
            oldpaypwd:function (value) {
                if(value&&!/^\d{6}$/.test(value)){
                    return '只能是6位纯数字！';
                }
            },
            paypwd:function (value) {
                if(value==$("input[name='oldpaypwd']").val()){
                    return '旧密码不能与新密码相同！';
                }
                if(value&&!/^\d{6}$/.test(value)){
                    return '只能是6位纯数字！';
                }
            },
            paypwd_confirmation:function (value) {
                if(value && $("input[name='paypwd']").val() != value){
                    return '两次支付密码不一致！';
                }
            }
        });
        form.on('submit(adminInfo)', function(data){
            var nickName = $("input[name='agent_name']").val();
            var nickname = $('#agent_name').val();
            if(nickName==nickname){
                layer.msg('您没有修改不需要提交！',{icon:6});
            }else{
                $.ajax({
                    url:"{{url('/agent/info/resInfo')}}",
                    data:$('#info_form').serialize(),
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
        form.on('submit(adminPassword)', function(data){
            $.ajax({
                url:"{{url('/agent/info/resPwd')}}",
                data:$('#pwd_form').serialize(),
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
        form.on('submit(adminPayPassword)', function(data){
            $.ajax({
                url:"{{url('/agent/info/resPaypwd')}}",
                data:$('#paypwd_form').serialize(),
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
    function setPassword() {
        layer.open({
            type:1,
            title: false,
            closeBtn:false,
            area: '400px',
            shade:0.8,
            id:'LAY_layuipro',//设定一个id,防止重复弹出
            btn: ['点击关闭'],
            btnAlign:'c',
            moveType:1,//拖拽模式 0或1
            content:'<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">' +
                '<div class="layui-form-item"><input type="password" name="paypasswords" id="paypasswords" placeholder="支付密码" class="layui-input" maxlength="6" oninput="value=value.replace(/[^\\d]/g,\'\')"></div>' +
                '<div class="layui-form-item"><input type="password" name="newpaypwds" id="newpaypwds" placeholder="确认支付密码" class="layui-input" maxlength="6" oninput="value=value.replace(/[^\\d]/g,\'\')"></div>' +
                '<div class="layui-form-item">' +
                '<input type="button" class="layui-btn layui-btn-normal" onclick="submit()" value="确认">' +
                '</div>' +
                '</div>',
            success:function (layero) {
                var btn = layero.find('.layui-layer-btn');

            }
        });
    }
    function submit() {
        //获取表单信息
        var pwd = document.getElementById('paypasswords');
        var newpwd = document.getElementById('newpaypwds');
        if(pwd.value!==newpwd.value){
            layer.msg("两次密码输入不同！",{shift: 6,icon:5});
        }else{
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                },
                url:"{{url('/agent/info/setPayPwd')}}",
                data:{
                    "paypassword":pwd.value
                },
                type:"post",
                dataType:"json",
                success:function (res) {
                    if(res.status==1){
                        layer.msg(res.msg,{icon:6});
                        var index = parent.layer.getFrameIndex(window.name);
                        setTimeout('parent.layer.close('+index+')',2000);
                    }else{
                        layer.msg(res.msg,{shift: 6,icon:5});
                    }
                }
            });
        }
    }
</script>
</body>
</html>