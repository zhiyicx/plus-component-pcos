@if(!$users->isEmpty())
    @foreach($users as $user)
        <dl>
            <dt><img src="{{ $user['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=30" class="fans_img" /></dt>
            <dd>
                <div class="user-info">
                    <p>{{$user['name']}}</p>
                    <div class="extra-count">
                        <span>{{$user['extra']['answers_count'] or 0}}&nbsp;回答</span>
                        <span>{{$user['extra']['likes_count'] or 0}}&nbsp;点赞</span>
                    </div>
                    @if (count($user['tags']) > 0)
                        <ul>
                            @foreach($user['tags'] as $tag)
                                <li>{{$tag['name']}}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="invitation-a" data-id="{{$user['id']}}" data-name="{{$user['name']}}">邀请回答</div>
            </dd>
        </dl>
    @endforeach
@endif