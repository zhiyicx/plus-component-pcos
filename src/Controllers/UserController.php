<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class UserController extends BaseController
{
    public function users(Request $request, int $type = 1)
    {
        if ($request->ajax()){
            $type = $request->query('type');
            $limit = $request->query('limit') ?: 10;
            $offset = $request->query('offset') ?: 0;

            $params = [
                'limit' => $limit,
                'offset' => $offset,
            ];

            if ($type == 1) { // 热门
                $api =  '/api/v2/user/populars';
            } elseif ($type == 2) { // 最新
                $api =  '/api/v2/user/latests';
            } elseif ($type == 3) { // 推荐
                $api = '/api/v2/user/find-by-tags';
            } else { // 地区
                $api = '';
            }

            $users = createRequest('GET', $api, $params);
            // dd($users);
            $data['users'] = $users;

            $html =  view('pcview::templates.user', $data, $this->PlusData)->render();

            return response()->json([
                'status'  => true,
                'data' => $html,
                'count' => count($users)
            ]);
        }

        $data['type'] = $type;
        $data['area'] = createRequest('GET', '/api/v2/locations/hots');

        $this->PlusData['current'] = 'users';
        return view('pcview::user.users', $data, $this->PlusData);
    }

    public function follows(Request $request, int $type = 1, int $user_id = 0)
    {
        if ($request->ajax()) {
            $user_id = $request->query('user_id');
            $type = $request->query('type');

            $params = [
                'after' => $request->query('after'),
                'limit' => 10
            ];

            // 判断是否为自己
            $self = !empty($this->PlusData['TS']['id']) && $user_id == $this->PlusData['TS']['id'] ? 1 : 0;

            if ($type == 1) { // 粉丝
                $api =  $self ? '/api/v2/user/followers' : '/api/v2' . '/users/' . $user_id .'/followers';
            } else { //关注
                $api =  $self ? '/api/v2/user/followings' : '/api/v2' . '/users/' . $user_id .'/followings';
            }

            $users = createRequest('GET', $api, $params);
            $data['users'] = $users;
            $user = clone $users;
            $after = $user->pop()->id ?? 0;

            $html =  view('pcview::templates.user', $data, $this->PlusData)->render();

            return response()->json([
                'status'  => true,
                'after' => $after,
                'data' => $html,
                'count' => count($users)
            ]);
        }

        $data['type'] = $type;
        $data['user_id'] = !empty($this->PlusData['TS']['id']) && $user_id == 0 ? $this->PlusData['TS']['id'] : $user_id;
        return view('pcview::user.follows', $data, $this->PlusData);
    }
}
