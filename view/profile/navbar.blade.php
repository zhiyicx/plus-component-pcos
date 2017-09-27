{{-- 个人中心头部个人信息 --}}
<div class="profile_top">
    <div class="profile_top_cover">
        <img class="user_bg" src="{{ $user->bg or asset('zhiyicx/plus-component-pc/images/default_cover.png') }}"/>
    </div>

    @if ($user->id == $TS->id)
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="change_cover" onclick="$('#cover').click()">更换封面</span>
    @endif

    <div class="profile_top_info">
        <div class="profile_top_img relative fl">
            <a href="{{ route('pc:mine', $user->id) }}">
                <img class="round" src="{{ $user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=160"/>
                @if($user->verified)
                    <img class="role-icon" src="{{ $user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                @endif
            </a>
        </div>
        <div class="profile_top_info_d">
            <div class="profile_top_user">
                <a href="{{ route('pc:mine', $user->id) }}">{{ $user->name }}</a>
                <span>{{$user->location or '未知'}}</span>
            </div>
            <div class="profile_top_bio">{{ $user->bio or '这家伙很懒，什么都没留下'}}</div>
            <div class="profile_top_tags">
                @foreach ($user->tags as $tag)
                    <span>{{$tag->name}}</span>
                @endforeach
            </div>
            @if ($user->verified)
                <div class="profile_logo_icon">
                    <span><i class="tag_icon"></i>已认证：TS团队成员</span>
                </div>
            @endif
        </div>
    </div>

    {{-- 个人中心导航栏 --}}
    <div class="profile_nav clearfix">
        @if ($TS->id == $user->id)
            <ul class="profile_nav_list clearfix">
                <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user->id) }}">主页</a></li>

                <li @if($current == 'group') class="active" @endif><a href="{{ route('pc:profilegroup') }}">圈子</a></li>

                {{-- <li @if($current == 'question') class="active" @endif><a href="{{ route('pc:profilequestion') }}">问答</a></li> --}}

                <li @if($current == 'news') class="active" @endif><a href="{{ route('pc:profilenews') }}">资讯</a></li>

                <li @if($current == 'collect') class="active" @endif><a href="{{ route('pc:profilecollect') }}">收藏</a></li>
            </ul>

            <a class="btn btn-primary contribute-btn" href="{{ route('pc:newsrelease') }}">
                <svg class="icon"><use xlink:href="#icon-feiji"></use></svg>投稿
            </a>
        @else
            <ul class="profile_nav_list clearfix">
                <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user->id) }}">TA的主页</a></li>

                <li @if($current == 'group') class="active" @endif><a href="{{ route('pc:profilegroup', $user->id) }}">TA的圈子</a></li>

                <li @if($current == 'news') class="active" @endif><a href="{{ route('pc:profilenews', $user->id) }}">TA的文章</a></li>
            </ul>
            <div class="follow-box">
                @if ($user->hasFollower == 0)
                    <div id="follow" status="0" class="tcolor">+关注</div>
                @else
                    <div id="follow" status="1" class="followed">已关注</div>
                @endif
            </div>
        @endif
    </div>
</div>