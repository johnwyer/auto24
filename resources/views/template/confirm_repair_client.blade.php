<div class="message-in">
    <div class="message-icon">
        <img src="{{asset('img/chat_message.svg')}}" alt="" />
    </div>
    <div class="message-content">
        <p>@lang('v.confirm_record')<strong>#{!! $order_id !!}</strong>.</p>
        <span class="message-datetime">{{$created_at}}</span>
    </div>
</div>