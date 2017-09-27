$(function() {
    // 获取焦点
    $('input[name="login"]').focus();

    var passlod = false;
    $('#login_btn').click(function() {
        var _this = $(this);

        var login = $('input[name="login"]').val();
        if (!passlod) {
            var url = '/api/v2/tokens';
            passlod = true;
            $('#login_form').ajaxSubmit({
                type: 'post',
                url: url,
                beforeSend: function() {
                    _this.text('登录中');
                    _this.css('cursor', 'no-drop');
                },
                success: function(res) {
                    noticebox('登录成功，跳转中...', 1, '/passport/token/' + res.token + '/0');
                },
                error: function(xhr) {
                    showError(xhr.responseJSON);
                },
                complete: function() {
                    _this.text('登录');
                    _this.css('cursor', 'pointer');
                    passlod = false;
                }
            });
        }
        return false;

    });

    // 注册提交
    $('#reg_btn').click(function() {
        var _this = $(this);
        var phone = $('input[name="phone"]').val();
        var email = $('input[name="email"]').val();
        var captcha = $('input[name="captchacode"]').val();
        var smscode = $('input[name="verifiable_code"]').val();
        var name = $('input[name="name"]').val();
        var password = $('input[name="password"]').val();
        var repassword = $('input[name="repassword"]').val();
        // 注册类型
        var regtype = $('input[name="verifiable_type"]').val();

        if (phone == '') {
            $('input[name="phone"]').focus();
            return false;
        }

        if (email == '') {
            $('input[name="email"]').focus();
            return false;
        }

        if (regtype == 'sms') {
            if(!checkPhone(phone)) {
                noticebox('请输入正确的手机号', 0);
                $('input[name="phone"]').focus();
                return false;
            }
        } else {
            if (!checkEmail(email)) {
                noticebox('请输入正确的邮箱', 0);
                $('input[name="email"]').focus();
                return false;
            }
        }

        if (captcha == '') {
            $('input[name="captchacode"]').focus();
            return false;
        }

        if (smscode == '') {
            $('input[name="verifiable_code"]').focus();
            return false;
        }

        if (name == '') {
            $('input[name="name"]').focus();
            return false;
        }

        if (getLength(name) < 2) {
            noticebox('用户名不能低于2个中文或4个英文', 0);
            $('input[name="name"]').focus();
            return false;
        }

        if (password == '') {
            $('input[name="password"]').focus();
            return false;
        }

        if (password.length < 6 || password.length > 15) {
            noticebox('密码长度必须在6-15个字符', 0);
            $('input[name="repassword"]').focus();
            return false;
        }

        if (password != repassword) {
            noticebox('两次密码输入不一致', 0);
            $('input[name="repassword"]').focus();
            return false;
        }

        if (!passlod) {
            var url = '/api/v2/users';
            passlod = true;
            $('#reg_form').ajaxSubmit({
                type: 'post',
                url: url,
                beforeSend: function() {
                    _this.text('注册中');
                    _this.css('cursor', 'no-drop');
                },
                success: function(res) {
                    noticebox('注册成功，跳转中...', 1, '/passport/token/' + res.token + '/1');
                },
                error: function(xhr) {
                    showError(xhr.responseJSON);
                },
                complete: function() {
                    _this.text('注册');
                    _this.css('cursor', 'pointer');
                    passlod = false;
                }
            });
        }
        return false;
    });

    // 找回密码提交
    $('#findpwd_btn').click(function() {
        var _this = $(this);
        var phone = $('input[name="phone"]').val();
        var email = $('input[name="email"]').val();
        var captcha = $('input[name="captchacode"]').val();
        var smscode = $('input[name="verifiable_code"]').val();
        var password = $('input[name="password"]').val();
        var repassword = $('input[name="repassword"]').val();
        // 注册类型
        var regtype = $('input[name="verifiable_type"]').val();

        if (phone == '') {
            $('input[name="phone"]').focus();
            return false;
        }

        if (email == '') {
            $('input[name="email"]').focus();
            return false;
        }

        if (regtype == 'sms') {
            if(!checkPhone(phone)) {
                noticebox('请输入正确的手机号', 0);
                $('input[name="phone"]').focus();
                return false;
            }
        } else {
            if (!checkEmail(email)) {
                noticebox('请输入正确的邮箱', 0);
                $('input[name="email"]').focus();
                return false;
            }
        }


        if (captcha == '') {
            $('input[name="captchacode"]').focus();
            return false;
        }

        if (smscode == '') {
            $('input[name="verifiable_code"]').focus();
            return false;
        }

        if (password == '') {
            $('input[name="password"]').focus();
            return false;
        }

        if (password.length < 6 || password.length > 15) {
            noticebox('密码长度必须在6-15个字符', 0);
            $('input[name="repassword"]').focus();
            return false;
        }

        if (password != repassword) {
            noticebox('两次密码输入不一致', 0);
            $('input[name="repassword"]').focus();
            return false;
        }


        if (!passlod) {
            var url = '/api/v2/user/retrieve-password';
            passlod = true;
            $('#findpwd_form').ajaxSubmit({
                type: 'PUT',
                url: url,
                beforeSend: function() {
                    _this.text('找回中...');
                    _this.css('cursor', 'no-drop');
                },
                success: function() {
                    noticebox('找回成功', 1, '/passport/login');
                },
                error: function(xhr) {
                    showError(xhr.responseJSON);
                },
                complete: function() {
                    _this.text('找回');
                    _this.css('cursor', 'pointer');
                }
            });
        }
        return false;
    });

    // 发送短信验证码
    $('#smscode').click(function() {
        if ($(this).hasClass('get_code_disable')) return false;
        
        var _this = $(this);
        // 发送类型
        var type = _this.attr('type');
        // 注册类型
        var regtype = $('input[name="verifiable_type"]').val();


        var login = regtype == 'sms' ? $('input[name="phone"]').val() : $('input[name="email"]').val();
        var captcha = $('input[name="captchacode"]').val();

        if (regtype == 'sms') {
            if (login == '') $('input[name="phone"]').focus();
            if(!checkPhone(login)) {
                noticebox('请输入正确的手机号', 0);
                $('input[name="phone"]').focus();
                return false;
            }
            var data = { phone: login };
        } else {
            if (login == '') $('input[name="email"]').focus();
            if (!checkEmail(login)) {
                noticebox('请输入正确的邮箱', 0);
                $('input[name="email"]').focus();
                return false;
            }
            var data = { email: login };
        }

        if (!captcha) {
            $('input[name="captchacode"]').focus();
            return false;
        }

        // 验证图形验证码
        var url = '/passport/checkcaptcha';
        $.ajax({
            type: 'post',
            url: url,
            data: { captcha: captcha },
            success: function(res) {
                // 发送验证码 
                var url = type == 'reg' ? '/api/v2/verifycodes/register' : '/api/v2/verifycodes';
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function() {
                        var str = '等待<span id="passsec">60</span>秒';
                        _this.html(str);
                        timeDown(60);
                        $('input[name="code"]').val('');
                        noticebox('验证码发送成功', 1);
                    },
                    error: function(xhr) {
                        showError(xhr.responseJSON);
                    }
                }, 'json');
            },
            error: function(xhr) {
                noticebox('图形验证码错误', 0);
                re_captcha();
            }
        }, 'json');
        return false;
    })

})


// 验证码倒计时
var downTimeHandler = null;
var timeDown = function(timeLeft) {
    clearInterval(downTimeHandler);
    if (timeLeft <= 0) return;
    $('#smscode').addClass('get_code_disable');
    $('#passsec').html(timeLeft);
    downTimeHandler = setInterval(function() {
        timeLeft--;
        $('#passsec').html(timeLeft);
        if (timeLeft <= -1) {
            clearInterval(downTimeHandler);
            $('#smscode').html('获取验证码').removeClass('get_code_disable');
        }
    }, 1000);
};


// 刷新验证码
var re_captcha = function() {
    var url = '/passport/captcha';
    url = url + "/" + Math.random();
    $('#captchacode').attr('src', url);
    $('input[name="captchacode"]').val('');
};