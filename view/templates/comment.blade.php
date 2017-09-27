@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if (!$comments->isEmpty())
@foreach ($comments as $comment)
<div class="comment_item" id="comment{{$comment['id']}}">
    <dl class="clearfix">
        <dt>
            <a href="{{ route('pc:mine', $comment['user']['id']) }}">
                <img src="{{ $comment['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50" width="50">
            </a>
        </dt>
        <dd>
            <a href="{{ route('pc:mine', $comment['user']['id']) }}"><span class="reply_name">{{$comment['user']['name']}}</span></a>
            <div class="reply_tool feed_datas">
                <span class="reply_time">{{ getTime($comment['created_at']) }}</span>
                <span class="reply_action options">
                    <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                </span>
                <div class="options_div">
                    <ul>
                        @if ($comment['user']['id'] == $TS['id'])
                            <li>
                                <a href="javascript:;" onclick="comment.delete('{{$comment['commentable_type']}}', {{$comment['commentable_id']}}, {{$comment['id']}});">
                                    <svg class="icon"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                                </a>
                            </li>
                        @endif
                        @if ($comment['user']['id'] != $TS['id'])
                            <li>
                                <a href="javascript:;" onclick="comment.reply('{{$comment['user']['id']}}', {{$comment['commentable_id']}}, '{{$comment['user']['name']}}');">
                                    <svg class="icon"><use xlink:href="#icon-shanchu-copy1"></use></svg>回复
                                </a>
                            </li>
                        @endif
                    </ul>
                    <img src="http://tss.io/zhiyicx/plus-component-pc/images/triangle.png" class="triangle">
                </div>
            </div>
            <div class="replay_body">{{$comment['body']}}</div>
        </dd>
    </dl>
</div>
@endforeach
@endif

