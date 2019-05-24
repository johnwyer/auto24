{{--<p style="font-size: 14px;"> Статус заказа <b>#{!! $order_id!!}</b> был изменён! </p>--}}

<div class="message-in">
    <div class="message-icon">
        <img src="{{asset('img/accept-circular-button-outline.svg')}}" alt="" />
    </div>
    <div class="message-content">
        <p>@lang('v.status_rev') <strong>#{!! $order_id !!}</strong> @lang('v.will_change')</p>
        <span class="message-datetime">{{$created_at}}</span>
    </div>
</div>