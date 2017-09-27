
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/message.css')}}"/>
<div class="chat_dialog">
    <div class="chat_content">
        <div class="chat_left">
            <ul id="root_list">
                <li @if($type=='pl')class="current_room"@endif data-type="pl">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xuanzedui-copy-copy-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">评论的</span>
                        <div>缘分评论了我</div>
                    </div>
                </li>
                <li @if($type=='zan')class="current_room"@endif data-type="zan">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xihuande-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">赞过的</span>
                        <div>缘分赞了我</div>
                    </div>
                </li>
                <li @if($type=='tz')class="current_room"@endif data-type="tz">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xihuande-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">通知</span>
                        <div>ninin</div>
                    </div>
                </li>
                {{-- <li @if($type=='at')class="current_room"@endif data-type="at">
                    <div class="chat_left_icon">
                        <svg class="icon chat_img" aria-hidden="true">
                            <use xlink:href="#icon-xiangguande-copy"></use>
                        </svg>
                    </div>
                    <div class="left_class">
                        <span class="chat_span">提到我的</span>
                        <div>缘分提到了我</div>
                    </div>
                </li> --}}
                <li class="room_item">
                    <div class="chat_left_icon">
                        <img src="{{ $routes['resource'] }}/images/avatar.png" class="chat_img" />
                    </div>
                    <div class="left_class">
                        <span class="chat_span">仰光</span>
                        <div>今天周五啦</div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="chat_right message-body">
            <div class="body-title">评论的</div>
            <div class="message-content" id="message_content">

            </div>
            <span id="message_after" style="display:none">0</span>
            <a href="javascript:" id = 'ui'>12333</a>
        </div>
    </div>
</div>

<script src="{{ $routes['resource'] }}/js/jquery.min.js"></script>

<script type="text/javascript">
    $(function () {
        messageFlip();

        // 切换消息类型
        $('#root_list').on('click', 'li', function () {
            $(this).hasClass("current_room") || ($(this).addClass("current_room").siblings('.current_room').removeClass('current_room'));
            var type = $(this).data('type');
            getData(type, 10, 0);
        });

        $('#ui').click(function () {
            messageFlip();
        });

        /**
         * 滚动加载
         */
        function messageFlip() {
            // 当前类型
            var type = $('#root_list').find('.current_room').data('type');
            var after = $('#message_after').text();

            getData(type, 10, after);
        }

        /**
         * 获取消息列表
         * @param type
         * @param limit
         * @param offset
         */
        function getData(type, limit, after) {
            var url = '';
            var title = '评论的';
            var params = {
                'limit': limit ? limit : 10,
                'after': after ? after : 0
            };
            switch(type) {
                case 'pl': // 评论加载

                    title = '评论的';
                    url = '/webmessage/comments';
                    break;
                case 'zan': // 点赞加载

                    title = '点赞的';
                    url = '/webmessage/likes';
                    break;
                case 'tz': // 通知加载

                    title = '通知';
                    url = '/webmessage/notifications';
                    break;
            }
            $('.body-title').text(title);
            $.ajax({
                url: "{{$routes['siteurl']}}" + url,
                type: 'GET',
                data: params,
                dataType: 'json',
                error: function (xml) {
                },
                success: function (res) {
                    if (res.status) {
                        if (res.data.count == 0) {
                            noticebox('已无更多啦', 0);
                        } else {
                            $('#message_after').text(res.data.after);
                            $('.chat_content').find('#message_content').html(res.data.html);
                        }
                    }
                    return false;
                }
            });
        }

    });





















{{--$(function(){--}}
	{{--$('#root_list li').on('click', function(e){--}}
	{{--$('#root_list li').removeClass('current_room');--}}
	{{--$(this).addClass('current_room');--}}
	{{--if ($(this).hasClass('room_item')) {--}}
		{{--// 聊天室。。。--}}
	{{--} else {--}}
		{{--var type = $(this).data('type');--}}
		{{--getMsgBody(type);--}}
	{{--}--}}
{{--})--}}

{{--var getMsgBody = function(type){--}}
	{{--if (type) {--}}
		{{--$('.message-body').html(loadHtml);--}}
		{{--$.get('/webMessage/getBody/'+type, function(html) {--}}
			{{--var html = JSON.parse(html);--}}
			{{--$('.message-body').html(html);--}}
			{{--$("img.lazy").lazyload({effect: "fadeIn"});--}}
		{{--});--}}
	{{--}--}}
{{--};--}}
{{--getMsgBody('{{$type}}');--}}
{{--});--}}
</script>