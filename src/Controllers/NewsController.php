<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getShort;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceImage;

class NewsController extends BaseController
{
    /**
     * 文章首页
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $this->PlusData['current'] = 'news';

        // 资讯分类
        $cates = createRequest('GET', '/api/v2/news/cates');
        $data['cates'] = array_merge($cates['my_cates'], $cates['more_cates']);

        $data['cate_id'] = $request->query('cate_id') ?: 0;

        return view('pcview::news.index', $data, $this->PlusData);
    }

    /**
     * 资讯列表.
     *
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function list(Request $request)
    {
        $params = [
            'cate_id' => $request->query('cate_id'),
            'after' => $request->query('after', 0)
        ];

        // 获取资讯列表
        $news['news'] = createRequest('GET', '/api/v2/news', $params);
        $new = clone $news['news'];
        $after = $new->pop()->id ?? 0;
        $news['cate_id'] = $params['cate_id'];
        $newsData = view('pcview::templates.news', $news, $this->PlusData)->render();

        return response()->json([
                'status'  => true,
                'data' => $newsData,
                'after' => $after
        ]);
    }

    /**
     * 文章详情页
     */
    public function read(int $news_id)
    {
        $this->PlusData['current'] = 'news';

        // 获取资讯详情
        $news = createRequest('GET', '/api/v2/news/' . $news_id);
        $news->reward = createRequest('GET', '/api/v2/news/' . $news_id . '/rewards/sum');
        $news->collect_count = $news->collections->count();
        $news->content = replaceImage($news->content);

        // 相关资讯
        $news_rel = createRequest('GET', '/api/v2/news/' . $news_id . '/correlations');

        $data['news'] = $news;
        $data['news_rel'] = $news_rel;
        return view('pcview::news.read', $data, $this->PlusData);
    }

    /**
     * 文章投稿页面
     */
    public function release(Request $request, int $news_id = 0)
    {
        $this->PlusData['current'] = 'news';
        // 发稿提示
        $notice['verified'] = json_encode($this->PlusData['TS']['verified']);
        $notice['balance'] = $this->PlusData['TS']['wallet']['balance'];
        $notice['contribute'] = json_encode($this->PlusData['config']['bootstrappers']['news:contribute']);
        $notice['pay_conyribute'] = $this->PlusData['config']['bootstrappers']['news:pay_conyribute'];

        // 资讯分类
        $cates = createRequest('GET', '/api/v2/news/cates');
        $data['news_id'] = $news_id;
        $data['cates'] = array_merge($cates['my_cates'], $cates['more_cates']);
        // 标签
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        $data['notice'] = $notice;

        return view('pcview::news.release', $data, $this->PlusData);
    }

    /**
     * 文章详情评论列表.
     *
     * @param  Illuminate\Http\Request $request
     * @param  int     $news_id
     * @return mixed
     */
    public function comments(Request $request, int $news_id)
    {
        $params = [
            'after' => $request->query('after') ?: 0
        ];

        $comments = createRequest('GET', '/api/v2/news/'.$news_id.'/comments', $params);
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
