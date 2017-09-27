@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if (!$comments->isEmpty())
    @foreach($comments as $comment)
        <dl class="message-one">
            <dt><img src="{{$comment['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=40}}"></dt>
            <dd>
                <div class="one-title"><a href="/profile/{{$comment['user']['id']}}">{{$comment['user']['name']}}</a>{{$comment['source_type']}}</div>
                <div class="one-date">{{ getTime($comment['created_at']) }}</div>

                <a href="{{$comment['source_url']}}" class="one-cotent">
                    <div class="content-comment">{{$comment['body']}}</div>
                    <div class="feed-content">
                        @if(isset($comment['source_img']))
                            <div class="con-img">
                                <img src="{{$comment['source_img']}}">
                            </div>
                        @endif
                        <div class="con-con">{{$comment['source_content']}}</div>
                    </div>
                </a>
            </dd>
        </dl>
    @endforeach
@else
    暂无更多
@endif