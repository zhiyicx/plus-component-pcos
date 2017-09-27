@if($incomes->isEmpty())
<div class="income-rank">
    <div class="title">收入达人排行榜</div>
    <ul class="income-list">
            @foreach($incomes as $income)
                <li>
                    <div class="fans-span">{{$loop->iteration}}</div>
                    <div class="income-avatar">
                        <img src="{{ $income['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60" alt="{{$income['name']}}">
                    </div>
                    <div class="income-name">
                        <a class="name" href="javascript:;">{{$income['name']}}</a>
                        <div class="answers-count">回答数：{{$income['extra']['answers_count']}}</div>
                    </div>
                </li>
            @endforeach
    </ul>
</div>
@endif