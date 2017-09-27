@section('title')
    {{ $news['title'] }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/markdown/css/editormd.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="news_left">
            <div class="detail_body">
                <div class="detail_title">
                    {{ $news['title'] }}
                </div>

                <div class="detail_info feed_datas" id="news_toolbar">
                    <a href="{{ route('pc:news', ['cate_id' => $news['category']['id']]) }}" class="cates_span">{{ $news['category']['name'] or '默认' }}</a>
                    <span>{{ $news['from'] != '原创' ? $news['from'] : $news['user']['name'] }}  ·  {{ $news['hits'] }}浏览  ·  {{ getTime($news['created_at']) }}</span>
                    <span class="options">
                        <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                    </span>
                    <div class="options_div">
                    <ul>
                        @if(isset($TS->id) && $news->user->id == $TS->id)
                        <li>
                            <a href="javascript:;" onclick="news.pinneds({{$news->id}});">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhiding-copy-copy1"></use></svg>申请置顶
                            </a>
                        </li>
                        @endif
                    </ul>
                    <img src="{{ asset('zhiyicx/plus-component-pc/images/triangle.png') }}" class="triangle" />
                    </div>
                </div>

                @if($news['subject'])
                    <div class="detail_subject">
                        <img src="{{ asset('zhiyicx/plus-component-pc/images/zixun-left.png') }}"/>
                        <div class="subject_content">{{ $news['subject'] }}</div>
                        <img src="{{ asset('zhiyicx/plus-component-pc/images/zixun-right.png') }}" class="right"/>
                    </div>
                @endif

                <div class="detail_content markdown-body editormd-preview-container">
                {!! Parsedown::instance()->setMarkupEscaped(true)->text($news->content) !!}
                </div>

                <div class="detail_share">
                    <span id="J-collect{{ $news->id }}" rel="{{ $news->collect_count }}" status="{{(int) $news->has_collect}}">
                        @if($news->has_collect)
                        <a class="act" href="javascript:;" onclick="collected.init({{$news->id}}, 'news', 0);" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                            <font class="cs">{{ $news->collect_count }}</font> 人收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collected.init({{$news->id}}, 'news', 0);">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                            <font class="cs">{{ $news->collect_count }}</font> 人收藏
                        </a>
                        @endif
                    </span>
                    <span class="digg" id="J-likes{{$news->id}}" rel="{{$news->digg_count}}" status="{{(int) $news->has_like}}">
                        @if($news->has_like)
                        <a class="act" href="javascript:void(0)" onclick="liked.init({{$news->id}}, 'news', 0)">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                            <font>{{$news->digg_count}}</font> 人喜欢
                        </a>
                        @else
                        <a href="javascript:;" onclick="liked.init({{$news->id}}, 'news', 0)">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                            <font>{{$news->digg_count}}</font> 人喜欢
                        </a>
                        @endif
                    </span>
                    <div class="del_share bdsharebuttonbox share_feedlist clearfix" data-tag="share_feedlist">
                        分享至：
                        <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                        <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                        <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
                    </div>
                </div>

                {{-- 相关推荐 --}}
                @if (!$news_rel->isEmpty())
                <div class="detail_recommend">
                    <p class="rel_title">相关推荐</p>
                    <div class="rel_tags">
                        @foreach ($news->tags as $tag)
                        <span>{{ $tag->name }}</span>
                        @endforeach
                    </div>

                    @foreach ($news_rel as $rel)
                    <div class="rel_news_item clearfix">
                         <div class="rel_news_img">
                              <a href="{{ route('pc:newsread', ['news_id' => $rel['id']]) }}">
                                   <img class="lazy" width="180" height="130" data-original="{{ getImageUrl($rel['image'], 180, 130)}}"/>
                              </a>
                         </div>
                         <div class="rel_news_word">
                              <a href="{{ route('pc:newsread', ['news_id' => $rel['id']]) }}">
                                   <div class="news_title"> {{ $rel['title'] }} </div>
                              </a>
                              <p>{{ $rel['subject'] }}</p>
                              <div class="news_bm">
                                   <a href="{{ route('pc:news', ['cate_id' => $rel['category']['id']]) }}" class="cates_span">{{ $rel['category']['name'] or '默认'}}</a>
                                   <span>{{ $rel['from'] }}  ·  {{ $rel['hits'] }}浏览  ·  {{ getTime($rel['created_at']) }}</span>
                              </div>
                         </div>
                    </div>
                    @endforeach
                </div>
                @endif



                {{-- 评论  --}}
                <div class="detail_comment">
                    <div class="comment_title"><span class="comment_count cs{{$news->id}}">{{$news->comment_count}}</span>人评论</div>
                    <div class="comment_box">
                        <textarea
                            class="comment_editor"
                            id="J-editor{{$news->id}}"
                            placeholder="说点什么吧"
                            onkeyup="checkNums(this, 255, 'nums');"
                        ></textarea>
                        <div class="comment_tool">
                            <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                            <button
                                class="btn btn-primary"
                                id="J-button{{$news->id}}"
                                onclick="news.addComment({{$news->id}}, 0)"
                            > 评 论 </button>
                        </div>
                    </div>
                    <div class="comment_list J-commentbox" id="J-commentbox{{$news->id}}">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right_container">
        <div class="news_release_btn">
            <a href="{{ route('pc:newsrelease') }}">
                <span>
                    <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-feiji"></use></svg>投稿
                </span>
            </a>
        </div>
        {{-- 近期热点 --}}
        @include('pcview::widgets.hotnews')

        {{-- 广告位 --}}
        @include('pcview::widgets.ads', ['space' => 'pc:news:right', 'type' => 1])
    </div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.news.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/markdown/lib/marked.js') }}"></script>
<script>
$(function(){
    $("img.lazy").lazyload({effect: "fadeIn"});

    setTimeout(function() {
        scroll.init({
            container: '.J-commentbox',
            loading: '.detail_comment',
            url: '/news/{{$news->id}}/comments'
        });
    }, 300);

    // 近期热点
    if($('.time_menu li a').length > 0) {
        $('.time_menu li').hover(function() {
            var type = $(this).attr('type');

            $(this).siblings().find('a').removeClass('hover');
            $(this).find('a').addClass('hover');

            $('.hot_news_list div').hide();
            $('#' + type).show();
        })
    }

    bdshare.addConfig('share', {
        "tag" : "share_feedlist",
        'bdText' : '{{ $news['title'] }}',
        'bdDesc' : '{{ $news['title'] }}',
        'bdUrl' : window.location.href,
        'bdPic': "{{count($news['image']) > 0 ? $routes['storage'].$news['image']['id'] : ''}}"
    });
});

</script>
@endsection
