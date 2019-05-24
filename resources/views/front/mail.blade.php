<!DOCTYPE html>
<html lang="en">
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
                <h2>Направление на ремонт #{{$order->number}}</h2>
                <?php $dataController = new App\Http\Controllers\DateController() ?>

                <p class="ta-center">{!! $dataController->getDatePDF($order->date) !!}</p>
                <div class="table">
                    <div class="cell">Номер заявки</div>
                    <div class="cell">{{$order->number}}</div>
                </div>
                <div class="table">
                    <div class="cell">Регион, город</div>
                    <div class="cell">{{$order->district}}, {{$order->city}}</div>
                </div>
                <div class="table">
                    <div class="cell">Желаемая дата ремонта</div>
                    <div class="cell">{{$order->propun_date}}</div>
                </div>
                <div class="table">
                    <div class="cell">Автомобиль</div>
                    <div class="cell">{{$order->marka}} {{$order->model}} {{$order->year}} {{$order->vin}}</div>
                </div>
                <div class="table">
                    <div class="cell">Автосервис</div>
                    <?php $i = 0;?>
                    <div class="cell">{{$request->name}}, {{$request->text}},<br /> @foreach($auto_phone as $phone) @if($i),@endif <?php $i++ ?> + 373 {{$phone->phone}} @endforeach</div>
                </div>
                <div class="table">
                    <div class="cell">Стоимость ремонта</div>
                    <div class="cell">{{$request->autoservice_price}} лей</div>
                </div>
                @if(!is_null($request->autoservice_parts) && !empty($request->autoservice_parts))
                    <div class="table">
                        <div class="cell">Автозапчасти</div>
                        <div class="cell">{{$request->autoservice_parts}} лей</div>
                    </div>
                @endif
                <div class="table">
                    <div class="cell"><strong>Итого</strong></div>
                    <div class="cell"><strong>{{$request->autoservice_price + $request->autoservice_parts}} лей</strong></div>
                </div>

                <div class="table">
                    <div class="cell large">
                        <h3>Список требуемых работ</h3>
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
                </div>
                <div class="table">
                    <div class="cell">Ответ автосервиса</div>
                    <div class="cell">@if(!is_null($first_message))«{{$first_message->message}}, @else « @endif Ремонт - {{$request->autoservice_price}} лей, Запчасти - {{$request->autoservice_parts}} лей, Итого - {{$request->autoservice_parts +    $request->autoservice_price}} лей»</div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>