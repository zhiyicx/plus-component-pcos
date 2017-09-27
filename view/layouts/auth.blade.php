<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('title')</title>

    <script>
        var API = '{{ $routes["api"] }}';
        var MID = "{{ $TS['id'] or 0 }}";
        var NAME = "{{ $TS['name'] or '' }}";
        var AVATAR = "{{ $TS['avatar'] }}";
        var TOKEN = "{{ $token or '' }}";
        var SITE_URL = "{{ $routes['siteurl'] }}";
        var RESOURCE_URL = '{{ $routes["resource"] }}';
        var SOCKET_URL = "{{ $routes['socket_url'] or ''}}";
    </script>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/passport.css') }}">
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.min.js') }}"></script>
</head>

<body @yield('body_class')>

    <div class="wrap">
        <!-- nav -->
        @include('pcview::layouts.partials.authnav')

        <!-- noticebox -->
        <div class="noticebox authnoticebox">
        </div>

        <!-- content -->
        <div class="main">
        @yield('content')
        </div>
    </div>

    <!-- footer -->
    @include('pcview::layouts.partials.authfooter')

    <script src="{{ asset('zhiyicx/plus-component-pc/js/common.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/font/iconfont.js') }}"></script>

    <!-- js -->
    @yield('scripts')

</body>

</html>
