@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if (!$comments->isEmpty())
    @foreach($comments as $comment)
        <dl class="message-one">
            <dt><img src="{{$comment['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=40}}"></dt>
            <dd>
                <div class="one-title"><a href="/profile/{{$comment['user']['id']}}">{{$comment['user']['name']}}</a></div>
                <div class="one-date">{{ getTime($comment['created_at']) }}</div>

                <div class="top-comment">对你的文章进行了“<sapn>{{$comment['comment']['body']}}</sapn>”评论并申请置顶，请及时审核。</div>

                <div class="comment-audit">
                    @if($comment['expires_at'] == null)
                        <a href="javascript:" class="green" data-args="type=1&news_id={{$comment['news']['id']}}&comment_id={{$comment['comment']['id']}}&pinned_id={{$comment['id']}}">同意置顶</a>
                        <a href="javascript:" class="green" data-args="type=-1&news_id={{$comment['news']['id']}}&comment_id={{$comment['comment']['id']}}&pinned_id={{$comment['id']}}">拒绝置顶</a>
                    @else
                        <a href="javascript:">同意置顶</a>
                    @endif
                </div>
            </dd>
        </dl>
    @endforeach

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + TOKEN,
                'Accept': 'application/json'
            }
        });
        $('.comment-audit').on('click', 'a', function () {
            var _this = this;
            var data = urlToObject($(this).data('args'));
            var url = '';
            var type = 'PATCH';

            if (data.type == 1) {
                url = '/api/v2/news/'+data.news_id+'/comments/'+data.comment_id+'/pinneds/'+data.pinned_id;
            } else {
                url = '/api/v2/news/comments/'+data.comment_id+'/pinneds/'+data.pinned_id+'/reject';
            }

            $.ajax({
                url: url,
                type: type,
                dataType: 'json',
                error: function(xml) {},
                success: function(res, data, xml) {
                    if (xml.status == 201) {
                        noticebox(res.message, 1);
                        $(_this).parent('.comment-audit').html('<a href="javascript:">同意置顶</a>');
                    } else if (xml.status == 204) {
                        noticebox('拒绝置顶成功', 1);
                        $(_this).parent('.comment-audit').html('<a href="javascript:">拒绝置顶</a>');
                    } else {
                        noticebox(res.message, 0);
                    }
                }
            });
        });
    </script>

@endif