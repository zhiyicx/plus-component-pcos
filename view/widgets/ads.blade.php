<!-- 右侧广告位 -->
@if ($type == 1)

    @if(!$ads->isEmpty())
        @foreach($ads as $ad)
        <div class="news_ad">
            <a href="{{ $ad['link'] }}" target="_blank">
                <img src="{{ $ad['image'] }}" />
            </a>
        </div>
        @endforeach
    @endif

<!-- 资讯顶部广告 -->
@elseif ($type == 2)

    @if(!$ads->isEmpty())
        <div class="unslider">
            <ul>
                @foreach($ads as $ad)
                  <li>
                    <a href="{{ $ad['link'] }}">
                        <img src="{{ $ad['image'] }}" width="100%" height="414">
                    </a>
                    @if ($ad['title'])
                        <p class="title">{{ $ad['title'] }}</p>
                    @endif
                  </li>
                @endforeach
            </ul>
        </div>
    @endif

@endif