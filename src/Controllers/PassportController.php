<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Session;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class PassportController extends BaseController
{

    public function token(string $token, int $type)
    {
        Session::put('token', $token);
        Session::put('initial_password', true);
        if ($type) {
            return redirect(route('pc:perfect'));
        } else {
            return redirect(route('pc:feeds'));
        }
    }

    public function index()
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

    	return view('pcview::passport.login', [], $this->PlusData);
    }

    public function logout()
    {
        Session::flush();
        return redirect(route('pc:feeds'));
    }

    public function register(int $type = 0)
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

        return view('pcview::passport.register', ['type' => $type], $this->PlusData);
    }

    public function findPassword(int $type = 0)
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }
        
        return view('pcview::passport.findpwd', ['type' => $type], $this->PlusData);
    }

    public function perfect()
    {
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        $data['user_tag'] = createRequest('GET', '/api/v2/user/tags');
        return view('pcview::passport.perfect', $data, $this->PlusData);
    }

    public function captcha($tmp)
    {
        // 生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        // 设置背景
        $builder->setBackgroundColor(237,237,237);
        // 设置字体大小
        $builder->setBackgroundColor(237,237,237);
        // 可以设置图片宽高及字体
        $builder->build($width = 100, $height = 40, $font = null);
        // 获取验证码的内容
        $phrase = $builder->getPhrase();

        // 把内容存入session
        Session::flash('milkcaptcha', $phrase);
        // 生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    public function checkCaptcha(Request $request)
    {
        $input = $request->input('captcha');

        if (Session::get('milkcaptcha') == $input) {
            return response()->json([], 200);
        } else {
            return response()->json([], 501);
        }
    }
}
