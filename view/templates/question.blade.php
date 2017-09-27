
@if (!$data->isEmpty())
@foreach ($data as $post)
    <div class="q_c">
        <h2 class="q_title">
            <a href="{{ route('pc:questionread', ['question_id' => $post['id']]) }}">{{ $post->subject }}</a>
        </h2>
        @if ($post->answer)
            <div class="q-answer">
                <div class="q_user">
                @if ($post->anonymity)
                    <div class="q_user_info">匿名</div>
                @else
                    <img src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=24" >
                    <div class="q_user_info">
                        <span>{{ $post->user->name }}</span>
                        @foreach ($post->user->tags as $tag)
                             <div>{{$tag->name}}</div>
                         @endforeach
                    </div>
                    <span class="q_time">{{ $post->answer->created_at }}</span>
                @endif
                </div>
                <div class="q_detail clearfix">
                    {{-- <div class="q_img">
                        <div class="img_wrap">
                            <img src="http://blog.jsonleex.com/icon/LX.png" >
                        </div>
                    </div> --}}
                    <div class="q_text">
                        <span>{{ str_limit($post->answer->body, 250) }}</span>
                        <button class="Button Button--plain Button--more">查看详情</button>
                    </div>
                </div>
            </div>
        @endif
        <div class="q_action">
            <button class="Button Button--plain">
                <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                    <title></title>
                    <g>
                        <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                    </g>
                </svg>
                {{ $post->watchers_count }} 关注
            </button>
            <button class="Button Button--plain">
                <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                    <title></title>
                    <g>
                        <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                    </g>
                </svg>
                {{ $post->answers_count }} 条回答
            </button>
            <button class="Button Button--plain">
                <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                    <title></title>
                    <g>
                        <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                    </g>
                </svg>
               {{ $post->amount/100 }}
            </button>
            <button class="Button Button--plain">
                <svg viewBox="0 0 18 4" class="Icon" width="20" height="20" aria-hidden="true">
                    <title></title>
                    <g>
                        <g>
                            <circle cx="2" cy="2" r="2"></circle>
                            <circle cx="9" cy="2" r="2"></circle>
                            <circle cx="16" cy="2" r="2"></circle>
                        </g>
                    </g>
                </svg>
            </button>
        </div>
    </div>
@endforeach
@endif
