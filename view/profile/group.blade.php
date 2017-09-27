@section('title')
{{ $user->name }}的个人主页
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="collect_box">
        {{-- 收藏列表 --}}
        <div class="feed_content">
            <div class="feed_menu J-menu">
                <a class="active" href="javascript:;" cid="1">@if ($TS->id == $user->id) 我加入的 @else TA加入的 @endif</a>
            </div>
            <div id="feeds_list" class="feed_box clearfix"></div>
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
        url: '/profile/group',
        params: {user: {{$user->id}} }
    });
}, 300);

$('#feeds_list').on('click', '.J-join', function(){

    if (MID == 0) {
        window.location.href = '/passport/login';
        return;
    }
    var _this = this;
    var status = $(this).attr('status');
    var group_id = $(this).attr('gid');
    group(status, group_id, function(){
        if (status == 1) {
            $(_this).text('+关注');
            $(_this).attr('status', 0);
            $(_this).removeClass('joined');
        } else {
            $(_this).text('已加入');
            $(_this).attr('status', 1);
            $(_this).addClass('joined');
        }
    });
});

</script>
@endsection