/* 基本资料更新 */
$('#J-user-info').on('click', function(e) {
    var getArgs = function() {
        var inp = $('#J-input input,#J-input select').toArray();
        var sel;
        for (var i in inp) {
            sel = $(inp[i]);
            args.set(sel.attr('name'), sel.val());

            if ($(inp[i]).attr('name') == 'sex') {
                args.set('sex', $('[name="sex"]:checked').val());
            }
        };
        return args.get();
    };
    var arg = getArgs();
    if (!args.data.bio) {
        noticebox('个人简介不能为空', 0);
        return;
    }
    if (getLength(args.data.bio) > 50) {
        noticebox('个人简介不能超过50个字符', 0);
        return;
    }
    $.ajax({
        url: '/api/v2/user',
        type: 'PATCH',
        data: arg,
        dataType: 'json',
        error: function(xml) {
            noticebox('资料修改失败', 0, 'refresh');
        },
        success: function(res) {
            noticebox('资料修改成功', 1, 'refresh');
        }
    });
});