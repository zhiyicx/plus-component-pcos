@if($type == 'pl')
<div class="message_comment">
    <div class="chat_title">
        评论的
    </div>
    <div class="comment_list">
        @if($list->count())
        @foreach($list as $val) 
            <div class="com_list">
                <div class="com_img"><a href="/index/{{$val['user_id']}}" target="_blank"><img src="{{$val['user']['avatar']}}" /></a></div>
                <div class="com_center">
                <a href="{{$val['source_url'] or 'javascript:;'}}">
                    <span class="com_name">{{$val['user']['name']}}</span>
                    <div class="com_subtitle">{{$val['comment_content']}}</div>
                    <div class="com_time">{{$val['created_at']}}</div>
                </a>
                </div>
                <div class="com_right">
                    <a href="{{$val['source_url'] or 'javascript:;'}}">
                    <img class="lazy comR_img" data-original="@if($val['source_img']){{$routes['storage']}}{{$val['source_img']}} @endif" />
                    </a>
                </div>
            </div>
        @endforeach
        @else
            <div class="no_data_div"><div class="no_data"><img src="{{$routes['resource']}}/images/pic_default_people.png" /><p>暂无评论消息</p></div></div>
        @endif        
    </div>
</div>
@elseif($type == 'zan')
<div class="message_comment">
    <div class="chat_title">
        点赞的
    </div>
    <div class="comment_list">
        @if($list->count())
        @foreach($list as $val) 
            <div class="com_list">
                <div class="com_img zan_img"><a href="/index/{{$val['user_id']}}" target="_blank"><img src="{{$val['info']['avatar']}}" /></a></div>
                <div class="com_center zan_center">
                    <span class="com_name">{{$val['info']['name']}}</span>
                    <div class="com_time zan_time">{{$val['created_at']}}</div>
                </div>
                <div class="com_right">
                    <i class="icon iconfont icon-xihuan-red"></i>
                    <img class="lazy comR_img" data-original="@if($val['source_cover']){{$routes['storage']}}{{$val['source_cover']}}?w=80&h=80 @endif" />
                </div>
            </div>
        @endforeach
        @else
            <div class="no_data_div"><div class="no_data"><img src="{{$routes['resource']}}/images/pic_default_people.png" /><p>暂无点赞消息</p></div></div>
        @endif
    </div>
</div>
@endif
<div>{{ $page }}</div>
<script>
$('.dy_pageCont>a').on('click', function(e){
    e.preventDefault();
    if (!$(this).hasClass('page_cur')) {
        var link = $(this).attr('href');
        $.get(link, function(html) {
            var html = JSON.parse(html);
            $('.message-body').html(html);
            $("img.lazy").lazyload({effect: "fadeIn"});
        });            
    }
})
</script>