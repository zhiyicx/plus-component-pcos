@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@if (isset($image['paid']) && !$image['paid'])
    <div class="locked_image" @if (isset($count) && $count == 'one') style="position:relative" @endif data-node="{{ $image['paid_node'] }}" data-amount="{{ $image['amount'] }}" data-file="{{ $image['file'] }}" data-original="{{ getImageUrl($image, $width, $height) }}">
	    <img src="{{ $routes['resource'] }}/images/pic_locked.png" class="feed_image_pay"/>
	    <svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-suo"></use></svg>
    </div>
@else
    {{-- 单张图片设置固定宽高 --}}
	@if (isset($count) && $count == 'one')
	@php
    $size = explode('x', $image['size']);
    if ($size[0] < $width && $size[1] < $height) { // 若宽高都小于展示高度，则保留原尺寸
        $w = $size[0];
        $h = $size[1];
    } else if ($size[0] > $size[1]) { // 宽大于高
        $w = $size[0] > $conw ? $conw : $size[0];
        $h = number_format(($width / $size[0] * $size[1]), 2, '.', '');
    } else if ($size[0] < $size[1]) { // 宽小于高
        $h = $size[1] > $conh ? $conh : $size[1];
        $w = number_format($height / $size[1] * $size[0], 2, '.', '');
    } else if ($size[0] == $size[1]) { // 宽高相同，取展示宽高中较小值
        $w = $h = $size[0] > $conh ? $conh : $size[0];
    }
    $style = 'width:' . $w . 'px;height:' . $h . 'px';
    @endphp
    <img style="{{ $style }}" class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
    @else
    <img class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
	@endif
@endif