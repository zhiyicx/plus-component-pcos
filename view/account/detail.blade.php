@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="account_container">
<div class="account_wrap">

{{-- 左侧导航 --}}
@include('pcview::account.sidebar')

<div class="account_r">
    <div class="account_c_c" id="J-warp">
        {{-- 订单详情 --}}
        <div class="order-detail">
            <div class="perfect_title">
                <p><a class="ucolor" href="">交易明细</a><font> > </font><a class="ucolor" href="">交易详情</a></p>
            </div>
            <div class="wallet-body">
                <div class="wallet-info clearfix">
                    <div class="sum">{{ $order->action==1 ? '+'.$order->amount/100 : '-'.$order->amount/100 }}</div>
                    <p class="ucolor">交易成功</p>
                </div>
                <div class="wallet-table">
                    <table class="table">
                        {{-- <tr class="row-tab">
                            <th class="ucolor" width="15%" align="left">收款人</th>
                            <td align="left">
                                <img src="" />
                                <span>艾萨拉</span>
                            </td>
                        </tr> --}}
                        <tr class="row-tab">
                            <th class="ucolor" width="15%" align="left">交易说明</th>
                            <td align="left"><p class="p">{{ $order->body }}</p></td>
                        </tr>
                        <tr class="row-tab">
                            <th class="ucolor" width="15%" align="left">创建时间</th>
                            <td align="left">{{ $order->created_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        {{-- /订单详情 --}}
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>

</script>
@endsection