    <div class="login_head">
        <div class="header">
            <div class="nav">
                <div class="nav_left">
                    <a href="{{ route('pc:feeds') }}">
                        <img src="@if(isset($config['common']['logo'])) {{ $routes['storage'] . $config['common']['logo'] }} @else {{ asset('zhiyicx/plus-component-pc/images/logo.png') }} @endif" class="nav_logo" />
                    </a>
                    <!-- <span class="nav_beta font16">beta</span> -->
                </div>
                <div class="login_top">
                    @if (route::currentrouteName() == 'pc:login')
                    <a href="{{ route('pc:register') }}" class="font16 ">注册</a>
                    @else
                    <a href="{{ route('pc:login') }}" class="font16 ">登录</a>
                    @endif
                    <a href="{{ route('pc:feeds') }}" class="font16 ">随便看看</a>
                    <a href="http://www.thinksns.com" class="font16 ">TS+官网</a>
                </div>
            </div>
        </div>
    </div>
