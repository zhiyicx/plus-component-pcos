/**
 * 文章投稿。
 */
$('.subject-submit').on('click', function() {
    var args = {
        'author': $('#subject-author').val(),
        'title': $('#subject-title').val(),
        'subject': $('#subject-abstract').val(),
        'content': editor.getMarkdown(),
        'image': $('#subject-image').val(),
        'from': $('#subject-from').val(),
        'cate_id': $('#cate_id').val(),
        'news_id': $('#news_id').val() || 0
    };
    var tags = [];
    $('#J-select-tags li').each(function(index){
        tags.push($(this).data('id'));
    });
    args.tags = tags;

    if (!args.title || getLength(args.title) > 20) {
        noticebox('文章标题不合法', 0);
        return false;
    }
    if (args.cate_id == '') {
        noticebox('请选择分类', 0);
        return false;
    }
    if (getLength(args.subject) > 200) {
        noticebox('摘要内容不能超过200字', 0);
        return false;
    }
    if (!args.content || getLength(args.content) > 5000) {
        noticebox('文章内容不合法', 0);
        return false;
    }
    if (!args.subject) { // 如果没有摘要，则截取内容前200字作为摘要
        args.subject = subString(editor.getHTML().replace(/<.*?>/ig,""), 200)
    }
    if (args.tags.length < 1) {
        noticebox('请选择标签', 0);
        return false;
    }
    if (!args.image || args.image == 0) {
        /*var reg = /\@\!\[\]\((\w+)\)/;
        var imgs = reg.exec(args.content);
        if (imgs != null) {
            args.image = imgs[1];
        }*/
        noticebox('请上传封面图片', 0);
        return false;
    }

    if (notice.contribute.length > 0) {
        var isVerified = $.inArray("verified", notice.contribute);
        var isPay = $.inArray("pay", notice.contribute);
        var pay_conyribute = (parseInt(notice.pay_conyribute)/10).toFixed(1);

        if (isVerified > -1 && notice.verified == null) {
            ly.confirm(formatConfirm('投稿提示', '成功通过平台认证的用户才能投稿，是否去认证？'), '' , '去认证', function(){
                window.location.href = '/account/authenticate';
            });
            return false;
        } else if (isPay > -1) {
            var html = formatConfirm('投稿提示', '<div class="confirm_money">￥' + pay_conyribute + '</div>本次投稿您需要支付￥' + pay_conyribute + '元，是否继续投稿？');
            ly.confirm(html, '' , '投稿', function(){
                var url = '/api/v2/news/categories/'+args.cate_id+'/news';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: args,
                    dataType: 'json',
                    error: function(xml) {},
                    success: function(res, data, xml) {
                        if (xml.status == 201) {
                            noticebox(res.message, 1, '/news');
                        } else {
                            noticebox(res.message, 0);
                        }
                    }
                });
            });

            return false;
        }
    }

    var url = '/api/v2/news/categories/'+args.cate_id+'/news';
    $.ajax({
        url: url,
        type: 'POST',
        data: args,
        dataType: 'json',
        error: function(xml) {},
        success: function(res, data, xml) {
            if (xml.status == 201) {
                noticebox(res.message, 1, '/news');
            } else {
                noticebox(res.message, 0);
            }
        }
    });
});

var news = {
    pinneds: function (news_id) {
        var url = '/api/v2/news/'+news_id+'/pinneds';
        pinneds(url);
    },
    addComment: function (row_id, type) {
        var url = '/api/v2/news/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    }
};

function subString(str, len, hasDot) {
    hasDot = hasDot ? hasDot : false;
    var newLength = 0;
    var newStr = "";
    var chineseRegex = /[^\x00-\xff]/g;
    var singleChar = "";
    var strLength = str.replace(chineseRegex, "**").length;
    for (var i = 0; i < strLength; i++) {
        singleChar = str.charAt(i).toString();
        newLength++;
        if (newLength > len) {
            break;
        }
        newStr += singleChar;
    }

    if (hasDot && strLength > len) {
        newStr += "...";
    }
    return newStr;
}