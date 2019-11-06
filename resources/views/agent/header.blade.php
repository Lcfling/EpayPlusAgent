<div class="main-layout-header">
    <div class="menu-btn" id="hideBtn">
        <a href="javascript:;">
            <span class="iconfont">&#xe60e;</span>
        </a>
    </div>
    <ul class="layui-nav" lay-filter="rightNav">
        <li class="layui-nav-item">
            <div class="addBtn hidden-xs" data-desc="代理商信息" data-url="{{url('/agent/info/userinfo')}}">&nbsp;<i class="layui-icon">&#xe612;</i>&nbsp;代理商&nbsp;</div>
        </li>
        <li class="layui-nav-item"><a href="{{url('/agent/logout')}}">退出</a></li>
    </ul>
</div>