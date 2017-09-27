<div class="answer-rank">
    <div class="title">问答达人排行</div>
    <div class="rank-tab" id="J-rank-tab">
        <span class="active" type="day">今日</span>
        <span type="week">一周</span>
        <span type="month">本月</span>
    </div>

    {{-- 天排行榜 --}}
    <ul class="answer-rank-list" id="J-tab-day">
        @if(!$qrank['day']->isEmpty())
            @foreach($qrank['day'] as $day)
                <li>
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>
                            {{ $day['extra']['rank'] }}
                        </span>
                    </div>
                    <div class="rank-avatar">
                        <img src="{{ $day['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60" width="60" height="60" />
                    </div>
                    <div class="rank-info">
                        <span class="tcolor">{{ $day['name'] }}</span>
                        <span class="ucolor txt-hide">回答数：{{ $day['extra']['count'] }}</span>
                    </div>
                </li>
            @endforeach
        @else
            <div class="no-data">暂无相关信息</div>
        @endif
    </ul>

    {{-- 周排行榜 --}}
    <ul class="answer-rank-list" id="J-tab-week" style="display: none;">
        @if(!$qrank['week']->isEmpty())
            @foreach($qrank['week'] as $week)
                <li>
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>
                            {{ $week['extra']['rank'] }}
                        </span>
                    </div>
                    <div class="rank-avatar">
                        <img src="{{ $week['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60" width="60" height="60" />
                    </div>
                    <div class="rank-info">
                        <span class="tcolor">{{ $week['name'] }}</span>
                        <span class="ucolor txt-hide">回答数：{{ $week['extra']['count'] }}</span>
                    </div>
                </li>
            @endforeach
        @else
            <div class="no-data">暂无相关信息</div>
        @endif
    </ul>

    {{-- 月排行榜 --}}
    <ul class="answer-rank-list" id="J-tab-month" style="display: none;">
        @if(!$qrank['month']->isEmpty())
            @foreach($qrank['month'] as $month)
                <li>
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>
                            {{ $month['extra']['rank'] }}
                        </span>
                    </div>
                    <div class="rank-avatar">
                        <img src="{{ $month['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60" width="60" height="60" />
                    </div>
                    <div class="rank-info">
                        <span class="tcolor">{{ $month['name'] }}</span>
                        <span class="ucolor txt-hide">回答数：{{ $month['extra']['count'] }}</span>
                    </div>
                </li>
            @endforeach
        @else
            <div class="no-data">暂无相关信息</div>
        @endif
    </ul>
</div>

<script>
$('#J-rank-tab > span').hover(function(){
    $('#J-rank-tab > span').removeClass('active');
    $(this).addClass('active');

    $('.answer-rank-list').hide();
    $('#J-tab-'+$(this).attr('type')).show();
})
</script>