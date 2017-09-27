@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp
@foreach($news as $item)
<div class="news_item">
     <div class="news_img">
          <a href="{{ route('pc:newsread', ['news_id' => $item['id']]) }}">
               <img class="lazy" width="230" height="163" data-original="{{ getImageUrl($item['image'], 230, 163)}}"/>
          </a>
     </div>
     <div class="news_word">
          <a href="{{ route('pc:newsread', ['news_id' => $item['id']]) }}">
               <div class="news_title"> {{ $item['title'] }} </div>
          </a>
          <p>{{ $item['subject'] }}</p>
          <div class="news_bm">
               @if ($cate_id == 0)
                    <a href="javascript:;" class="cates_span">{{ $item['category']['name'] }}</a>
               @endif
               <span>{{ $item['from'] }}  ·  {{ $item['hits'] }}浏览  ·  {{ getTime($item['created_at'], 1) }}</span>
          </div>
     </div>
</div>
@endforeach