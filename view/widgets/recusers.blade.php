@if (!$users->isEmpty())
<div class="recusers">
    <ul>
        @foreach ($users as $user)
        <li>
            <a href="{{ route('pc:mine', $user['id']) }}">
                <img src="{{ $user['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50"/>
            </a>
            <span>
                <a href="{{ route('pc:mine', $user['id']) }}">{{ $user['name'] }}</a>
            </span>
        </li>
        @endforeach
    </ul>
    @if ($users->count() == 9)
    <a class="recmore" href="{{ route('pc:users', ['type'=>3]) }}">更多推荐用户</a>
    @endif
</div>
@endif