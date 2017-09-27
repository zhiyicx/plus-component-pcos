@foreach ($group as $item)
    <div class="group_item @if(($loop->iteration) % 2 == 0) group_item_right @endif">
        <dl class="clearfix">
            <dt><a href="{{Route('pc:groupread', $item->id)}}"><img src="{{ $routes['storage'].$item->avatar->id }}" width="120" height="120"></a></dt>
            <dd>
                <a class="title" href="{{Route('pc:groupread', $item->id)}}">{{ $item->title }}</a>
                <div class="tool">
                    <span>分享 <font class="mcolor">{{ $item->posts_count }}</font></span>
                    <span>订阅 <font class="mcolor" id="join-count-{{ $item->id }}">{{ $item->members_count }}</font></span>
                </div>
                <div class="join">
                    @if ($item->is_member)
                        <button class="J-join joined" gid="{{ $item->id }}"  status="1">已加入</button>
                    @else
                        <button class="J-join" gid="{{ $item->id }}"  status="0">+加入</button>
                    @endif
                </div>
            </dd>
        </dl>
    </div>
@endforeach