var message = new function() {

    var self = this;

    /* 任务栏配置 */
    self.params = {
        taskbar: {
            clickLi: function(li, e) {console.log(li)},
            clickLiClearMsgnum: true,
            removeLi: function(li, e) {},
            lis: {
                "pl": {
                    id: 'pl',
                    title: "评论",
                    src: RESOURCE_URL + '/images/message/pl.png',
                    icon:'<svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xuanzedui-copy-copy-copy"></use></svg>',
                },
                "zan": {
                    id: 'zan',
                    title: "赞",
                    src: RESOURCE_URL + '/images/message/zan.png',
                    icon:'<svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuande-copy"></use></svg>',
                }
            }
        }
    };

    /* 任务栏 */
    var taskbar = {

        el: '<div id="message-taskbar">\
               <div class="wrap">\
                 <ul id="message-fixed" class="message-list"></ul>\
                 <ul id="message-users" class="message-list"></ul>\
               </div>\
               <div class="smartButton" show="1">\
                    <i class="smartButton-num"></i>\
                    <i class="smartButton-iconfont">&#xf0200;</i>\
                    <span class="smartButton-text">联系人</span>\
               </div>\
            </div>',

        li: '<li class="tooltip tip-left"><a href="javascript:;"><img /><i></i></a></li>',

        isBindEvents: false,

        /* # 储存消息的对象 */
        messageNumList: {},

        /* # 储存消息总数 */
        messageCount: 0,

        limitHeight: 47,

        /**
         * 在页面上构建任务栏
         * @return void
         */
        build: function() {
            taskbar.remove();

            $('body').append(taskbar.el);
            taskbar.initEvents();  

            try {
                var lis = self.params.taskbar.lis;
                $.each(lis, function(i, params) {
                    taskbar.addLi(params);
                });
                /* # 添加智能按钮初始事件 */
                taskbar.initSmartButton();
                /* # 窗口变化事件 */
                // $(window).on('resize', function() {
                //     taskbar.initSmartButton();
                // });
                /* # 开启定时事件 */
                taskbar.setTimeoutSmartButtonMessageNumber();
            } catch (e) {}
        },

        /**
         * 初始化SmartButton（显示或者隐藏联系人按钮）
         * @return void
         */
        initSmartButton: function() {
            /* # 针对非chrome浏览器的兼容处理 */
            //if (/*navigator.userAgent.toLowerCase().match(/edge/) == null && */window.chrome) {
            taskbar.jq('.wrap').addClass('chrome');
            taskbar.limitHeight = 40;
            //};
            /* # 前置判断，判断分辨率问题 */
            taskbar.messageStatus = $(document).width() < 1140;
            if (!taskbar.messageStatus) {
                taskbar.jq().css({
                    right: 0,
                    top: 0
                });
                taskbar.jq('.smartButton').show().css({
                    left: 0,
                    opacity: 0
                });
                taskbar.jq('.wrap').css('margin-left', 0);
                return false;
            };

            /* # 初始化 */
            taskbar.jq().css({
                top: 0,
                right: '-45px'
            });
            taskbar.messageStatus && taskbar.jq('.smartButton').show().animate({
                left: '-30px',
                opacity: 1
            }, 900, function() {
                taskbar.jq().animate({
                    right: '-32px',
                    top: 0
                }, 450);
                taskbar.jq('.wrap').animate({
                    marginLeft: '5px'
                }, 450);
            });
            /* # 绑定额外区域变比smartButton */
            taskbar.initSmartBar || $(document).on('click', 'div#body-bg', function() {
                taskbar.messageStatus && taskbar.changeSmartBarStatus(false);
            });
            /* # 绑定smartButton点击事件 */
            taskbar.initSmartBar || taskbar.jq('.smartButton').on('click', function(event) {
                event.preventDefault();
                taskbar.messageStatus && taskbar.changeSmartBarStatus(true);
            });
            taskbar.initSmartBar = true;
        },

        /* # title新增闪动效果 */
        $head: $('head').find('title'),
        isShowTitle: false,
        showTitleText: $('head').find('title').text(),
        showTitle: function(status) {
            taskbar.isShowTitle = status ? true : false;
            taskbar.showTitleToOne = true;
            if (taskbar.isShowTitle) {
                if (taskbar.showTitlwSetp == 1) {
                    taskbar.showTitlwSetp = 2;
                    taskbar.$head.text('【新消息】' + taskbar.showTitleText);
                } else {
                    taskbar.showTitlwSetp = 1;
                    taskbar.$head.text('【　　　】' + taskbar.showTitleText);
                };
                return false;
            };
            taskbar.$head.text(taskbar.showTitleText);
            taskbar.isShowTitle = false;
        },

        /* # 定时执行消息提示方法 */
        setTimeoutSmartButtonMessageNumber: function() {
            taskbar.messageCount = 0;
            for (i in taskbar.messageNumList) {
                taskbar.messageCount += parseInt(taskbar.messageNumList[i]);
            }
            if (taskbar.messageCount > 0) {
                taskbar.jq('.smartButton-num').text(taskbar.messageCount > 9 ? '···' : taskbar.messageCount).fadeIn('show');
                taskbar.jq('.smartButton-text').text('新消息');
                taskbar.showTitle(true);
            } else {
                taskbar.jq('.smartButton-text').text('联系人');
                taskbar.jq('.smartButton-num').text(0).fadeOut('show');
                taskbar.showTitle(false);
            };
            setTimeout(function() {
                taskbar.setTimeoutSmartButtonMessageNumber();
            }, 500);
        },

        /* # smartButton和taskBar状态切换 */
        changeSmartBarStatus: function(status) {
            status = status ? true : false;
            if (status) {
                taskbar.jq('.smartButton').animate({
                    left: '5px',
                    opacity: 0
                }, 450, function() {
                    taskbar.jq('.smartButton').hide();
                });
                taskbar.jq().animate({
                    right: 0
                }, 900);
                taskbar.jq('.wrap').animate({
                    marginLeft: 0
                }, 900);
                /* # 用于中断执行 */
                return true;
            };
            taskbar.jq('.smartButton').show().animate({
                left: '-30px',
                opacity: 1
            }, 900);
            taskbar.jq().animate({
                right: '-32px'
            }, 450);
            taskbar.jq('.wrap').animate({
                marginLeft: '5px'
            }, 100);
        },

        /**
         * 从页面上移除任务栏
         * @return void
         */
        remove: function() {
            if (taskbar.exist()) {
                taskbar.jq().remove();
            }
        },

        /**
         * 检查任务栏是否已经在页面上量
         * @return boolean 存在返回true，否则为false
         */
        exist: function() {
            return taskbar.jq().length > 0;
        },

        /**
         * 设置消息数量，展现在前端提示用户
         * @param string id 列表项的ID
         * @param integer number 设置的消息数量
         * @return void
         */
        setMessageNumber: function(id, number) {
            if (!taskbar.exist()) return;
            /* # 设置消息数量到数组 */
            taskbar.messageNumList[id] = number;
            var i = taskbar.jq('#message-' + id + ' i');
            i.data('num', number).text(number > 9 ? '···' : number);
            if (number <= 0) {
                i.addClass('hide');
            } else {
                i.removeClass('hide');
            }
        },

        /**
         * 添加一个li标签
         * @param array|object params li的参数
         * @param string|function insert 插入的方式
         * @param string toId 插入参照的ID
         * @return void
         */
        addLi: function(params, insert, toId) {
            //任务栏不存在则自动建立
            if (!taskbar.exist()) {
                taskbar.build();
            }

            var li = $(taskbar.li); //li标签
            var a = li.find('a'); //img标签
            var i = li.find('i'); //i标签
            var number = params.num || 0; //消息数
            var type, isReplace, method;

            /* # 设置消息数量到数组 */
            taskbar.messageNumList[params.id] = number;

            // li参数设置
            li.attr('id', 'message-' + params.id);
            li.data('id', params.id);
            li.data('realId', 'message-' + params.id);
            // li.attr('title', params.title || '');
            li.attr('data-tooltip', params.title || '');
            a.html(params.icon);
            i.data('num', number).text(
                number > 9 ? '···' : number
            );
            if (number <= 0) {
                i.addClass('hide');
            } else {
                i.removeClass('hide');
            }
            if (params.roomid) {
                type = 'users';
                li.data('roomid', params.roomid);
            } else {
                type = 'fixed';
            }
            type = params.type || type;
            li.data('type', type);
            // li参数设置结束

            //是否需要替换
            isReplace = taskbar.hasId(params.id);
            //支持的插入方式
            method = ['append', 'prepend', 'after', 'before'];
            // 如果传入一个function，则直接执行
            if (typeof insert == 'function') {
                insert(li, type, isReplace);
                return;
            } else if ($.inArray(insert, method) < 0) {
                insert = 'append';
            }

            toId = toId ? toId : type;
            if (isReplace) {
                li.addClass('noanimat');
                li.replaceAll(taskbar.getId(params.id));
            } else {
                eval('taskbar.getId(toId).' + insert + '(li);');
            }
        },
        //移除某个ID
        removeId: function(id) {
            /* # 设置消息数量到数组 */
            taskbar.messageNumList[id] = 0;
            taskbar.getId(id).remove();
        },
        //清空某个类型里面的全部
        clear: function(type) {
            taskbar.getId(type).html('');
        },
        //取得指定表达式的jquery对象
        jq: function(expr) {
            if (expr) {
                return $('#message-taskbar').find(expr);
            } else {
                return $('#message-taskbar');
            }
        },
        //取得指定ID的jquery对象
        getId: function(id) {
            return taskbar.jq('#message-' + id);
        },
        //检查是否存在某个ID
        hasId: function(id) {
            return taskbar.getId(id).length > 0;
        },
        /**
         * 初始化消息数量
         * 给聊天中li元素绑定移动清除事件
         */
        initEvents: function() {
            if (taskbar.isBindEvents) return;
            taskbar.isBindEvents = true;

            var noActiveMove = null;
            var isMousedown = false;
            var mousedownLi = null;
            var mousedownX = 0;
            var mousedownY = 0;
            var lis = taskbar.jq('.message-list li');

            $(document).on('mousedown', '.message-list li', function(e) { //开始移动
                var li = $(this);
                if (li.data('type') == 'fixed' || li.hasClass('move')) {
                    return false;
                }
                //按住500毫秒后激活移动
                noActiveMove = setTimeout(function() {
                    noActiveMove = null;
                    mousedownLi = li;
                    isMousedown = true;
                    mousedownX = e.pageX;
                    mousedownY = e.pageY;
                }, 500);
                return false;
            }).on('click', '.message-list li', function(e) { //单击事件
                //还未激活移动则取消
                if (noActiveMove) {
                    clearTimeout(noActiveMove);
                }
                var li = $(this);
                if (li.hasClass('move')) {
                    return false; //正在移动中的
                }
                try {
                    if (self.params.taskbar.clickLiClearMsgnum) {
                        taskbar.setMessageNumber(li.data('id'), 0);
                    }
                    if (typeof self.params.taskbar.clickLi == 'function') {
                        self.params.taskbar.clickLi(li, e);
                    }
                } catch (e) {}
                return false;
            }).on('mouseup', '.message-list li', function() {
                //还未激活移动则取消
                if (noActiveMove) {
                    clearTimeout(noActiveMove);
                }
            });
            //移动出去
            $(document).mousemove(function(e) {
                if (!isMousedown) return;
                mousedownLi.css({
                    right: mousedownX - e.pageX,
                    bottom: mousedownY - e.pageY,
                }).addClass('move');
            }).mouseup(function(e) { //停止移动
                if (noActiveMove) clearTimeout(noActiveMove);
                if (!isMousedown) return;
                isMousedown = false;
                //删除
                if (parseInt(mousedownLi.css('right')) > 50) {
                    mousedownLi.addClass('scale');
                    setTimeout(function() {
                        try {
                            if (typeof self.params.taskbar.removeLi == 'function') {
                                self.params.taskbar.removeLi(mousedownLi, e);
                            }
                        } catch (e) {}
                        mousedownLi.remove();
                        mousedownLi = null;
                    }, 800);
                } else { // 回去
                    mousedownLi.animate({
                        right: 0,
                        bottom: 0
                    }, 300, function() {
                        mousedownLi.removeAttr('style')
                            .removeClass('move');
                        mousedownLi = null;
                    });
                }
            });
        }

    }; /* 任务栏 结束 */


    self._init = function(args) {
        self.init(args);
    };

    self.init = function(args) {
        if (MID <= 0) return;
        return;
        if(SOCKET_URL) { //判断是否配置im聊天服务器
            // var webSocket = null;
            // var options = {
            //     url: SOCKET_URL,
            //     onOpen : function(event) {
            //         console.log('打开Socket连接：ws://' + SOCKET_URL);
            //     },
            //     onSend : function(message) {
            //         console.log('发送数据包：' + message);
            //     },
            //     onMessage : function(message) {
            //         console.log('服务器数据包：' + message);
            //     },
            //     onError : function(event) {
            //         console.log('WebSocket错误：view console');
            //     },
            //     onClose : function(event) {
            //         if(!webSocket) return;
            //         webSocket = null;
            //         console.log('WebSocket关闭：ws://' + SOCKET_URL);
            //     }
            // };

            // // 非连接状态及未连接状态 连接SOCKET
            // if ((webSocket && webSocket.socket.readyState != 1) || webSocket == null) {
            //     SOCKET_URL  = SOCKET_URL + '?token=Kl77IrETrRVCh6aup1MFdg8AAAA';
            //     webSocket = $.websocket(options);
            // }
        }


        $(function() {
            taskbar.build();
            self.params.taskbar.clickLi = function(li) {
                if (li.data('type') == 'fixed' && !li.data('roomid')) {
                    ly.load('/webMessage/index/' + li.data('id'), '');
                } else {
                    ly.load('/webMessage/room/' + li.data('roomid'));
                }
            }

            $(window).scroll(function(e) {
                var shield = $('#msgbox-shield');
                if (shield.length > 0) {
                    $(window).scrollTop(shield.data('st'));
                }
            });

            var setTaskRoom = function(pos) {
                var limit = $(window).height() / taskbar.limitHeight - 5;
                // $.get(U('public/WebMessage/latelyRoomList'), {limit:limit}, function(res){
                //     /* # 评论和赞以及通知 */
                //     res.info.comment = parseInt(res.info.comment);
                //     res.info.digg    = parseInt(res.info.digg);
                //     res.info.notice  = parseInt(res.info.notice);
                //     res.info.at      = parseInt(res.info.at);
                //     res.info.comment >= 1 && taskbar.setMessageNumber('pl' , res.info.comment);
                //     res.info.digg    >= 1 && taskbar.setMessageNumber('zan', res.info.digg);
                //     res.info.notice  >= 1 && taskbar.setMessageNumber('tz' , res.info.notice);
                //     res.info.at      >= 1 && taskbar.setMessageNumber('at' , res.info.at);

                //     if(!res.data) return;
                //     var i;
                //     for(i in res.data){
                //         taskbar.addLi({
                //             id : 'room'+res.data[i].room_id,
                //             title : res.data[i].title,
                //             src : res.data[i].src,
                //             num: res.data[i].msg_new,
                //             roomid : res.data[i].room_id
                //         }, pos?pos:'append');
                //     }
                // }, 'json');
            }

            setTaskRoom('append');

            setInterval(function() { setTaskRoom('prepend'); }, 30000);

            self.params.taskbar.removeLi = function(li) {
                var data = { roomid: li.data('roomid') };
                $.get('public/WebMessage/clearMessage', data, function(res){}, 'json');
                /* # 清理本地缓存的消息数量 */
                taskbar.messageNumList['room' + data.roomid] = 0;
            }
        });
    };

    self.taskbar = taskbar;
    self.setMessageNumber = taskbar.setMessageNumber;
    return undefined;
};
