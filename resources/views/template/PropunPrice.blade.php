{{--<p style="font-size: 14px;"> Есть предложение цены по заказу <b>#{!! $order_id !!}</b></p>--}}

<div class="message-in">
    <div class="message-icon">
        <img src="{{asset('img/chat_message.svg')}}" alt="" />
    </div>
    <div class="message-content">
        <p>@lang('v.propun_price') <strong>#{!! $order_id !!}</strong>.</p>
        <span class="message-datetime">{{$created_at}}</span>
    </div>
</div>