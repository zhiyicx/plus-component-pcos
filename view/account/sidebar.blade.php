@php $route = Route::currentRouteName(); @endphp
<div class="account_l">
    <ul class="account_menu">
        <a href="{{ Route('pc:account') }}">
            <li class="@if ($account_cur == 'index')active @endif"><i class="iconfont icon-ziliao"></i>基本资料</li>
        </a>
        <a href="{{ Route('pc:tags') }}">
            <li class="@if ($account_cur == 'tags')active @endif"><i class="iconfont icon-ziliao"></i>标签管理</li>
        </a>
        <a href="{{ Route('pc:authenticate') }}">
            <li class="@if ($account_cur == 'authenticate')active @endif"><i class="iconfont icon-ziliao"></i>认证管理</li>
        </a>
        <a href="{{ Route('pc:security')}}">
            <li class="@if ($account_cur == 'security')active @endif"><i class="iconfont icon-ziliao"></i>安全设置</li>
        </a>
        <a href="{{ Route('pc:binds')}}">
            <li class="@if ($account_cur == 'binds')active @endif"><i class="iconfont icon-ziliao"></i>账号管理</li>
        </a>
    </ul>
</div>
