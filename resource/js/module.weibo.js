var weibo = {};

/**
 * 上传后操作
 * @return void
 */
weibo.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" onclick="weibo.showImg();" src="' + SITE_URL + '/api/v2/files/' + task_id + '"/ tid="' + task_id + '" amount="">';
    var del = '<span class="imgdel"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-close"></use></svg></span>'
    $('#' + 'fileupload_1_' + f.index).css('border', 'none').html(img + del);
};
weibo.showImg = function(){
    layer.photos({
      photos: '#file_upload_1-queue'
      ,anim: 0
      ,move: false
    });
};
/**
 * 发布动态
 * @return void
 */
weibo.postFeed = function() {
    // 登录判断
    checkLogin();
    weibo.doPostFeed('free');
};

weibo.doPostFeed = function(type) {

    // 分享字数限制
    var strlen = getLength($('#feed_content').val());
    var leftnums = initNums - strlen;

    // 免费并仅有文字时验证1-255个字，其余不超过255字即可
    var check = (type == 'free' && $('.feed_picture').find('img').length == 0)  ? (leftnums < 0 || leftnums == initNums) : (leftnums < 0);
    if (check) {
        noticebox('分享内容长度为1-' + initNums + '字', 0);
        return false;
    }

    // 组装数据
    var data = {
        feed_content: $('#feed_content').val(),
        feed_from: 1,
        feed_mark: MID + new Date().getTime(),
    }
    var images = [];
    $('.feed_picture').find('img').each(function() {
        images.push({'id':$(this).attr('tid')});
    });
    if (images.length != 0) data.images = images;

    $.ajax({
        url: '/api/v2/feeds',
        type: 'post',
        data: data,
        success: function(res) {
            noticebox('发布成功', 1);
            $('.feed_picture').html('').hide();
            $('#feed_content').val('');
            weibo.afterPostFeed(res.id);
        },
        error: function(xhr){
            showError(xhr.responseJSON);
        }
    });
}

weibo.afterPostFeed = function(feed_id) {
    var url = '/feeds';
    $.ajax({
        url: url,
        type: 'get',
        data: { feed_id: feed_id},
        dataType: 'json',
        success: function(res) {
            if ($('#feeds_list').find('.no_data_div').length > 0) {
                $('#feeds_list').find('.no_data_div').remove();
            }
            $(res.data).hide().prependTo('#feeds_list').fadeIn('slow');
            $("img.lazy").lazyload({effect: "fadeIn"});
        }
    })
};
weibo.delFeed = function(feed_id, type) {
    layer.confirm(confirmTxt + '确定删除这条信息？', {}, function() {
        var url = '/api/v2/feeds/' + feed_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                if (type) {
                    noticebox('删除成功', 1, '/feeds');
                }
                $('#feed' + feed_id).fadeOut();
                layer.closeAll();
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    });
};
weibo.denounce = function(obj) {
    var feed_id = $(obj).attr('feed_id');
    var to_uid = $(obj).attr('to_uid');
    layer.prompt(function(val, index) {
        if (!val) {
            layer.msg(' 请填写举报理由', { icon: 0 });
        }
        var url = '';
        $.ajax({
            url: url,
            type: 'POST',
            data: { aid: feed_id, to_uid: to_uid, reason: val, from: 'weibo' },
            dataType: 'json',
            success: function(res) {
                layer.msg(' 举报成功', { icon: 1 });
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
        layer.close(index);
    });
};
//微博申请置顶
weibo.pinneds = function (feed_id) {
    var url = '/api/v2/feeds/'+feed_id+'/pinneds';
    pinneds(url);
};
weibo.addComment = function (row_id, type) {
    var url = '/api/v2/feeds/' + row_id + '/comments';
    comment.support.row_id = row_id;
    comment.support.position = type;
    comment.support.editor = $('#J-editor'+row_id);
    comment.support.button = $('#J-button'+row_id);
    comment.publish(url, function(res){
        $('.nums').text(comment.support.wordcount);
        $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
    });
};

$(function() {

    // 图片删除事件
    $(".feed_post").on("click", ".imgdel", function() {
        $(this).parent().remove();
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length == 0) {
            $('.uploadify-queue-add').remove();
            $('#file_upload_1-queue').hide();
        }
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length != 0  && $('.uploadify-queue-add').length == 0 ){
            var add = '<a class="feed_picture_span uploadify-queue-add"></a>'
            $('.uploadify-queue').append(add);
        }
    });

    // 微博分类tab
    $('.show_tab a').on('click', function() {
        var type = $(this).data('type');
        $('#feeds_list').html('');
        weibo.init({ container: '#feeds_list', type: type });
        $('.show_tab a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });

    // 显示回复框
    $('body').on('click', '.J-comment-show', function() {
        checkLogin();

        var comment_box = $(this).parent().siblings('.comment_box');
        if (comment_box.css('display') == 'none') {
            comment_box.show();
        } else {
            comment_box.hide();
        }
    });

    // 付费图片弹窗
    $('body').on('click', '.locked_image', function() {
        checkLogin();

        var _this = $(this);
        var amount = _this.data('amount');
        var node = _this.data('node');
        var file = _this.data('file');
        var image = _this.data('original');

        var html = formatConfirm('购买支付', '<div class="confirm_money">￥' + amount + '</div>您只需要支付￥' + amount + '元即可查看高清大图，是否确认支付？');
        ly.confirm(html, '', '', function(){
            var url = '/api/v2/purchases/' + node;
            // 确认支付
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    var img = '<img class="lazy per_image" data-original="' + image + '"/>';
                    _this.replaceWith(img);
                    $("img.lazy").lazyload({ effect: "fadeIn" });
                    noticebox('支付成功', 1);
                },
                error: function(xhr) {
                    showError(xhr.responseJSON);
                }
            });
        })
    });

    // 显示跳转详情文字
    $('body').on("mouseover mouseout", '.date', function(event){
        if(event.type == "mouseover"){
          var width = $(this).find('span').first().width();
            width = width < 60 ? 60 : width;
          $(this).find('span').first().hide();
          $(this).find('span').last().css({display:'inline-block', width: width});
        }else if(event.type == "mouseout"){
          $(this).find('span').first().show();
          $(this).find('span').last().hide();
        }
    });

    // 文字弹窗
    $('body').on('click', '.feed_pay_text', function() {
        checkLogin();

        var _this = $(this);
        var amount = _this.data('amount');
        var node = _this.data('node');
        var tourl = _this.data('url') || '';

        var html = formatConfirm('购买支付', '<div class="confirm_money">￥' + amount + '</div>您只需要支付￥' + amount + '元即可查看完整内容，是否确认支付？');
        ly.confirm(html, '', '', function(){
            var url = '/api/v2/purchases/' + node;
            // 确认支付
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (tourl == '') {
                        noticebox('支付成功', 1);
                    } else {
                        noticebox('支付成功', 1, tourl);
                    }
                },
                error: function(xhr) {
                    showError(xhr.responseJSON);
                }
            });
        })
    });


    // 付费设置确认
    $('body').on('click', '.pay_btn_yes', function() {
        // 输入框输入值
        var amount = $('.pay_body input').val();
        // 选择值
        var span_amount = $('.pay_body .current').attr('amount');
        if (amount == '' && typeof(span_amount) == 'undefined') {
            return false;
        }
        var real = amount == '' ? span_amount : amount;

        // 选择图片索引
        var index = $('.pay_image .current').parent().index();

        // 设置金额
        $('.pay_images .pay_image').eq(index).find('img').attr('amount', real);
        $('.feed_picture img').eq(index).attr('amount', real);

        // 添加标示
        if ($('.pay_images .pay_image').eq(index).find('svg').length == 0){
            $('.pay_images .pay_image').eq(index).append('<svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-suo"></use></svg>');
        }
    });

    $('body').on('click', '.pay_btn_reset', function() {
        // 选择图片索引
        var index = $('.pay_image .current').parent().index();

        // 设置金额
        $('.pay_images .pay_image').eq(index).find('img').attr('amount', '');
        $('.feed_picture img').eq(index).attr('amount', '');

        // 添加标示
        $('.pay_images .pay_image').eq(index).find('svg').remove();

        $('.pay_body span').removeClass('current');
        $('.pay_body input').val('');
    });

    // 付费图片点击
    $('body').on('click', '.pay_images img', function() {
        $(this).parents('.pay_images').find('img').removeClass('current');
        $(this).addClass('current');

        var amount = $(this).attr('amount');

        $('.pay_body').find('span').removeClass('current');
        $('.pay_body').find('input').val('');
        if (amount != '') {
            if (amount == '1') {
                $('.pay_body span[amount="1"]').addClass('current');
            } else if (amount == '5') {
                $('.pay_body span[amount="5"]').addClass('current');
            } else if (amount == '10') {
                $('.pay_body span[amount="10"]').addClass('current');
            } else {
                $('.pay_body input').val(amount);
            }
        }

        // 三角位置
        var left = $(this).parent().position().left + 3;
        $(this).parents('.pay_images').find('.triangle').css('margin-left', left);
    });

    // 收费金额选择
    $('body').on('click', '.pay_body span', function() {
        $(this).siblings().removeClass('current');
        $(this).addClass('current');
        $(this).parent().find('input').val('');

        // 若为文字付费
        if ($('.pay_images').length == 0) {
            $('#feed_content').attr('amount', $(this).attr('amount'));
        }
    });

    // 收费金额输入
    $('body').on('focus change', '.pay_body input', function() {
        $(this).parent().find('span').removeClass('current');

        // 若为文字付费
        if ($('.pay_images').length == 0) {
            $('#feed_content').attr('amount', $(this).val());
        }
    });
});
