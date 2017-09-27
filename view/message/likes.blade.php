@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if (!$likes->isEmpty())
    @foreach($likes as $like)
        <dl class="message-one">
            <dt><img src="{{$like['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=40}}"></dt>
            <dd>
                <div class="one-title"><a href="/profile/{{$like['user']['id']}}">{{$like['user']['name']}}</a>{{$like['source_type']}}</div>
                <div class="one-date">{{ getTime($like['created_at']) }}</div>

                <a href="{{$like['source_url']}}" class="one-cotent">
                    <div class="feed-content">
                        @if(isset($like['source_img']))
                            <div class="con-img">
                                <img src="{{$like['source_img']}}">
                            </div>
                        @endif
                        <div class="con-con">{{$like['source_content']}}</div>
                    </div>
                </a>
            </dd>
        </dl>
    @endforeach
@else
    暂无更多
@endif