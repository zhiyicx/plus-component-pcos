<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SlimKit\PlusSocialite\API\Requests\AccessTokenRequest;
use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Models\User;
use function asset as plus_asset;
use function view as plus_view;

/**
 * Generate an asset path for the application.
 *
 * @param string $path
 * @param bool $secure
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function asset($path, $secure = null)
{
    $path = asset_path($path);
    return plus_asset($path, $secure);
}
/**
 * Get The component resource asset path.
 *
 * @param string $path
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function asset_path($path)
{
    return component_name().'/'.$path;
}
/**
 * Get the component base path.
 *
 * @param string $path
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function base_path($path = '')
{
    return dirname(__DIR__).$path;
}
/**
 * Get the component name.
 *
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function component_name()
{
    return 'zhiyicx/plus-component-pc';
}
/**
 * Get the component route filename.
 *
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function route_path()
{
    return base_path('/router.php');
}
/**
 * Get the component resource path.
 *
 * @return string
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
function resource_path()
{
    return base_path('/resource');
}

function getShort($str, $length = 40, $ext = '')
{
    $str = nl2br($str);
    $str = addslashes($str);
    $str = trim($str);
    $str = strip_tags($str);
    $str = htmlspecialchars_decode($str);
    $strlenth = 0;
    $out = '';
    $output = '';
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
    foreach ($match[0] as $v) {
        preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $v, $matchs);
        if (!empty($matchs[0])) {
            $strlenth += 1;
        } elseif (is_numeric($v)) {
            //$strlenth +=  0.545;  // 字符像素宽度比例 汉字为1
            $strlenth += 0.5;    // 字符字节长度比例 汉字为1
        } else {
            //$strlenth +=  0.475;  // 字符像素宽度比例 汉字为1
            $strlenth += 0.5;    // 字符字节长度比例 汉字为1
        }

        if ($strlenth > $length) {
            $output .= $ext;
            break;
        }

        $output .= $v;
    }

    return $output;
}

function formatContent($content)
{
    $content = preg_replace_callback('/((?:https?|mailto|ftp):\/\/([^\x{2e80}-\x{9fff}\s<\'\"“”‘’，。}]*)?)/u', function($url){
        return '<a href="'.$url[0].'" target="_blank" style="color:#59b6d7;">访问链接+</a>';
    }, $content);

    return $content;
}

function createRequest($method = 'POST', $url = '', $params = array())
{
    $request = Request::create($url, $method, $params);
    $request->headers->add(['Accept' => 'application/json', 'Authorization' => 'Bearer '. Session::get('token')]);

    // 注入JWT请求单例
    app(\Tymon\JWTAuth\JWTAuth::class)->setRequest($request);

    // 解决获取认证用户
    $request->setUserResolver(function($guard) {
        return Auth::user($guard);
    });
    // 解决请求传参问题
    if ($url != '/api/v2/user/' && $url != '/api/v2/bootstrappers/' && $url != '/api/v2/tokens/') { // 获取登录用户不需要传参
        app()->instance(Request::class, $request);
    }

    $response = Route::dispatch($request)->original;
    return $response;
    // if ($response->isSuccessful()){
    //     return $response->original;
    // } else {
    //     return $response;
    // }
}

function socialiteRequest($method = 'POST', $url = '', $params = array())
{
    $request = AccessTokenRequest::create($url, $method, $params);
    $request->headers->add(['Accept' => 'application/json', 'Authorization' => 'Bearer '. Session::get('token')]);

    // 注入JWT请求单例
    app(\Tymon\JWTAuth\JWTAuth::class)->setRequest($request);

    // 解决获取认证用户
    $request->setUserResolver(function($guard) {
        return Auth::user($guard);
    });
    // 解决请求传参问题
    if ($url != '/api/v2/user/' && $url != '/api/v2/bootstrappers/' && $url != '/api/v2/tokens/') { // 获取登录用户不需要传参
        app()->instance(AccessTokenRequest::class, $request);
    }

    $response = Route::dispatch($request)->original;
    return $response;
}

function getTime($time, int $type = 1, int $format = 1)
{
    // 本地化
    Carbon::setLocale('zh');

    $timezone = isset($_COOKIE['customer_timezone']) ? $_COOKIE['customer_timezone'] : 0;
    // 一小时内显示文字
    if ((Carbon::now()->subHours(24) < $time) && $format) {
        return $time->diffForHumans();
    }
    return $type ? $time->addHours($timezone)->toDateString() : $time->addHours($timezone);
}

function getImageUrl($image = array(), $width, $height, $cut = true)
{
    if (!$image) {
        return false;
    }
    
    // 裁剪
    $file = $image['file'] ?? $image['id'];
    if ($cut) {
        $size = explode('x', $image['size']);
        if ($size[0] > $size[1]) {
            $width = number_format($height / $size[1] * $size[0], 2, '.', '');
        } else {
            $height = number_format($width / $size[0] * $size[1], 2, '.', '');
        }
        return getenv('APP_URL') . '/api/v2/files/' . $file . '?&w=' . $width . '&h=' . $height . '&token=' . Session::get('token');
    } else {
        return getenv('APP_URL') . '/api/v2/files/' . $file . '?&token=' . Session::get('token');
    }

}

function replaceImage($content)
{
    return preg_replace('/\@\!\[(.*)\]\((\d+)\)/i', '![$1](' . getenv('APP_URL') . '/api/v2/files/$2)', $content);
}

function getUserInfo($id)
{
    $user = User::where('id', '=', $id)->first();
    return $user;
}