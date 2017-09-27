<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class FeedController extends BaseController
{
    public function feeds(Request $request)
    {
        if($request->ajax()){
            if ($request->query('feed_id')){ // 获取单条微博内容
                $feeds['feeds'] = collect();
                $feed = createRequest('GET', '/api/v2/feeds/'.$request->feed_id);
                $feeds['feeds']->push($feed);
                $feedData = view('pcview::templates.feeds', $feeds, $this->PlusData)->render();

                return response()->json([
                        'status'  => true,
                        'data' => $feedData
                ]);
            } else { // 获取微博列表
                $params = [
                    'type' => $request->query('type'),
                    'after' => $request->query('after') ?: 0
                ];
                $feeds = createRequest('GET', '/api/v2/feeds', $params);
                if (!empty($feeds['pinned'])) { // 置顶动态
                    $feeds['pinned']->each(function ($item, $key) use ($feeds) {
                        $item->pinned = 1;
                        $feeds['feeds']->prepend($item);
                    });
                }

                $feed = clone $feeds['feeds'];
                $after = $feed->pop()->id ?? 0;
                $feedData = view('pcview::templates.feeds', $feeds, $this->PlusData)->render();

                return response()->json([
                        'status'  => true,
                        'data' => $feedData,
                        'after' => $after
                ]);
            }
        }

        // 渲染模板
        $data['type'] = $request->input('type') ?: ($this->PlusData['TS'] ? 'follow' : 'hot');

        $this->PlusData['current'] = 'feeds';
        return view('pcview::feed.index', $data, $this->PlusData);
    }

    public function read(Request $request, int $feed_id)
    {
        $feed = createRequest('GET', '/api/v2/feeds/'.$feed_id);
        $feed->collect_count = $feed->collection->count();
        $feed->rewards->map(function($reward){
            $reward->user = $reward->user;
        });
        $data['feed'] = $feed;
        $data['user'] = $feed->user;
        $data['user']['followers'] = $feed->user->followers()->count();
        $data['user']['followings'] = $feed->user->followings()->count();

        $this->PlusData['current'] = 'feeds';
        return view('pcview::feed.read', $data, $this->PlusData);
    }

    public function comments(Request $request, int $feed_id)
    {
        $params = [
            'after' => $request->query('after') ?: 0
        ];

        $comments = createRequest('GET', '/api/v2/feeds/'.$feed_id.'/comments', $params);
        $comment = clone $comments['comments'];
        $after = $comment->pop()->id ?? 0;

        $comments['comments']->map(function($item){
            $item->user = $item->user;

            return $item;
        });
        $commentData = view('pcview::templates.comment', $comments, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $commentData,
            'after' => $after
        ]);
    }
}
