<?php 

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;

/**
* 用户认证管理
*/
class AuthUserController extends Controller
{
    
    public function getAuthUserList(Request $request)
    {
        $key = $request->key;
        $state = $request->state;
        $datas = UserVerified::orderBy('id', 'desc')
            ->where(function($query) use ($key){
                    if ($key) {
                        $query->where('realname', 'like', '%'.$key.'%');
                    }
                })
            ->where(function($query) use ($state){
                if ($state) {
                    if ($state == -1) {$state = 0;}
                    $query->where('verified', $state);
                }
            })
            ->with('user')
            ->get();

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas
        ]))->setStatusCode(200);
    }

    /**
     * 认证用户
     * 
     * @param  Request $request [description]
     * @param  int     $aid     [description]
     * @return [type]           [description]
     */
    public function audit(Request $request, int $aid)
    {
        $state = $request->state;
        $verified = UserVerified::find($aid);
        if ($verified) {
            UserVerified::where('id', $aid)->update(['verified' => $state]);

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '提交成功，请等待审核',
            ]))->setStatusCode(200);
        }
    }

    /**
     * 删除用户认证信息
     * 
     * @param  int    $aid [description]
     * @return [type]      [description]
     */
    public function delAuthInfo(int $aid)
    {
        $verified = UserVerified::find($aid);
        if ($verified) {

            $verified->delete();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '删除成功',
            ]))->setStatusCode(200);
        }
    }
}