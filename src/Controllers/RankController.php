<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Illuminate\Http\Request;
use Cache;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class RankController extends BaseController
{
    public function index(Request $request, int $mold = 1)
    {
        $data['mold'] = $mold;

        if ($mold == 1) {
            $data['follower'] = $this->rankCache('follower', '/api/v2/ranks/followers');
            $data['balance'] = $this->rankCache('balance', '/api/v2/ranks/balance');
            $data['income'] = $this->rankCache('income', '/api/v2/ranks/income');
            $data['check'] = $this->rankCache('check', '/api/v2/checkin-ranks');
            foreach ($data['check'] as &$v) {
                $v['extra']['count'] = $v['extra']['last_checkin_count'];
            }
            $data['experts'] = $this->rankCache('experts', '/api/v2/question-ranks/experts');
            $data['likes'] = $this->rankCache('experts', '/api/v2/question-ranks/likes');

        } elseif ($mold == 2) {
            $data['answers_day'] = $this->rankCache('answers_day', '/api/v2/question-ranks/answers');
            $data['answers_week'] = $this->rankCache('answers_week', '/api/v2/question-ranks/answers', ['type' => 'week']);
            $data['answers_month'] = $this->rankCache('answers_month', '/api/v2/question-ranks/answers', ['type' => 'month']);

        } elseif ($mold == 3) {
            $data['feeds_day'] = $this->rankCache('feeds_day', '/api/v2/feeds/ranks');
            $data['feeds_week'] = $this->rankCache('feeds_week', '/api/v2/feeds/ranks', ['type' => 'week']);
            $data['feeds_month'] = $this->rankCache('feeds_month', '/api/v2/feeds/ranks', ['type' => 'month']);

        } elseif ($mold == 4) {
            $data['news_day'] = $this->rankCache('news_day', '/api/v2/news/ranks');
            $data['news_week'] = $this->rankCache('news_week', '/api/v2/news/ranks', ['type' => 'week']);
            $data['news_month'] = $this->rankCache('news_month', '/api/v2/news/ranks', ['type' => 'month']);

        }

        return view('pcview::rank.index', $data, $this->PlusData);
    }

    public function _getRankList(Request $request)
    {
        $genre = $request->input('genre') ?: '';
        $offset = $request->input('offset') ?: 0;
        $limit = $request->input('limit') ?: 0;
        switch ($genre) {
            case 'follower':
                $tabName = '粉丝数';
                $data = $this->rankCache('follower', '/api/v2/ranks/followers', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'balance':
                $tabName = '';
                $data = $this->rankCache('balance', '/api/v2/ranks/balance', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'income':
                $tabName = '';
                $data = $this->rankCache('income', '/api/v2/ranks/income', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'check':
                $tabName = '';
                $data = $this->rankCache('check', '/api/v2/checkin-ranks', ['offset' => $offset, 'limit' => $limit]);
                foreach ($data as &$v) {
                    $v['extra']['count'] = $v['extra']['last_checkin_count'];
                }
                break;
            case 'experts':
                $tabName = '';
                $data = $this->rankCache('experts', '/api/v2/question-ranks/experts', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'likes':
                $tabName = '问答点赞量';
                $data = $this->rankCache('likes', '/api/v2/question-ranks/likes', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_day':
                $tabName = '问答量';
                $data = $this->rankCache('answers_day', '/api/v2/question-ranks/answers', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_week':
                $tabName = '问答量';
                $data = $this->rankCache('answers_week', '/api/v2/question-ranks/answers', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_month':
                $tabName = '问答量';
                $data = $this->rankCache('answers_month', '/api/v2/question-ranks/answers', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_day':
                $tabName = '点赞量';
                $data = $this->rankCache('feeds_day', '/api/v2/feeds/ranks', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_week':
                $tabName = '点赞量';
                $data = $this->rankCache('feeds_week', '/api/v2/feeds/ranks', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_month':
                $tabName = '点赞量';
                $data = $this->rankCache('feeds_month', '/api/v2/feeds/ranks', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_day':
                $tabName = '浏览量';
                $data = $this->rankCache('news_day', '/api/v2/news/ranks', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_week':
                $tabName = '浏览量';
                $data = $this->rankCache('news_week', '/api/v2/news/ranks', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_month':
                $tabName = '浏览量';
                $data = $this->rankCache('news_month', '/api/v2/news/ranks', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
        }

        $return['count'] = count($data);
        $return['nowPage'] = $offset / $limit + 1;

        $return['html'] = view('pcview::templates.rank_lists', [
            'post' => $data,
            'genre' => $genre,
            'tabName' => $tabName,
            'routes' => $this->PlusData['routes']
        ])
            ->render();

        return response()->json([
            'status'  => true,
            'data' => $return,
        ]);
    }

    public function rankCache($key, $url, $params = [], $time = 5, $type = 'GET')
    {
        $offset = isset($params['offset']) ? $params['offset'] : 0;
        Cache::has('rank_by_'.$key.'_offset_'.$offset)
            ? Cache::get('rank_by_'.$key.'_offset_'.$offset)
            : Cache::put('rank_by_'.$key.'_offset_'.$offset, createRequest($type, $url, $params), $time);

        return Cache::get('rank_by_'.$key.'_offset_'.$offset);
    }
}
