@if($post->images)
    <div id="layer-photos-demo{{$post->id}}">
    @if($post->images->count() == 1)
        @php
            // 单张图片默认展示宽高
            $conw = isset($conw) ? $conw : 555;
            $conh = isset($conh) ? $conh : 400;
        @endphp
        @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => $conw, 'height' => $conh, 'count' => 'one'])
    @elseif($post->images->count() == 2)
        <div style="width: 100%; display: flex;">
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 277, 'height' => 273])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 277, 'height' => 273])
            </div>
        </div>
    @elseif($post->images->count() == 3)
        <div style="width: 100%; display: flex;">
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 184, 'height' => 180])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 184, 'height' => 180])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 184, 'height' => 180])
            </div>
        </div>
    @elseif($post->images->count() == 4)
        <div style="width: 100%; display: flex;">
            <div style="width: 50%">
                <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 277, 'height' => 273])
                </div>
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 277, 'height' => 273])
              </div>
            </div>
            <div style="width: 50%">
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 277, 'height' => 273])
              </div>
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 277, 'height' => 273])
              </div>
            </div>
        </div>
    @elseif($post->images->count() == 5)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 66.6666%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 370, 'height' => 366])
            </div>
            <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 183])
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 183])
                </div>
            </div>
            <div style="width: 100%; display: flex;">
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 277, 'height' => 273])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 277, 'height' => 273])
                </div>
            </div>
        </div>
    @elseif($post->images->count() == 6)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 66.6666%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 370, 'height' => 366])
            </div>
            <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 183])
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 183])
                </div>
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 185, 'height' => 183])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 185, 'height' => 183])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 185, 'height' => 183])
            </div>
        </div>
    @elseif($post->images->count() == 7)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 50%">
                <div style="width: 100%" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 277, 'height' => 273])
                </div>
                <div style="width: 100%" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 277, 'height' => 273])
                </div>
            </div>
            <div style="width: 50%; display: flex; flex-wrap: wrap;">
                <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 138, 'height' => 135])
                </div>
                <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 138, 'height' => 135])
                </div>
                <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 277, 'height' => 273])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 138, 'height' => 135])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[6], 'width' => 138, 'height' => 135])
                </div>
            </div>
        </div>
    @elseif($post->images->count() == 8)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 185, 'height' => 183])
            </div>
            <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 183])
            </div>
            <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 183])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 277, 'height' => 273])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 277, 'height' => 273])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 185, 'height' => 183])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[6], 'width' => 185, 'height' => 183])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[7], 'width' => 185, 'height' => 183])
            </div>
        </div>
    @elseif($post->images->count() == 9)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[6], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[7], 'width' => 185, 'height' => 181])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[8], 'width' => 185, 'height' => 181])
            </div>
        </div>
    @endif
    </div>
@endif