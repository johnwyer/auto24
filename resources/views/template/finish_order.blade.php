<div class="message-in">
    <div class="message-icon">
        <img src="{{asset('img/chat_message.svg')}}" alt="" />
    </div>
    <div class="message-content">
        <p>@lang('v.finish_rep_1') <strong>#{!! $order_id !!}</strong> @lang('v.finish_rep_2')</p>
        <span class="message-datetime">{{$created_at}}</span>
    </div>
</div>