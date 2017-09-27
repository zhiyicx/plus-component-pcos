<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class MessageController extends BaseController
{
	public function index(Request $request)
	{
		$data['type'] = 'pl';

		// 获取最新评论
        $comment = createRequest('GET', '/api/v2/user/comments', ['after' => 0, 'limit' => 1]);
        !$comment->isEmpty() && $data['message']['comment'] = $comment[0]['user']['name'];

        // 获取最新点赞
        $like = createRequest('GET', '/api/v2/user/likes', ['after' => 0, 'limit' => 1]);
        !$like->isEmpty() && $data['message']['like'] = $like[0]['user']['name'];

        // 获取通知
        $notifications = createRequest('GET', '/api/v2/user/notifications', ['offset' => 0, 'limit' => 1]);
        !$notifications->isEmpty() && $data['message']['notification'] = $notifications[0]['data']['content'];

		return view('pcview::message.message', $data, $this->PlusData);
	}

    /**
     * 评论消息列表.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(Request $request)
    {
        $after = $request->input('after') ?: 0;
        $limit = $request->input('limit') ?: 20;
        $data['comments'] = createRequest('GET', '/api/v2/user/comments', ['after' => $after, 'limit' => $limit]);

        $return = '';
        if (!$data['comments']->isEmpty()) {
            foreach ($data['comments'] as $v) {
                switch ($v['commentable_type']) {
                    case 'feeds':
                        $v['source_type'] = '评论了你的动态';
                        $v['source_url'] = Route('pc:feedread', $v['commentable']['id']);
                        $v['source_content'] = $v['commentable']['feed_content'];
                        count($v['commentable']['images']) > 0 && $v['source_img'] = $this->PlusData['routes']['storage'].$v['commentable']['images'][0]['id'].'?w=35&h=35';
                        break;
                    case 'group-posts':
                        $v['source_type'] = '评论了你的圈子';
                        $v['source_url'] = Route('pc:grouppost', ['group_id' => $v['commentable']['group_id'], 'post_id' => $v['commentable']['id']]);
                        $v['source_content'] = $v['commentable']['content'];
                        count($v['commentable']['images']) > 0 && $v['source_img'] = $this->PlusData['routes']['storage'].$v['commentable']['images'][0]['id'].'?w=35&h=35';
                        break;
                    case 'news':
                        $v['source_type'] = '评论了你的文章';
                        $v['source_url'] = Route('pc:newsread', $v['commentable']['id']);
                        $v['source_content'] = $v['commentable']['subject'];
                        $v['commentable']['image'] && $v['source_img'] = $this->PlusData['routes']['storage'].$v['commentable']['image']['id'].'?w=35&h=35';
                        break;
                }
            }

            $return = view('pcview::message.comments', $data, $this->PlusData)->render();
        }

        return response()->json([
            'status'  => true,
            'data' => $return,
            'count' => $data['comments']->count(),
            'after' => $data['comments']->pop()->id ?? 0,
        ]);
    }

    /**
     * 点赞消息列表.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likes(Request $request)
    {
        $after = $request->input('after') ?: 0;
        $limit = $request->input('limit') ?: 20;
        $data['likes'] = createRequest('GET', '/api/v2/user/likes', ['after' => $after, 'limit' => $limit]);
        $return = '';
        if (!$data['likes']->isEmpty()) {
            foreach ($data['likes'] as $v) {
                switch ($v['likeable_type']) {
                    case 'feeds':
                        $v['source_type'] = '赞了你的动态';
                        $v['source_url'] = Route('pc:feedread', $v['likeable']['id']);
                        $v['source_content'] = $v['likeable']['feed_content'];
                        count($v['commentable']['images']) > 0 && $v['source_img'] = $this->PlusData['routes']['storage'].$v['likeable']['images'][0]['id'].'?w=35&h=35';
                        break;
                    case 'group-posts':
                        $v['source_type'] = '赞了你的圈子';
                        $v['source_url'] = Route('pc:grouppost', ['group_id' => $v['likeable']['group_id'], 'post_id' => $v['likeable']['id']]);
                        $v['source_content'] = $v['likeable']['content'];
                        count($v['likeable']['images']) > 0 && $v['source_img'] = $this->PlusData['routes']['storage'].$v['likeable']['images'][0]['id'].'?w=35&h=35';
                        break;
                    case 'news':
                        $v['source_type'] = '赞了你的文章';
                        $v['source_url'] = Route('pc:newsread', $v['likeable']['id']);
                        $v['source_content'] = $v['likeable']['subject'];
                        $v['likeable']['image'] && $v['source_img'] = $this->PlusData['routes']['storage'].$v['likeable']['image']['id'].'?w=35&h=35';
                        break;
                }
            }

            $return = view('pcview::message.likes', $data, $this->PlusData)->render();
        }

        return response()->json([
            'status'  => true,
            'data' => $return,
            'count' => $data['likes']->count(),
            'after' => $data['likes']->pop()->id ?? 0
        ]);
    }

    /**
     * 通知消息列表.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifications(Request $request)
    {
        $offset = $request->input('offset') ?: 0;
        $limit = $request->input('limit') ?: 20;
        $data['notifications'] = createRequest('GET', '/api/v2/user/notifications', ['offset' => $offset, 'limit' => $limit]);
        $return = '';
        if (!$data['notifications']->isEmpty()) {
            $return = view('pcview::message.notifications', $data, $this->PlusData)->render();
        }

        return response()->json([
            'status'  => true,
            'data' => $return,
            'count' => $data['notifications']->count(),
        ]);
    }

    public function feedCommentTop(Request $request)
    {
        $after = $request->input('after') ?: 0;
        $limit = $request->input('limit') ?: 20;
        $data['comments'] = createRequest('GET', '/api/v2/user/feed-comment-pinneds', ['after' => $after, 'limit' => $limit]);

        $return = view('pcview::message.feedcomment_top', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $return,
            'count' => $data['comments']->count(),
            'after' => $data['comments']->pop()->id ?? 0
        ]);
    }

    public function newsCommentTop(Request $request)
    {
        $after = $request->input('after') ?: 0;
        $limit = $request->input('limit') ?: 20;
        $data['comments'] = createRequest('GET', '/api/v2/news/comments/pinneds', ['after' => $after, 'limit' => $limit]);

        $return = view('pcview::message.feedcomment_top', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $return,
            'count' => $data['comments']->count(),
            'after' => $data['comments']->pop()->id ?? 0
        ]);
    }

}
