{{--<p style="font-size: 14px;"> {!! $message !!} </p>--}}

<div class="message-in">
    <div class="message-icon">
        <img src="{{asset('img/chat_message.svg')}}" alt="" />
    </div>
    <div class="message-content">
        <p>{!! $message !!}</p>
        <span class="message-datetime">{{$created_at}}</span>
    </div>
</div>