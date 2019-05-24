<!DOCTYPE html>
<html lang="{{Lang::getLocale()}}">
    <head>
        <meta charset="UTF-8"/>
        <link rel='stylesheet' href='{{asset('css/pdf/main.css')}}' type='text/css' media='all' />
    </head>
    <body>
    <div id="wrapper" class="site">
        <div id="wrap-page-content">
            <div id="contents" class="page-content">
                <header>
                    <div class="header-content">
                        <div class="column">
                            <h1><img src="{{asset('images/pdf/logo.png')}}" /></h1>
                        </div>
                        <div class="column">
                            <div class="column-content">
                                <span>+ 373 (69) 123-456</span>
                                <div class="links-list">
                                    <a href="mailto:help@auto24.md" title="help@auto24.md">help@auto24.md</a> / <a href="https://auto24.md" title="">auto24.md</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="main-content">
                    <h2>@lang('v.direction_to_repair') #{{$order->number}}</h2>
                    <?php $dataController = new App\Http\Controllers\DateController() ?>

                    <p class="ta-center">{!! $dataController->getDatePDF($order->date) !!}</p>
                    <div class="table">
                        <div class="cell">@lang('v.number_review')</div>
                        <div class="cell">{{$order->number}}</div>
                    </div>

                    <div class="table">
                        <div class="cell">@lang('v.district_city')</div>
                        <div class="cell">{{$order->district}}, {{$order->city}}</div>
                    </div>
                    @if(!empty($order->propun_date) && !is_null($order->propun_date))
                        <div class="table">
                            <div class="cell">@lang('v.propun_date_repair')</div>
                            <div class="cell">{{$order->propun_date}}</div>
                        </div>
                    @endif
                    <div class="table">
                        <div class="cell">@lang('v.auto')</div>
                        <div class="cell">{{$order->marka}} {{$order->model}} {{$order->year}} {{$order->vin}}</div>
                    </div>
                    <div class="table">
                        <div class="cell">@lang('v.one_autoservice')</div>
                            <?php $i = 0;?>
                            <div class="cell">{{$request->name}}, {{$request->text}},<br /> @foreach($auto_phone as $phone) @if($i),@endif <?php $i++ ?> + 373 {{$phone->phone}} @endforeach</div>
                    </div>
                    <div class="table">
                        <div class="cell">@lang('v.price_repair')</div>
                        <div class="cell">{{$request->autoservice_price}} @lang('v.mdl')</div>
                    </div>
                    @if(!is_null($request->autoservice_parts) && !empty($request->autoservice_parts))
                    <div class="table">
                        <div class="cell">@lang('v.auto_parts')</div>
                        <div class="cell">{{$request->autoservice_parts}} @lang('v.mdl')</div>
                    </div>
                    @endif
                        <div class="table">
                            <div class="cell"><strong>@lang('v.total')</strong></div>
                            <div class="cell"><strong>{{$request->autoservice_price + $request->autoservice_parts}} @lang('v.mdl')</strong></div>
                        </div>
                    <div class="table">
                        <div class="cell large no-border">
                            <h3>@lang('v.list_required_work')</h3>
                        </div>
                    </div>
                            @foreach($service as $item)
                        <div class="table">
                            <div class="cell large no-border">
                                <h4>{{$item['name']}}</h4>
                            </div>
                        </div>
                                @if(isset($item['child']) && count($item['child']))
                                        @foreach($item['child'] as $child)
                                <div class="table">
                                    <div class="cell large no-border type-list">{{$child}}</div>
                                </div>
                                        @endforeach
                                @endif
                            @endforeach

{{--                    <div class="table">
                        <div class="cell large">
                            <h3>@lang('v.list_required_work')</h3>
                            @foreach($service as $item)
                                <h4>{{$item['name']}}</h4>
                                @if(isset($item['child']) && count($item['child']))
                                <ul>
                                    @foreach($item['child'] as $child)
                                        <li>{{$child}}</li>
                                    @endforeach
                                </ul>
                                @endif
                            @endforeach
                        </div>
                    </div>--}}
                    <br />
                    <div class="table">
                        <div class="cell with-border">@lang('v.request_autoservice')</div>
                        <div class="cell with-border">@if(!is_null($first_message))«{{$first_message->message}}, @else « @endif @lang('v.repair') - {{$request->autoservice_price}} @lang('v.mdl'), @lang('v.service') - {{$request->autoservice_parts}} @lang('v.mdl'), @lang('v.total') - {{$request->autoservice_parts +    $request->autoservice_price}} @lang('v.mdl')»</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>