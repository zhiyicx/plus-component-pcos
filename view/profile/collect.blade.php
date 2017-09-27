@section('title') {{ $user->name }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="collect_box">
        {{-- 收藏列表 --}}
        <div class="feed_content">
            <div class="feed_menu J-menu">
                <a class="active" href="javascript:;" cid="1">动态</a>
                <a href="javascript:;" cid="2">文章</a>
                {{-- <a href="javascript:;" cid="3">回答</a>
                <a href="javascript:;" cid="3">活动</a> --}}
            </div>
            <div id="feeds_list" class="feed_box"></div>
        </div>
    </div>

    <div class="right_box">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.profile.js') }}"></script>
<script>
setTimeout(function() {
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/collect',
        paramtype: 1,
        params: {cate: 1, limit: 10}
    });
}, 300);

$('.J-menu > a').on('click', function(){
    var cate = $(this).attr('cid');

    $('#feeds_list').html('');
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/collect',
        params: {cate: cate }
    });

    $('.J-menu a').removeClass('active');
    $(this).addClass('active');
});
</script>
@endsection