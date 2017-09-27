<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class Ads
{
    public function compose(View $view)
    {
    	// 获取所有广告位
        $spaces = createRequest('GET', '/api/v2/advertisingspace')->keyBy('space');

        // 获取具体广告位内容
        $ads = createRequest('GET', '/api/v2/advertisingspace/' . $spaces[$view['space']]['id'] . '/advertising')->pluck('data');

        $view->with('ads', $ads);
    }
}