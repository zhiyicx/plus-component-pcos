@if(!empty($post))
    @foreach($post as $postk=>$postv)
        <div rel="{{$genre}}div" @if($postk > 8) current="1" @endif>
            <li>
                @if(isset($postv['extra']))
                    <div class="fans_span1"><span @if($postv['extra']['rank'] <= 3) class="blue" @elseif($postv['extra']['rank'] >= 3 && $postv['extra']['rank'] <= 10) class="grey" @endif>{{$postv['extra']['rank']}}</span></div>
                    <div class="fans_span2 txt-hide">
                        <a href="{{ route('pc:mine', ['user_id'=>$postv['id']]) }}"><img src="{{ $postv['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=30" class="fans_img" /></a>
                        <a href="{{ route('pc:mine', ['user_id'=>$postv['id']]) }}">{{$postv['name']}}</a>
                    </div>
                    @if($tabName !== '')
                        <div class="fans_span3">{{$postv['extra']['count'] or 0}}</div>
                    @endif
                @endif
            </li>
        </div>
    @endforeach
@endif