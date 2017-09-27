
/**
 * 微博操作
 * @type {Object}
 */
var profile = {
    delete : function(id, type) {
        var url = '';
        if (type == 'feeds') {
            url = '/api/v2/feeds/' + id;
        }
        layer.confirm('确定删除这条信息？', {icon: 3}, function(index) {
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                success: function(res) {
                    $('#feed' + id).fadeOut();
                    layer.close(index);
                },
                error: function(xhr){
                    showError(xhr.responseJSON);
                }
            });
        });
    },
    pinneds: function (id, type) {
        if (type == 'feeds') {
            var url = '/api/v2/feeds/'+id+'/pinneds';
            pinneds(url);
        }
        if (type == 'news') {
            var url = '/api/v2/news/'+id+'/pinneds';
            pinneds(url);
        }

    },
    addComment: function (row_id, type, cate) {
        var url = '/api/v2/'+cate+'/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    }
}

$(function() {
    $('#cover').on('change', function(e) {
        var file = e.target.files[0];
        var formDatas = new FormData();
            formDatas.append("image", file);
            $.ajax({
                url: '/api/v2/user/bg',
                type: 'POST',
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(res) {
                    noticebox('更换背景图成功', 1);
                    $('.user_bg').attr('src', window.URL.createObjectURL(file));
                }
            });
    });
    // 显示回复框
    $('#feeds_list').on('click', '.J-comment-show', function() {
        checkLogin();
        var comment_box = $(this).parent().siblings('.comment_box');
        if (comment_box.css('display') == 'none') {
            comment_box.show();
        } else {
            comment_box.hide();
        }
    });
})