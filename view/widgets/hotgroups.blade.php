<div class="hot-groups">
    <div class="title">热门圈子</div>
    <ul class="hot-groups-list">
        @if(!$groups->isEmpty())
            @foreach($groups as $group)
                <li>
                    <div class="fans-span1">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>{{$loop->iteration}}
                        </span>
                    </div>
                    <div class="hot-content">
                        <a class="hot-group-title" href="{{ Route('pc:groupread', $group->id) }}">{{$group->title}}</a>
                        <div class="hot-group-count">
                            <span class="count">分享  {{$group->posts_count}}</span>
                            <span class="count">订阅  {{$group->members_count}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
        @else
            <div class="no-groups">暂无相关信息</div>
        @endif
    </ul>
</div>