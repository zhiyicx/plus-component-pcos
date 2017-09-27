@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
@endphp

@if(!$posts->isEmpty())
@foreach($posts as $key => $post)
<div class="feed_item" id="feed{{$post->id}}">
    <div class="feed_title">
        <a class="avatar_box" href="{{ route('pc:mine', $post->user->id) }}">
            <img class="avatar" src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50" />
            @if($post->user->verified)
            <img class="role-icon" src="{{ $post->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
            @endif
        </a>

        <a href="javascript:;">
            <span class="feed_uname font14">{{ $post->user->name }}</span>
        </a>
        <a href="{{ route('pc:grouppost', ['group_id' => $post->group_id, 'post_id' => $post->id]) }}" class="date">
            <span class="feed_time font12">{{ getTime($post->created_at) }}</span>
        </a>
    </div>

    <div class="post-title"><a href="{{ route('pc:grouppost', ['group_id' => $post->group_id, 'post_id' => $post->id]) }}">{{$post->title}}</a></div>

    <div class="feed_body">
        <p class="feed_text">{!! formatContent($post->content) !!}</p>

        @include('pcview::templates.feed_images')
    </div>

    <div class="feed_bottom">
        <div class="feed_datas">
            <span class="digg" id="J-likes{{$post->id}}" rel="{{$post->diggs}}" status="{{(int) $post->has_like}}">
                @if($post->has_like)
                <a href="javascript:void(0)" onclick="liked.init({{$post->id}}, 'group', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg>
                    <font>{{$post->diggs}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="liked.init({{$post->id}}, 'group', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                    <font>{{$post->diggs}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$post->id}}">{{$post->comments_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>{{$post->views}}
            </span>
            <span class="options">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
            </span>
            <div class="options_div">
                <ul>
                    <li id="J-collect{{$post->id}}" rel="0" status="{{(int) $post->has_collection}}">
                        @if($post->has_collection)
                        <a class="act" href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 1);" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 1);">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏
                        </a>
                        @endif
                    </li>
                    @if($post->user_id == $TS['id'])
                        <li>
                            <a href="javascript:;" onclick="post.delPost('{{$post->group_id}}', '{{$post->id}}');">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                            </a>
                        </li>
                    @endif
                </ul>
                <img src="{{ asset('zhiyicx/plus-component-pc/images/triangle.png') }}" class="triangle" />
            </div>
        </div>

        <div class="comment_box" style="display: none;">
            <div class="comment_line">
                <img src="{{ asset('zhiyicx/plus-component-pc/images/line.png') }}" />
            </div>
            <div class="comment_body" id="comment_box{{$post->id}}">
                <div class="comment_textarea">
                    <textarea class="comment-editor" id="J-editor{{$post->id}}" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                    <div class="comment_post">
                        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                        <a class="btn btn-primary fr" id="J-button{{$post->id}}" onclick="post.addComment({{$post->id}}, {{$post->group_id}}, 1)"> 评 论 </a>
                    </div>
                </div>
                <div id="J-commentbox{{ $post->id }}">
                    @if($post->comments->count())
                        @foreach($post->comments as $cv)
                            <p class="comment_con" id="comment{{$cv->id}}">
                                <span class="tcolor">{{ $cv->user['name'] }}：</span> {{$cv->body}}
                                @if($cv->user_id != $TS['id'])
                                    <a onclick="comment.reply('{{$cv['user']['id']}}', {{$cv['commentable_id']}}, '{{$cv['user']['name']}}')">回复</a>
                                @else
                                    <a class="comment_del" onclick="comment.delete({{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">删除</a>
                                @endif
                            </p>
                        @endforeach
                    @endif
                </div>
                @if($post->comments->count() >= 5)
                <div class="comit_all font12"><a href="{{Route('pc:grouppost', [$post->group_id, $post->id])}}">查看全部评论</a></div>
                @endif
            </div>
        </div>
        <div class="feed_line"></div>
    </div>
</div>
<script type="text/javascript">
    layer.photos({
        photos: '#layer-photos-demo{{$post->id}}'
        ,anim: 0
        ,move: false
    });
</script>
@endforeach
@endif
