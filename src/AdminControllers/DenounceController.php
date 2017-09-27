<?php 

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Denounce;


/**
* 举报管理
*/
class DenounceController extends Controller
{
    public function getDenounceList(Request $request)
    {
        $key = $request->key;
        $state = $request->state;
        $datas = Denounce::where( function ($query) use ($state) {
                if ($state) {
                    if ($state == -1) {$state = 0;}
                    $query->where('state', $state);
                }
            })
            ->where( function ($query) use ($key) {
                if ($key) {
                    $query->where('reason', 'like', '%'.$key.'%');
                }
            })
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas
        ]))->setStatusCode(200);
    }

    public function handle(Request $request, int $did)
    {
        $state = $request->state;
        if ($state > 0) {
            $denounce = Denounce::find($did);
            if ($denounce) {
                $denounce->state = $state;
                $denounce->save();

                return response()->json(static::createJsonData([
                    'status' => true,
                    'message' => '操作成功'
                ]))->setStatusCode(200);
            }
        } else {
            $denounce = Denounce::find($did);
            if ($denounce) {
                $denounce->delete();

                return response()->json(static::createJsonData([
                    'status' => true,
                    'message' => '删除成功'
                ]))->setStatusCode(200);
            }
        }
    }


}

