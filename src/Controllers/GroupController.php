<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class GroupController extends BaseController
{

    public function index(Request $request)
    {
        $this->PlusData['current'] = 'group';

        return view('pcview::group.index', [], $this->PlusData);
    }

    /**
     * 圈子列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $type = $request->input('type');
        $params = [
            'after' => $request->query('after') ?: 0
        ];

        $groups = createRequest('GET', '/api/v2/groups', $params);

        if ($type == 2) {
            $groups = createRequest('GET', '/api/v2/groups/joined', $params);
            $groups->map(function($item){
                $item->is_member = true;
            });
        }

        $group = clone $groups;
        $after = $group->pop()->id ?? 0;
        $data['group'] = $groups;
        $groupData = view('pcview::templates.group', $data, $this->PlusData)->render();

        return response()->json([
                'status'  => true,
                'data' => $groupData,
                'after' => $after
        ]);

    }

    /**
     * 圈子详情
     * @param Request $request
     * @param $group_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zsy
     */
    public function read(Request $request, $group_id)
    {
        $this->PlusData['current'] = 'group';

        $data['type'] = $request->input('type') ?: ($this->PlusData['TS'] ? 'new' : 'hot');
        $user = $this->PlusData['TS']['id'] ?? 0;
        $group = createRequest('GET', '/api/v2/groups/'.$group_id);

        $data['group'] = $group;

        return view('pcview::group.read', $data, $this->PlusData);
    }

    /**
     * 获取指定圈子动态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zsy
     */
    public function postLists(Request $request)
    {
        $group_id = $request->input('group_id');
        $type = $request->input('type', 'new');
        $params = [
            'after' => $request->query('after', 0),
        ];

        switch ($type) {
           case 'hot':
                $posts['posts'] = createRequest('GET', '/api/v2/groups/'.$group_id.'/posts', $params);
                break;
            case 'follow':
                $posts['posts'] = createRequest('GET', '/api/v2/groups/'.$group_id.'/posts', $params);
                break;
            default:
                $posts['posts'] = createRequest('GET', '/api/v2/groups/'.$group_id.'/posts', $params);
                break;
        }

        $post = clone $posts['posts'];
        $after = $post->pop()->id ?? 0;
        $feedData = view('pcview::templates.group_posts', $posts, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $feedData,
            'after' => $after
        ]);
    }

    /**
     * 创建圈子动态后获取动态信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zsy
     */
    public function getPost(Request $request)
    {
        $posts['posts'] = collect();
        $post = createRequest('GET', '/api/v2/groups/'.$request->group_id.'/posts/'.$request->post_id);
        $posts['posts']->push($post);
        $feedData = view('pcview::templates.group_posts', $posts, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $feedData
        ]);
    }

    public function postDetail(Request $request, $group_id, $post_id)
    {
        $this->PlusData['current'] = 'group';

        $data['post'] = createRequest('GET', '/api/v2/groups/'.$group_id.'/posts/'.$post_id);

        return view('pcview::group.post', $data, $this->PlusData);
    }

    public function comments(Request $request, $group_id, $post_id)
    {
        $params = [
            'after' => $request->query('after') ?: 0
        ];

        $comments['comments'] = createRequest('GET', '/api/v2/groups/'.$group_id.'/posts/'.$post_id.'/comments', $params);
        $comment = clone $comments['comments'];
        $after = $comment->pop()->id ?? 0;

        $comments['comments']->map(function($item){
            $item->user = $item->user;

            return $item;
        });
        $comments['top'] = false;
        $commentData = view('pcview::templates.comment', $comments, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $commentData,
            'after' => $after
        ]);
    }
}