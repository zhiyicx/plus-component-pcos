@section('title')
	搜索 {{ $keywords }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/user.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/search.css') }}"/>
@endsection

@section('content')
    <div class="left_container clearfix">
        <div class="search_container">
            <div class="search_nav clearfix">
                <ul class="search_menu">
                    <li><a href="javascript:;" @if($type == 1) class="selected" @endif type="1">动态</a></li>
                    <!-- <li>
                        <div data-value="" class="zy_select t_c gap12">
                            <span>问答</span>
                            <ul>
                                <li data-value="user">话题</li>
                            </ul>
                            <i></i>
                        </div>
                    </li> -->
                    <li><a href="javascript:;" @if($type == 3) class="selected" @endif type="3">文章</a></li>
                    <li><a href="javascript:;" @if($type == 4) class="selected" @endif type="4">用户</a></li>
                    <li><a href="javascript:;" @if($type == 5) class="selected" @endif type="5">圈子</a></li>
                </ul>

                <div class="search_box">
                    <input class="search_input" type="text" placeholder="输入关键词搜索" value="{{ $keywords or ''}}" id="search_input"/>
                    <a class="search_icon">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-sousuo"></use></svg>
                    </a>
                </div>
            </div>

            <div class="clearfix" id="content_list">
            </div>
        </div>
    </div>

    <div class="right_container">
        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')

        <!-- 近期热点 -->
        @include('pcview::widgets.hotnews')
    </div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.weibo.js') }}"></script>
<script type="text/javascript">
$(function() {
    var type = '{{ $type }}';
    var keywords = '{{ $keywords }}';
    switchType(type);

    // 用户关注
    $('#content_list').on('click', '.follow_btn', function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = $(this).attr('uid');
        follow(status, user_id, _this, afterdata);
    })

    // 问答话题切换
    var select = $(".zy_select");

    select.on("click", function(e){
        e.stopPropagation();
        return !($(this).hasClass("open")) ? $(this).addClass("open") : $(this).removeClass("open");
    });

    select.on("click", "li", function(e){
        e.stopPropagation();
        var $this = $(this).parent("ul");
        if ($(this).html() == '问答') {
            $this.prev('span').html('问答');
            $this.parent(".zy_select").data("value", '问答');
            $(this).html('话题');
        } else {
            $this.prev('span').html('话题');
            $this.parent(".zy_select").data("value", '话题');
            $(this).html('问答');
        }

        $this.parent(".zy_select").removeClass("open");
    });

    $(document).click(function() {
        select.removeClass("open");
    });

    // 导航切换
    $('.search_menu a').click(function(){
        type = $(this).attr('type');
        $(this).parents('ul').find('a').removeClass('selected');
        $(this).addClass('selected');
        switchType(type);
    })

    // 搜索点击
    $('.search_icon').click(function(){
        var val = $('#search_input').val();
        keywords = val;
        switchType(type);
    })

    function switchType(type) {
        $('#content_list').html('');

        switch(type) {
            case '1': // 动态加载
                var params = {
                    type: type,
                    limit: 10,
                    keywords: keywords
                };
                scroll.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params
                });
                break;

            case '3': // 资讯加载
                var params = {
                    type: type,
                    limit: 10,
                    keywords: keywords
                };
                scroll.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params
                });
                break;

            case '4': // 用户加载
                var params = {
                    type: type,
                    limit: 10,
                    keywords: keywords
                };
                scroll.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params,
                    paramtype: 1
                });
                break;

            case '5': // 圈子加载
                var params = {
                    type: type,
                    keywords: keywords
                };
                scroll.init({
                    container: '#content_list',
                    loading: '.search_container',
                    url: '/search/data',
                    params: params,
                });
                break;
        };
    }

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.removeClass('followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.addClass('followed');
        }
    }

});

</script>
@endsection
