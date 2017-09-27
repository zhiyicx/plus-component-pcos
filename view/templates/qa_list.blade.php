
@foreach ($datas as $data)
	<div class="qa-item">
	    <h3 class="qa-title tcolor">{{ $data->subject }}</h3>
	    <div class="qa-toolbar font14">
	    @if ($data->anonymity)
	    	<a href="{{ route('pc:mine', $data->user->id) }}">
            	<img class="round mr10" src="{{ asset('zhiyicx/plus-component-pc/images/avatar.png') }}" width="30">
            </a>
	    	<span class="tcolor mr10">匿名</span>
	    @else
			<a href="{{ route('pc:mine', $data->user->id) }}">
            	<img class="round mr10" src="{{ $data->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" width="30">
            </a>
	        <span class="tcolor mr10">{{ $data->user->name }}</span>·&nbsp;&nbsp;
	        @if ($data->user->tags)
		        <span class="gcolor">
		        	@foreach ($data->user->tags as $k=>$tag)
		        		{{ $tag->name }}@if (!$loop->last) /@endif
		        	@endforeach
		        </span>
	        @endif
	        <span class="gcolor ctime fr">{{ $data->created_at }}</span>
	    @endif
	    </div>
	    <div class="qa-body mt20 mb20 clearfix">
	        @if (0)
	        	<img class="fl mr20" src="{{ asset('zhiyicx/plus-component-pc/images/pic_locked.png') }}" height="100">
	        @endif
	        <p class="tcolor margin0"><a href="{{ route('pc:answeread', $data->id) }}">{{ $data->body }}</p></a>
	    </div>
	    <div class="qa-toolbar feed_datas font14">
	        <span class="follow gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> 关注
	        </span>
	        <span class="answer gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> {{ $data->answers_count }}条 回答
	        </span>
	        <span class="mony gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> ￥{{ $data->amount/100 }}
	        </span>
	        <span class="options">
	            <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
	        </span>
	    </div>
	</div>
@endforeach