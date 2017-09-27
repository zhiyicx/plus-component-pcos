@foreach ($users as $data)
    <div class="user_item @if(($loop->iteration) % 2 == 0) user_item_right @endif">
        <div class="user_header">
            <a class="avatar_box" href="{{route('pc:mine',['user_id'=>$data['id']])}}">
                <img src="{{ $data['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60" class="user_avatar" alt="{{ $data['name'] }}"/>
                @if($data->verified)
                <img class="role-icon" src="{{ $post->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                @endif
            </a>
        </div>
        <div class="user_body">
                <a href="{{route('pc:mine',['user_id'=>$data['id']])}}">
                    <span class="user_name">{{ $data['name'] or $data['phone'] }}</span>
                </a>
                @if ($data['follower'])
                <span id="data" class="follow_btn followed" uid="{{ $data['id'] }}" status="1">已关注</span>
                @else
                <span id="data" class="follow_btn" uid="{{ $data['id'] }}" status="0">+关注</span>
                @endif
            <div class="user_subtitle">{{ $data['bio'] or '这家伙很懒，什么都没留下'}}</div>
            <div class="user_number">
                <span class="user_num">粉丝<span>{{ $data['extra']['followers_count'] or 0}}</span></span>
                <span class="user_num right">关注<span>{{ $data['extra']['followings_count'] or 0}}</span></span>
            </div>
        </div>
    </div>
@endforeach