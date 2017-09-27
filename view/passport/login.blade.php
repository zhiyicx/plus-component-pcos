@section('title')
登录
@endsection

@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="login_container">
    <div class="login_left">
        <img src="@if(isset($config['common']['login_bg'])) {{ $routes['storage'] . $config['common']['login_bg'] }} @else {{ $routes['resource'] }}/images/login_bg.png @endif"/>
    </div>
    <div class="login_right">
        <form method="POST" id="login_form">
            <div class="login_input">
                <input type="text" placeholder="输入手机号/邮箱/昵称" name="login"/>
            </div>
            <div class="login_input">
                <input type="password" placeholder="输入密码" name="password"/>
            </div>
            <div class="login_extra">
                <a class="forget_pwd" href="{{ route('pc:findpassword') }}">忘记密码</a>
            </div>
            <a class="login_button" id="login_btn">登录</a>
        </form>

        <div class="login_right_bottom">
            <span class="no_account">没有账号？<a href="{{ route('pc:register') }}"><span>注册</span></a></span>
            <div class="login_share" >
                三方登录：
                <a href="javascript:" data-type="weibo" class="bind">
                    <svg class="icon icon_weibo" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weibo"></use>
                    </svg>
                </a>
                <a href="javascript:" data-type="qq" class="bind">
                    <svg class="icon icon_qq" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-qq"></use>
                    </svg>
                </a>
                <a href="javascript:" data-type="wechat" class="bind">
                    <svg class="icon icon_weixin" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weixin"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.form.js') }} "></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.passport.js') }} "></script>
<script type="text/javascript">
$(function(){ 
    $(document).keydown(function(event){
        if(event.keyCode==13){
            $("#login_btn").click();
        }
    });
    $('.bind').click('on', function () {
        var type = $(this).data('type');
        window.open("/socialite/"+type, "", "height=560, width=700");
    });
});

function getToken(token) {
    window.location.href = '/passport/token/' + token + '/0';
}

function toBind(other_type, access_token, name) {
    var _token = $('meta[name="_token"]').attr('content');
    var args = {};
    args.other_type = other_type;
    args.access_token = access_token;
    args.name = name;
    args._token = _token;
    var form = $("<form method='post'></form>"),
        input;

    form.attr({"action": '/socialite'});

    $.each(args,function(key,value){
        input = $("<input type='hidden'>");
        input.attr({"name":key});
        input.val(value);
        form.append(input);
    });
    form.appendTo(document.body);
    form.submit();
}
</script>
@endsection
