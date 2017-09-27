<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceUrl;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class ProfileController extends BaseController
{
    public function feeds(Request $request, int $user_id = 0)
    {
        if ($request->ajax()) {
            $params = [
                'type' => $request->query('type'),
                'user' => $request->query('user'),
                'after' => $request->query('after', 0),
            ];
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1: //全部
                    $feeds = createRequest('GET', '/api/v2/feeds', $params);
                    $feed = clone $feeds['feeds'];
                    $after = $feed->pop()->id ?? 0;
                    $html = view('pcview::templates.profile_feed', $feeds, $this->PlusData)->render();
                    break;
                default:
                    # code...
                    break;
            }

            return response()->json(static::createJsonData([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]));
        }

        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();
        $data['user']->hasFollower = $data['user']->hasFollower($request->user());
        $this->PlusData['current'] = 'feeds';

        return view('pcview::profile.index', $data, $this->PlusData);
    }

    /**
     * 投稿文章.
     *
     * @param  Request $request
     * @return mixed
     */
    public function news(Request $request, int $user_id = 0)
    {
        if ($request->ajax()) {
            $params = [
                'type' => $request->query('type'),
                'after' => $request->query('after', 0)
            ];
            if ($request->query('user')) {
                $params['user'] = $request->query('user');
            }
            $news = createRequest('GET', '/api/v2/user/news/contributes', $params);
            $news->map(function($item){
                $item->collection_count = $item->collections->count();
            });
            $new = clone $news;
            $after = $new->pop()->id ?? 0;
            $data['data'] = $news;

            $html = view('pcview::templates.profile_news', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }

        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();
        $data['user']->hasFollower = $data['user']->hasFollower($request->user());
        $this->PlusData['current'] = 'news';
        $data['type'] = 0;

        return view('pcview::profile.news', $data, $this->PlusData);
    }

    /**
     * 个人收藏.
     *
     * @param  Request $request
     * @return mixed
     */
    public function collect(Request $request)
    {
        if ($request->ajax()) {
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1:
                    $params = [
                        'offset' => $request->query('offset', 0),
                        'limit' => $request->query('limit'),
                    ];
                    $feeds = createRequest('GET', '/api/v2/feeds/collections', $params);
                    $data['feeds'] = $feeds;
                    $after = 0;
                    $html = view('pcview::templates.collect_feeds', $data, $this->PlusData)->render();
                    break;
                case 2:
                    $params = [
                        'after' => $request->query('after', 0),
                        'limit' => $request->query('limit', 10),
                    ];
                    $news = createRequest('GET', '/api/v2/news/collections', $params);
                    $news->map(function($item){
                        $item->collection_count = $item->collections->count();
                        $item->comment_count = $item->comments->count();
                    });
                    $new = clone $news;
                    $after = $new->pop()->id ?? 0;
                    $data['data'] = $news;
                    $html = view('pcview::templates.profile_news', $data, $this->PlusData)->render();
                    break;
                default:
                    # code...
                    break;
            }

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }

        $data['user'] = $this->PlusData['TS'];
        $this->PlusData['current'] = 'collect';
        $data['type'] = 0;

        return view('pcview::profile.collect', $data, $this->PlusData);
    }

    /**
     * 加入的圈子.
     *
     * @param  Request     $request
     * @param  int|integer $user_id
     * @return mixed
     */
    public function group(Request $request, int $user_id = 0)
    {
        if ($request->ajax()) {
            $params = [
                'after' => $request->query('after', 0),
                'user' => $request->query('user', 0),
            ];
            $groups = createRequest('GET', '/api/v2/groups', $params);
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

        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();
        $data['user']->hasFollower = $data['user']->hasFollower($request->user());
        $this->PlusData['current'] = 'group';

        return view('pcview::profile.group', $data, $this->PlusData);
    }

    /**
     * 问答信息.
     *
     * @param  Request     $request
     * @param  int|integer $user_id
     * @return mixed
     */
    public function question(Request $request, int $user_id = 0)
    {
        if ($request->ajax()) {
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1:
                    $params = [
                        'after' => $request->query('after', 0),
                        'type' => $request->query('type', 'all'),
                    ];
                    $questions = createRequest('GET', '/api/v2/user/questions', $params);
                    $question = clone $questions;
                    $after = $question->pop()->id ?? 0;
                    $data['datas'] = $questions;
                    $html = view('pcview::templates.qa_list', $data, $this->PlusData)->render();

                    break;
                case 2:
                    $params = [
                        'after' => $request->query('after', 0),
                        'type' => $request->query('type', 'all'),
                    ];
                    $answers = createRequest('GET', '/api/v2/user/question-answer', $params);
                    $answer = clone $answers;
                    $after = $answer->pop()->id ?? 0;
                    $data['datas'] = $answers;
                    $html = view('pcview::templates.answer', $data, $this->PlusData)->render();
                    break;
                case 3:
                    $params = [
                        'offset' => $request->query('offset', 0),
                        'limit' => $request->query('limit'),
                    ];
                    $watches = createRequest('GET', '/api/v2/user/question-watches', $params);
                    $data['datas'] = $watches;
                    $after = 0;
                    $html = view('pcview::templates.qa_list', $data, $this->PlusData)->render();
                    break;
                case 4:
                    $params = [
                        'after' => $request->query('after', 0),
                        'type' => $request->query('type', 'follow'),
                    ];
                    $topics = createRequest('GET', '/api/v2/user/question-topics', $params);
                    $topics->map(function($item){
                        $item->has_follow = true;
                    });
                    $topic = clone $topics;
                    $after = $topic->pop()->id ?? 0;
                    $data['data'] = $topics;
                    $html = view('pcview::templates.topic', $data, $this->PlusData)->render();
                    break;
            }

            return response()->json([
                'data' => $html,
                'after' => $after
            ]);
        }
        $data['data'] = createRequest('GET', '/api/v2/user/questions');
        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();
        $this->PlusData['current'] = 'question';

        return view('pcview::profile.question', $data, $this->PlusData);
    }
}
