@section('title') {{ $user->name }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/question.css') }}"/>
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">

    <div class="collect_box">
        <div class="feed_content">
            <div class="feed_menu J-menu">
                <div data-value="1" class="zy_select t_c gap12 mr20" id="J-question">
                    <span class="qa_opt">全部问题</span>
                    <ul>
                        <li data-value="all" class="active">全部提问</li>
                        <li data-value="invitation">邀请提问</a></li>
                        <li data-value="reward">悬赏提问</a></li>
                        <li data-value="other">其他提问</a></li>
                    </ul>
                    <i></i>
                </div>
                <div data-value="1" class="zy_select t_c gap12 mr20" id="J-answer">
                    <span class="qa_opt">我的回答</span>
                    <ul>
                        <li data-value="all" class="active">全部</li>
                        <li data-value="adoption">被采纳</a></li>
                        <li data-value="invitation">被邀请</a></li>
                        <li data-value="other">其他</a></li>
                    </ul>
                    <i></i>
                </div>
                <a class="qa_opt ucolor" href="javascript:;" data-value="3">关注的问题</a>
                <a class="qa_opt ucolor" href="javascript:;" data-value="4">关注的话题</a>
            </div>
            <div id="feeds_list" class="feed_box follow_topic clearfix">

            </div>
        </div>
    </div>

    <div class="right_box">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script>
setTimeout(function() {
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/question',
        params: {type: 'all' }
    });
}, 300);

$('#J-question li').on('click', function(){
    var type = $(this).data('value');
    $('#feeds_list').html('');
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/question',
        params: {type: type, cate: 1 }
    });
});
$('#J-answer li').on('click', function(){
    var type = $(this).data('value');
    $('#feeds_list').html('');
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/question',
        params: {type: type, cate: 2 }
    });
});

$('.J-menu a').on('click', function(){
    var type = $(this).data('value');
    $('#feeds_list').html('');
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/question',
        params: {cate: type }
    });

    $('.qa_opt').removeClass('active');
    $(this).addClass('active');
});

$('#feeds_list').on('click', '.J-follow', function(){
    checkLogin();
    var _this = this;
    var status = $(this).attr('status');
    var topic_id = $(this).attr('tid');
    topic(status, topic_id, function(){
        if (status == 1) {
            $(_this).text('+关注');
            $(_this).attr('status', 0);
            $(_this).removeClass('followed');
        } else {
            $(_this).text('已关注');
            $(_this).attr('status', 1);
            $(_this).addClass('followed');
        }
    });
});

</script>
@endsection