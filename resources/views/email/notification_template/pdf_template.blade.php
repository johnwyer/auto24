<!DOCTYPE html>
<html lang="en"
      style="font-size: 100%; font-family: Roboto, Helvetica, sans-serif; position: relative; width: 100%; height: 100%; box-sizing: border-box;">
<head>
    <meta charset="UTF-8"/>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=cyrillic' type='text/css' media='all'/>
    {{--<link rel='stylesheet' href='{{asset('css/pdf/main.css')}}' type='text/css' media='all' />--}}
    <style>

    </style>
</head>
<body style="font-size:100%; font-family:Roboto, Helvetica, sans-serif; position:relative; width:100%; height:100%; margin:0; box-sizing:border-box; transition: all 0.25s ease-in;">
<table style="font-size:100%; font-family:Roboto, Helvetica, sans-serif; width:100%; height:100%; background-color:#fff; color:#000000; border-collapse:collapse; margin:0; padding:0; transition: all 0.25s ease-in;"
       align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td align="center" style="width:100%; text-align:center; padding:0 20px 50px 20px; vertical-align:top;">
            <table style="max-width:935px; margin:0 auto; background-color:#fff; border-collapse:collapse; padding:0;"
                   width="935" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td style="width:100%; height:178px; text-align:center; margin:0; padding:0 28px; border-bottom:5px solid #f2f2f2;">
                        <table style="color:#000; font-size:18px; line-height:1.7; width:100%; background-color:#fff; border-collapse:collapse; margin:0; padding:0;"
                               width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td style="width:50%; height:173px; text-align:left; margin:0; padding:0 0 0 20px; vertical-align:middle;">
                                    <a href="https://auto24.md" title=""><img src="{{asset('images/pdf/logo.png')}}" /></a>
                                </td>
                                <td style="width:50%; height:173px; text-align:right; margin:0; padding:0 20px 0 0; vertical-align:middle;">
                                    <p style="margin:0; padding:0; line-height:1.7;">+ 373 (69) 123-456</p>
                                    <p style="margin:0; padding:0; line-height:1.7; color:#a3a3a3;">
                                        <a style="color:#0363cd; text-decoration:underline;" href="mailto:help@auto24.md" title="help@auto24.md">help@auto24.md</a> / <a style="color: #0363cd;text-decoration: underline;" href="https://auto24.md" title="">auto24.md</a>
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:center; margin:0; padding:0 28px;">
                        <table style="color:#000; font-size:17px; line-height:1.5; width:100%; background-color:#fff; border-collapse: collapse; margin:0; padding:0;"
                               width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td style="width:100%; text-align:center; margin:0; padding:0;">
                                    <h2 style="font-size:40px;font-weight:700;margin:20px 0 0 0;padding:0;">@lang('v.direction_to_repair') #{{$order->number}}</h2>
                                    <?php $dataController = new App\Http\Controllers\DateController() ?>
                                    <p style="font-size:17px;margin:0 0 40px 0;padding:0;">{!! $dataController->getDatePDF($order->date) !!}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:center; margin:0; padding:0 28px;">
                        <table style="color:#000; font-size:17px; line-height:1.5; width:100%; background-color:#fff; border-collapse: collapse; margin:0; padding:0;"
                               width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2; border-top:2px solid #f2f2f2;">Номер заявки</td>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2; border-top:2px solid #f2f2f2;">{{$order->number}}</td>
                                </tr>
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">Регион, город</td>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">{{$order->district}}, {{$order->city}}</td>
                                </tr>
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">Желаемая дата ремонта</td>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">{{$order->propun_date}}</td>
                                </tr>
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">Автомобиль</td>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">{{$order->marka}} {{$order->model}} {{$order->year}} {{$order->vin}}</td>
                                </tr>
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">Автосервис</td>
                                    <?php $i = 0;?>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">{{$request->name}}, {{$request->text}},<br /> @foreach($auto_phone as $phone) @if($i),@endif <?php $i++ ?> + 373 {{$phone->phone}} @endforeach</td>
                                </tr>
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">Стоимость ремонта</td>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">{{$request->autoservice_price}} лей</td>
                                </tr>
                                @if(!is_null($request->autoservice_parts) && !empty($request->autoservice_parts))
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">Автозапчасти</td>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;">{{$request->autoservice_parts}} лей</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="width:40%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;"><strong>Итого</strong></td>
                                    <td style="width:60%; text-align:left; margin:0; padding:6px 20px 6px 20px; border-bottom: 2px solid #f2f2f2;"><strong>{{$request->autoservice_price + $request->autoservice_parts}} лей</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:left; margin:0; padding:0 28px;">
                        <table style="color:#000; font-size:17px; line-height:1.5; width:100%; background-color:#fff; border-collapse: collapse; margin:0; padding:0;" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td style="width:100%; text-align:left; line-height:1.75; margin:0; padding:2px 20px 21px 20px;border-bottom: 2px solid #f2f2f2;">
                                    <h3 style="font-size:22px;font-weight:700;margin:7px 0 5px 0;padding:0;">Список требуемых работ</h3>
                                    <?php $counter = 0; ?>
                                    @foreach($service as $item)
                                        @if($counter == 0)
                                            <p style="font-size:17px;margin:0;padding:0;"><strong>{{$item['name']}}</strong></p>
                                        @else
                                            <p style="font-size:17px;margin:10px 0 0 0;padding:0;"><strong>{{$item['name']}}</strong></p>
                                        @endif
                                        @if(isset($item['child'])&& count($item['child']))
                                            @foreach($item['child'] as $child)
                                                <p style="font-size:17px;margin:0 0 0 30px;padding:0;">{{$child}}</p>
                                            @endforeach
                                        @endif
                                        <?php $counter++; ?>
                                    @endforeach
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:center; margin:0; padding:0 28px;">
                        <table style="color:#000; font-size:17px; line-height:1.4; width:100%; background-color:#fff; border-collapse: collapse; margin:0; padding:0;"
                               width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td style="width:40%; text-align:left; vertical-align: top; margin:0; padding:8px 20px 8px 20px;">Ответ автосервиса</td>
                                <td style="width:60%; text-align:left; vertical-align: top; margin:0; padding:8px 20px 8px 20px;">@if(!is_null($first_message))«{{$first_message->message}}, @else « @endif Ремонт - {{$request->autoservice_price}} лей, Запчасти - {{$request->autoservice_parts}} лей, Итого - {{$request->autoservice_parts +    $request->autoservice_price}} лей»</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>


</body>
</html>
