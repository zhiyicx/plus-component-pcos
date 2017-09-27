@section('title')
安全设置
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="account_container">

    @include('pcview::account.sidebar')

    <div class="account_r">
        <div class="account_c_c">
            <div class="account_tab" id="J-input">
                <div class="perfect_title">
                    <p>修改密码</p>
                </div>
                @if($showPassword)
                    <div class="account_form_row">
                        <label class="w80 required" for="old_password"><font color="red">*</font>原密码</label>
                        <input id="old_password" name="old_password" type="password">
                    </div>
                @endif
                <div class="account_form_row">
                    <label class="w80 required" for="password"><font color="red">*</font>设置新密码</label>
                    <input id="password" name="password" type="password">
                </div>
                <div class="account_form_row">
                    <label class="w80 required" for="password_confirmation"><font color="red">*</font>确认新密码</label>
                    <input id="password_confirmation" name="password_confirmation" type="password">
                </div>
                <div class="perfect_btns">
                    <a class="perfect_btn save" id="J-user-security" href="javascript:;">保存</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$('#J-user-security').on('click', function(){
	var getArgs = function() {
        var inp = $('#J-input input').toArray();
        var sel;
        for (var i in inp) {
            sel = $(inp[i]);
            args.set(sel.attr('name'), sel.val());
        };
        return args.get();
    };
    $.ajax({
        url: '/api/v2/user/password',
        type: 'PUT',
        data: getArgs(),
        dataType: 'json',
        error: function(xhr) {
            showError(xhr.responseJSON);
        },
        success: function(res) {
            noticebox('密码修改成功', 1, 'refresh');
        }
    });
});
</script>
@endsection