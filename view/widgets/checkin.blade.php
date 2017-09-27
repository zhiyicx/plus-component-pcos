@if (!empty($data))
<div class="checkin_cont">
    <div class="checkin_user">
        <span>
            {{$TS['name']}}
        </span>
        <a class="avatar" href="{{ route('pc:mine') }}">
            <img class="round" src="{{ $TS['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=100"/>
            @if($TS->verified)
                <img class="role-icon" src="{{ $TS->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
            @endif
        </a>
    </div>
    @if(!$data['checked_in'])
    <div class="checkin_div" onclick="checkIn({{ $data['checked_in'] }}, {{ $data['last_checkin_count'] }});" id="checkin">
        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>每日签到
    </div>
    @else
    <div class="checkin_div checked_div">
        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>
        已签到<span>连续签到<font class="colnum">{{ $data['last_checkin_count'] }}</font>天</span>
    </div>
    @endif
</div>
@endif