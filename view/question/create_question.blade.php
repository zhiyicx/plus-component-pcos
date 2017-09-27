@section('title') 提问 @endsection @extends('pcview::layouts.default') @section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
@endsection @section('content')
<div class="create-question">
    <div class="step1">
        <div class="question-tw">提问</div>
        <div class="question-form-row" style="position:relative">
            <input type="hidden" id="question_id" name="id" value="{{$id or 0}}" />
            <input id="subject" name="subject" type="text" value="" placeholder="请输入问题并已问号结束" autocomplete="off"/>
            <div class="subject-error"></div>
            <div class="question-searching">
                <div class="searching-existing"></div>
            </div>
        </div>
        <div class="question-form-row question-topics">
            <label for="J-select-topics">请选择话题</label>
            <ul class="question-topics-selected" id="J-select-topics"></ul>
            <div class="question-topics-list" id="J-topic-box" style="display: none;">
                <dl>
                    @foreach ($topics as $topic)
                        <dd data-id="{{$topic->id}}">{{ $topic->name }}</dd>
                    @endforeach
                </dl>
            </div>
            <input type="hidden" name="topics" id="topics" />
        </div>
        <div class="question-form-row">
            @include('pcview::widgets.markdown', ['height'=>'400px', 'width' => '100%', 'content'=>$content ?? ''])
        </div>
        <div class="question-form-row">

            <input id="anonymity" name="anonymity" type="checkbox" class="input-checkbox"/>
            <label for="anonymity">启动匿名</label>
        </div>
        <div class="question-next"><button id="question-next">下一步</button></div>
    </div>
    <div class="step2">
        <div class="question-tw">设置悬赏
            <span class="tw-notice">(可跳过)</span>
            <span class="reward-rule">悬赏规则</span>
        </div>
        <div class="reward-row">
            <div class="reward-notice">设置悬赏金额</div>
            <ul class="reward-example">
                <li>1.00</li>
                <li>5.00</li>
                <li>10.00</li>
            </ul>
            <input type="text" class="custom-money" id="amount" placeholder="自定义悬赏金额">
            <input type="hidden" id="amount-hide" name="amount">
        </div>
        <div class="reward-row">
            <div class="invitation-notice">
                {{--<div class="reward-notice">悬赏邀请</div>--}}
                {{--<span>--}}
                    {{--<input id="reward" name="reward" type="checkbox" class="input-checkbox"/>--}}
                {{--<label for="reward">邀请答题人，设置围观等</label>--}}
                {{--</span>--}}


                <div class="reward-notice">悬赏邀请</div>
                <span>
                    <input id="rewardyes" name="reward" type="radio" value="1" class="input-radio"/>
                    <label for="rewardyes">是</label>
                </span>
                <span>
                    <input id="rewardno" name="reward" type="radio" value="0" checked="checked" class="input-radio"/>
                    <label for="rewardno">否</label>
                </span>
            </div>
            <div class="invitation-con">
                <dl>
                    <dt>邀请回答</dt>
                    <dd id="invitation_user">
                        <a href="javascript:" id="invitation-add">添加</a>
                    </dd>
                </dl>
                <dl>
                    <dt>是否开启围观</dt>
                    <dd>
                        <span>
                            <input id="lookyes" name="look" type="radio" value="1" class="input-radio"/>
                             <label for="lookyes">是</label>
                        </span>
                        <span>
                            <input id="lookno" name="look" type="radio" value="0" checked="checked" class="input-radio"/>
                             <label for="lookno">否</label>
                        </span>
                    </dd>
                </dl>
            </div>
            <div class="question-next"><button id="question-submit">发布问题</button></div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
    <script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
    <script>
        // 问题搜索
        var last;
        var subject = $('#subject');
        var args = {};
        var step1 = $('.step1');
        var step2 = $('.step2');
        subject.keyup(function (event) {
            //利用event的timeStamp来标记时间，这样每次的keyup事件都会修改last的值
            last = event.timeStamp;
            setTimeout(function(){
                if(last - event.timeStamp == 0){
                    $('.subject-error').text('').hide();
                    question_search();
                }
            }, 500);
        });
        subject.focus(function() {
            var val = $.trim(subject.val());
            if (val.length >= 1) {
                question_search();
            }
        });
        function question_search(event) {
            var val = $.trim(subject.val());
            var question_searching = $('.question-searching');
            var searching_existing = $(".searching-existing");
            searching_existing.html('');
            $('.searching-notice').remove();

            if (val != "") {
                $.ajax({
                    type: "GET",
                    url: '/api/v2/questions',
                    data: {
                        subject: val,
                        type: "all",
                        limit: 8
                    },
                    success: function(res) {
                        if (res.length > 0) {
                            question_searching.prepend('<div class="searching-notice">您的问题可能已有答案</div>');
                            $.each(res, function(key, value) {
                                if (key < 8) {
                                    var html = '<div><a href="/question/' + value.id + '">' + value.subject + '</a></div>';
                                    searching_existing.append(html);
                                }
                            });
                            question_searching.show();
                            $('.searching-notice').on('click', function () {
                                searching_existing.html('');
                                $('.searching-notice').remove();
                            })
                        }
                    }
                });
            }
        }

        // 选择话题
        $('.question-topics').on('click', '>*', function(e){
            e.stopPropagation();
            $('#J-topic-box').toggle();
        });
        $('#J-topic-box dd').on('click', function(e){
            e.stopPropagation();
            var selBox = $('#J-select-topics');
            var topic_id = $(this).data('id');
            var topic_name = $(this).text();
            if (selBox.find('li').hasClass('topic_'+topic_id)) {
                noticebox('话题已存在', 0); return;
            }
            selBox.append('<li class="topic_'+topic_id+'" data-id="'+topic_id+'">'+topic_name+'</li>');
            selBox.on('click', 'li', function(){
                $(this).remove();
                if (selBox.find('li').length == 0) {
                    $('.question-topics label').show();
                }
            });
            if (selBox.find('li').length >= 5) {
                noticebox('话题最多五个', 0);
                return;
            } else if (selBox.find('li').length > 0) {
                $('.question-topics label').hide();
            }
        });

        // 下一步
        $('#question-next').on('click', function () {
            args.subject = $('#subject').val().replace(/(\s*$)/g, "");
            args.body = editor.getMarkdown();
            args.anonymity = $("input[type='checkbox'][name='anonymity']:checked").val() == 'on' ? 1 : 0;
            args.question_id = $('#question_id').val() || 0;
            args.topics = [];
            $('#J-select-topics li').each(function(index){
                args.topics.push($(this).data('id'));
            });
            if (args.subject.length < 1) {
                $('#subject').focus();
                $('.subject-error').text('请输入标题').show();

                return false;
            } else if (args.subject.charAt(args.subject.length - 1) != '?' && args.subject.charAt(args.subject.length - 1) != '？') {
                $('#subject').focus();
                $('.subject-error').text('请以问号结束提问').show();

                return false;
            } else if (args.subject.match(/([\u4E00-\u9FA5A-Za-z0-9])/g) == null) {
                 $('#subject').focus();
                 $('.subject-error').text('请认真填写标题').show();

                 return false;
             }

            if (args.topics.length < 1) {
                noticebox('请选择话题', 0);

                return false;
            }

            step1.hide();
            step2.show();

        });

        // 悬赏规则
        $('.reward-rule').on('click', function () {
            var html = formatConfirm('悬赏规则', '化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？化对花说：“显摆啥，不就是戴了顶草帽吗？')
            ly.alert(html);
        });

        // 选择悬赏金额
        $('.reward-example').on('click', 'li', function () {
            if ($(this).hasClass('select-amount')) {
                $(this).removeClass('select-amount');
            }else{
                $(this).siblings().removeClass('select-amount');
                $(this).addClass('select-amount');
                $("#amount-hide").val($(this).text());
                $('#amount').val('');
            }

        });
        $('#amount').focus(function () {
            $('.select-amount').removeClass('select-amount');
        });

        // 是否开启悬赏邀请
        $('#rewardno').on('click', function () {
            $('.invitation-con').hide('fast');
        });
        $('#rewardyes').on('click', function () {
            $('.invitation-con').show('fast');
        });
        $('#reward').on('click', function () {
            if ($("input[type='checkbox'][name='reward']:checked").val() == 'on') {
                $('.invitation-con').show('fast');
            } else {
                $('.invitation-con').hide('fast');
            }
        });

        // 添加邀请人
        $('#invitation-add').on('click', function () {
            ly.load('/question/users', '', '480px', '550px', 'GET',
                {'topics' : args.topics
                });
        });
        // 发布问题
        $('#question-submit').on('click', function () {
            args.amount = parseInt($('#amount').val()) || parseInt($("#amount-hide").val()) || 0;
            args.look = $("input[type='radio'][name='look']:checked").val();
            var topic = [];
            for (var key in args.topics) {
                topic[key] = {};
                topic[key].id = args.topics[key];
            }
            args.topics = topic;
            var invitations = [];
            for (var key in args.invitations) {
                invitations[key] = {};
                invitations[key].user = args.invitations[key];
            }
            args.invitations = invitations;
            args.automaticity = 0;
            if (args.look == 1) {
                if (args.amount <= 0) {
                    noticebox('请设置悬赏金额', 0);

                    return false;
                }
                if (args.invitations.length != 1) {
                    noticebox('请邀请回答人', 0);

                    return false;
                }
                args.automaticity = 1;
            }

            $.ajax({
                type: 'POST',
                url: '/api/v2/questions',
                data: args,
                success: function(res, data, xml) {
                    if (xml.status == 201) {
                        noticebox(res.message, 1, '/question/'+res.question.id);
                    } else {
                        noticebox(res.message, 0);
                    }
                }
            });
        })

    </script>
@endsection