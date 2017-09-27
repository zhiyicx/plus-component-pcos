<?php

use Zhiyi\Plus\Http\Middleware;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Middleware as PcMiddleware;

Route::prefix('passport')->group(function () {
    // 登录
    Route::get('/login', 'PassportController@index')->name('pc:login');

    // 登录成功记录token
    Route::get('/token/{token}/{type}', 'PassportController@token')->name('pc:token');

    // 登出
    Route::any('/logout', 'PassportController@logout')->name('pc:logout');

    // 注册
    Route::get('/register/{type?}', 'PassportController@register')->where(['type' => '[0-9]+'])->name('pc:register');

    // 获取验证码
    Route::get('/captcha/{tmp}', 'PassportController@captcha')->name('pc:captcha');

    // 检验验证码
    Route::post('/checkcaptcha', 'PassportController@checkCaptcha')->name('pc:checkcaptcha');

    // 找回密码
    Route::get('/findpwd/{type?}', 'PassportController@findPassword')->where(['type' => '[0-9]+'])->name('pc:findpassword');

    // 完善资料
    Route::get('/perfect', 'PassportController@perfect')->name('pc:perfect');
});

Route::prefix('feeds')->group(function () {
    // 动态首页
    Route::get('/', 'FeedController@feeds')->name('pc:feeds');

    // 动态详情
    Route::get('/{feed}', 'FeedController@read')->where(['feed' => '[0-9]+'])->name('pc:feedread');

    // 动态详情获取评论
    Route::get('/{feed}/comments', 'FeedController@comments')->where(['feed' => '[0-9]+']);
});

Route::prefix('question')->group(function () {
    // 问答
    Route::get('/', 'QuestionController@question')->name('pc:question');

    Route::get('/{question_id}', 'QuestionController@read')->where(['question_id' => '[0-9]+'])->name('pc:questionread');

    Route::get('topic', 'QuestionController@topic')->name('pc:topic');

    Route::get('answer/{answer}', 'QuestionController@answer')->name('pc:answeread');

    Route::get('answer/{answer}/comments', 'QuestionController@answerComments');

    Route::get('create', 'QuestionController@createQuestion')->name('pc:createquestion');
    Route::get('users', 'QuestionController@getUsers')->name('pc:questionusers');
});

Route::prefix('rank')->group(function () {
    // 排行榜
    Route::get('/{mold?}', 'RankController@index')->where(['mold' => '[0-9]+'])->name('pc:rank');

    // 获取排行榜列表
    Route::get('/rankList', 'RankController@_getRankList')->name('pc:ranklist');
});

Route::prefix('account')->middleware(PcMiddleware\CheckLogin::class)->group(function () {
    // 基本设置
    Route::get('/index', 'AccountController@index')->name('pc:account');

    // 认证
    Route::get('/authenticate', 'AccountController@authenticate')->name('pc:authenticate');

    //更新认证信息
    Route::get('update/authenticate', 'AccountController@updateAuthenticate')->name('pc:update_authenticate');

    // 提交认证
    Route::post('/authenticate', 'AccountController@doAuthenticate');

    // 标签管理
    Route::get('/tags', 'AccountController@tags')->name('pc:tags');

    // 安全设置
    Route::get('/security', 'AccountController@security')->name('pc:security');

    // 我的钱包
    Route::get('/wallet/{type?}', 'AccountController@wallet')->where(['type' => '[1-5]'])->name('pc:wallet');

    // 交易明细
    Route::get('/wallet/records', 'AccountController@records')->name('pc:walletrecords');

    // 交易明细
    Route::get('/wallet/record/{record_id}', 'AccountController@record')->name('pc:walletrecord');

    // 充值
    Route::get('/wallet/pay', 'AccountController@pay')->name('pc:walletpay');

    // 跳转充值
    Route::get('/wallet/payto', 'AccountController@payto')->name('pc:walletpayto');

    // 提现
    Route::get('/wallet/draw', 'AccountController@draw')->name('pc:walletdraw');

    // 获取我绑定信息
    Route::get('/binds', 'AccountController@getMyBinds')->name('pc:binds');

    Route::get('/success', 'AccountController@successCon')->name('pc:success');
});

Route::prefix('profile')->middleware(PcMiddleware\CheckLogin::class)->group(function () {

    // 动态
    Route::get('/{user?}', 'ProfileController@feeds')->where(['user' => '[0-9]+'])->name('pc:mine');

    // 资讯
    Route::get('news/{user?}', 'ProfileController@news')->name('pc:profilenews');

    // 收藏
    Route::get('collect', 'ProfileController@collect')->name('pc:profilecollect');

    //圈子
    Route::get('group/{user?}', 'ProfileController@group')->name('pc:profilegroup');

    Route::get('question/{user?}', 'ProfileController@question')->name('pc:profilequestion');
});

Route::prefix('users')->group(function () {
    // 找人
    Route::get('/{type?}', 'UserController@users')->where(['type' => '[1-4]'])->name('pc:users');

    // 粉丝关注
    // Route::middleware(PcMiddleware\CheckLogin::class)->get('/follows/{type?}/{user_id?}', 'UserController@follows')->where(['type' => '[1-2]', 'user_id' => '[0-9+]'])->name('pc:follows');
    Route::middleware(PcMiddleware\CheckLogin::class)->get('/follows/{type?}/{user_id?}', 'UserController@follows')->where(['type' => '[1-2]'])->name('pc:follows');
});


Route::prefix('news')->group(function () {
    // 资讯
    Route::get('/', 'NewsController@index')->name('pc:news');

    // 获取资讯列表
    Route::get('/list', 'NewsController@list');

    // 资讯详情
    Route::get('/{news_id}', 'NewsController@read')->where(['news_id' => '[0-9]+'])->name('pc:newsread');

    // 文章详情评论
    Route::get('/{news_id}/comments', 'NewsController@comments')->where(['news_id' => '[0-9]+']);

    // 投稿
    Route::middleware(PcMiddleware\CheckLogin::class)->get('/release/{news_id?}', 'NewsController@release')->name('pc:newsrelease');
});

Route::prefix('webmessage')->group(function () {
    Route::get('/', 'MessageController@index')->name('pc:webmessage');
    // 评论我的列表
    Route::get('/comments', 'MessageController@comments')->name('pc:webmessagecomments');
    // 点赞我的列表
    Route::get('/likes', 'MessageController@likes')->name('pc:webmessagelikes');
    // 通知列表
    Route::get('/notifications', 'MessageController@notifications')->name('pc:webmessagenotifications');
    // 动态评论置顶消息列表
    Route::get('/feedCommentTop', 'MessageController@feedCommentTop')->name('pc:webmessagefeedcomment');
    // 文章评论置顶消息列表
    Route::get('/newsCommentTop', 'MessageController@newsCommentTop')->name('pc:webmessagenewscomment');

});

Route::prefix('group')->group(function () {
    // 圈子列表
    Route::get('/', 'GroupController@index')->name('pc:group');

    // 圈子详情
    Route::get('/{group_id?}', 'GroupController@read')->where(['group_id' => '[1-9]+'])->name('pc:groupread');

    // 获取圈子列表
    Route::get('list', 'GroupController@list');

    // 获取圈子动态列表
    Route::get('postLists', 'GroupController@postLists');

    // 获取单条圈子动态信息
    Route::get('getPost', 'GroupController@getPost');

    // 圈子动态详情
    Route::get('/{group_id}/post/{post_id}', 'GroupController@postDetail')->where(['group_id' => '[0-9]+', 'post_id' => '[0-9]+'])->name('pc:grouppost');

    // 圈子动态获取评论列表
    Route::get('/{group_id}/post/{post_id}/comments', 'GroupController@comments')->where(['group_id' => '[0-9]+', 'post_id' => '[0-9]+']);

});

Route::prefix('search')->group(function () {
    Route::get('/{type?}/{keywords?}', 'SearchController@index')->where(['type' => '[1-6]+'])->name('pc:search');
    Route::get('/data', 'SearchController@getData');
});

// 三方用户信息授权
Route::prefix('socialite')->group(function () {

    // 三方获取信息跳转
    Route::get('/{service}', 'SocialiteController@redirectToProvider')->where(['service' => 'weibo|qq|wechat'])->name('pc:socialite');
    Route::get('/{service}/bind', 'SocialiteController@redirectToProviderByBind')->where(['service' => 'weibo|qq|wechat'])->name('pc:socialitebind');

    // 三方回调
    Route::get('/{service}/callback', 'SocialiteController@handleProviderCallback')->where(['service' => 'weibo|qq|wechat']);

    Route::post('/', 'SocialiteController@bind')->name('pc:socialitebind');
});


