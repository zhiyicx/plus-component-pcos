@section('title')
    {{ $post->title }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="feed_left">

            <div class="post-title">{{$post->title}}</div>
            <div class="detail_user">
                <div class="detail_user_header">
                    <a href="#">
                        <img src="{{ $post['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" alt="">
                    </a>
                </div>
                <div class="detail_user_info">
                    <div class="detail_user_name"><a href="#">{{ $post->user->name }}</a></div>
                    <div class="detail_time">{{ getTime($post['created_at']) }}</div>
                </div>
            </div>

            @if($post->images)
                <div class="detail_images" id="layer-photos-demo">
                    @foreach($post->images as $store)
                        <img data-original="{{ $routes['storage']}}{{$store['id'] }}?w=675&h=380" class="lazy img-responsive"/>
                    @endforeach
                </div>
            @endif

            <div class="detail_body">
                {!!$post->content!!}
            </div>

            <div class="detail_share">
                <span id="J-collect{{ $post->id }}" rel="{{ $post->collections }}" status="{{(int) $post->has_collection}}">
                    @if($post->has_collection)
                    <a class="act" href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                        <font class="cs">{{ $post->collections }}</font> 人收藏
                    </a>
                    @else
                    <a href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                        <font class="cs">{{ $post->collections }}</font> 人收藏
                    </a>
                    @endif
                </span>
                <span class="digg" id="J-likes{{$post->id}}" rel="{{$post->diggs}}" status="{{(int) $post->has_like}}">
                    @if($post->has_like)
                    <a class="act" href="javascript:void(0)" onclick="liked.init({{$post->id}}, 'group', 0)">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                        <font>{{$post->diggs}}</font> 人喜欢
                    </a>
                    @else
                    <a href="javascript:;" onclick="liked.init({{$post->id}}, 'group', 0)">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                        <font>{{$post->diggs}}</font> 人喜欢
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
            <div class="detail_comment">
                <div class="comment_title"><span class="comment_count cs{{$post->id}}"">{{$post['comments_count']}} </span>人评论</div>
                <div class="comment_box">
                    <textarea
                            class="comment_editor"
                            id="J-editor{{$post->id}}"
                            placeholder="说点什么吧"
                            onkeyup="checkNums(this, 255, 'nums');"
                    ></textarea>
                    <div class="comment_tool">
                        <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                        <button
                            class="btn btn-primary"
                            id="J-button{{$post->id}}"
                            onclick="post.addComment({{$post->id}}, {{$post->group_id}}, 0)"
                        > 评 论 </button>
                    </div>
                </div>
                <div class="comment_list J-commentbox" id="J-commentbox{{$post->id}}">

                </div>
            </div>
        </div>
    </div>

    <div class="right_container">
        <div class="right_about">
            <div class="info clearfix">
                <div class="auth_header">
                    <a href="#">
                        <img src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png')}}" />
                    </a>
                </div>
                <div class="auth_info">
                    <div class="info_name">
                        <a href="#">{{ $post->user->name }}</a>
                    </div>
                    <p class="info_bio">{{ $post->user->bio or '暂无简介' }}</p>
                </div>
            </div>
            <ul class="auth_fans">
                <li>粉丝<a href="javascript:;">{{ $post->user->extra->followers_count }}</a></li>
                <li>关注<a href="javascript:;">{{ $post->user->extra->followings_count }}</a></li>
            </ul>
        </div>
        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')
        <!-- 收入达人 -->
        @include('pcview::widgets.incomerank')

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.group.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
    <script type="text/javascript">
        layer.photos({
            photos: '#layer-photos-demo'
            ,anim: 5
            ,move: false
        });

        setTimeout(function() {
            scroll.init({
                container: '.J-commentbox',
                loading: '.feed_left',
                url: '/group/{{$post['group_id']}}/post/{{$post['id']}}/comments' ,
                canload: true
            });
        }, 300);

        $(document).ready(function(){
            $("img.lazy").lazyload({effect: "fadeIn"});
            bdshare.addConfig('share', {
                "tag" : "share_feedlist",
                'bdText' : '{{$post['title']}}',
                'bdDesc' : '{{$post['content']}}',
                'bdUrl' : window.location.href,
                'bdPic': "{{count($post['images']) > 0 ? $routes['storage'].$post['images'][0]['id'] : ''}}"
            });
        });


    </script>
@endsection
