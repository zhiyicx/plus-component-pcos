
@foreach ($datas as $data)
	<div class="qa-item">
	    <div class="qa-body mb20 clearfix">
	        @if (0)
	        	<img class="fl mr20" src="{{ asset('zhiyicx/plus-component-pc/images/pic_locked.png') }}" height="100">
	        @endif
	        <p class="tcolor margin0"><a href="{{ route('pc:answeread', $data->id) }}">{{ $data->body }}</p></a>
	    </div>
	    <div class="qa-toolbar feed_datas font14">
	        <span class="follow gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> {{ $data->comments_count }}评论
	        </span>
	        <span class="answer gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> 0 分享
	        </span>
	        <span class="mony gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> 编辑
	        </span>
	        <span class="options">
	            <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
	        </span>
	    </div>
	</div>
@endforeach