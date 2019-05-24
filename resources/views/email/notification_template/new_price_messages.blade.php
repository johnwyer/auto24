<?php
    $city = 'city_' . Lang::getLocale();
    $name = 'mark_' . Lang::getLocale();
?>

        <!DOCTYPE html>
<html lang="{{Lang::getLocale()}}"
      style="font-size: 100%; font-family: Roboto, Helvetica, sans-serif; position: relative; width: 100%; height: 100%; box-sizing: border-box;">
<head>
    <meta charset="UTF-8"/>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&subset=cyrillic'
          type='text/css' media='all'/>
    <style>
        .main-data a {
            text-decoration: underline !important;
        }

        .main-data a:hover {
            text-decoration: none !important;
        }

        .user-data a {
            font-size: 16px;
            color: #000;
            text-decoration: none;
        }

        .user-data a:hover {
            text-decoration: underline;
        }

        .btn-confirm:hover {
            background-color: #0270e6 !important;
        }
    </style>
</head>
<body style="font-size:100%; font-family:Roboto, Helvetica, sans-serif; position:relative; width:100%; height:100%; margin:0; box-sizing:border-box;">
<table style="font-size:100%; font-family:Roboto, Helvetica, sans-serif; width:100%; height:100%; background-color:#f2f2f2; color:#000000; border-collapse:collapse; margin:0; padding:0;"
       align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td align="center" style="width:100%; text-align:center; padding:10px 20px 20px 20px; vertical-align:top;">
            <table style="max-width:680px; margin:0 auto; background-color:#f2f2f2; border-collapse:collapse; padding:0;"
                   width="680" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td style="width:100%; height:107px; text-align:center; margin:0; padding:0;">
                        <a href="{{URL::route('home')}}" style="display:inline-block;" title="Auto24.md"><img
                                    src="{{asset('images/mail/logo.png')}}"
                                    style="display:block; width:200px; height:26px; padding:43px 34px 33px 34px; border:0;"
                                    width="200" height="26" border="0" alt=""/></a>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:left; background-color:#fff; padding:26px 30px 23px 30px;">
                        <table style="color:#000; font-size:16px; line-height:1.5; width:100%; background-color:#fff; border-collapse: collapse; margin:0; padding:0;"
                               width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td class="main-data">
                                    <h3 style="font-size:20px; font-weight:700; margin:0 0 4px 0; padding:0;">@lang('v.hello')</h3>

                                    <p style="margin:0; padding:0;">@lang('v.new_resp')</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @foreach($content as $key => $autoservices)
                    <?php
                    $controller = new \App\Http\Controllers\ViewController();
                    $mark = $controller->getMarkOrder($key);
                    ?>
                    <tr>
                        <td style="width:100%; text-align:left; padding:10px 0 0 0;">
                            <table style="color:#000;
                                                    font-size:16px;
                                                    line-height:1.5;
                                                    width:100%;
                                                    background-color:#fff;
                                                    border-collapse: collapse;
                                                    margin:0;
                                                    padding:0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td style="width:100%; text-align:left; background-color:#fff; padding:20px 60px 20px 60px;">
                                        <h4 style="font-size:17px; font-weight:700; color:#0363cd; margin:0; padding:0;">@lang('v.review')
                                            #{{$key}}</h4>

                                        <p style="margin:0; padding:0;">{{$mark->$name}} <span
                                                    style="color:#cccccc;">/</span> {{$mark->model}} <span
                                                    style="color:#cccccc;">/</span> {{$mark->year}} @if(!empty($mark->vin))
                                                <span style="color:#cccccc;">/</span>@endif {{$mark->vin}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%; text-align:left; background-color:#fff; padding:0 30px 5px 30px;">
                                        @foreach($autoservices as $message)

                                            <table style="color:#000;
                                                                    font-size:15px;
                                                                    line-height:1.5;
                                                                    width:100%;
                                                                    background-color:#fff;
                                                                    border:1px solid #dbeffc;
                                                                    border-radius: 5px;
                                                                    margin:0 0 20px 0;
                                                                    padding:0;" width="100%" border="0" cellpadding="0"
                                                   cellspacing="0">
                                                <tbody>
                                                <tr>
                                                    <td style="width:100%;
                                                                            text-align:left;
                                                                            background-color:#fff;
                                                                            padding:15px 30px 15px 30px;
                                                                            border-top-left-radius: 5px;
                                                                            border-top-right-radius: 5px;">

                                                        <p style="margin:0; padding:0; font-size:16px; font-weight: 500;">{{$message->autoservice_name}}</p>

                                                        <p style="margin:0; padding:0; color: #696969;"> {{$message->$city }}
                                                            , {{$message->address}}</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width:100%;
                                                                            text-align:left;
                                                                            background-color:#dbeffc;
                                                                            padding:14px 30px 17px 30px;
                                                                            border-bottom-left-radius: 5px;
                                                                            border-bottom-right-radius: 5px;">
                                                        <p style="margin:0; padding:0;">{{$message->message}}</p>
                                                        <table style="font-size:15px; line-height:1.7; width:215px; margin:14px 0 14px 0; padding:0;"
                                                               width="215" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                            <tr>
                                                                <td style="width:50%; text-align:left;">@lang('v.repair') </td>
                                                                <td style="width:50%; text-align:right;">{{$message->autoservice_price}} @lang('v.mdl')</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width:50%; text-align: left;">Запчасти</td>
                                                                <?php $parts = $message->autoservice_parts ? $message->autoservice_parts : 0;  ?>
                                                                <td style="width:50%; text-align: right;">{{$parts}} @lang('v.mdl')</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width:50%; text-align:left;">@lang('v.total') </td>
                                                                <td style="width:50%; text-align:right; font-weight:500;">{{$message->autoservice_price + $parts }} @lang('v.mdl')</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <p style="margin:0; padding:0;"><a class="btn-confirm"
                                                                                           style="display:inline-block;
                                                                                                   width:160px;
                                                                                                   border-radius:5px;
                                                                                                   text-decoration:none;
                                                                                                   font-size:14px;
                                                                                                   font-weight:700;
                                                                                                   line-height:1;
                                                                                                   padding:23px 25px;
                                                                                                   text-align:center;
                                                                                                   text-transform:uppercase;
                                                                                                   color:#fff;
                                                                                           <?php $dt = new \DateTime($message->created_at); ?>                                                       background-color:#0363cd;"
                                                                                           href="{{URL::route('dashboard')}}?orders={{$key}}"
                                                                                           title="@lang('v.repair_record_uperkase')">@lang('v.repair_record_uperkase')</a>
                                                        </p>

                                                        <p style="margin:0; padding:0; text-align:right; font-size:14px; line-height:1.1; color:#94acbb;">{{$dt->format('d.m.Y H:i')}}</p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        @endforeach
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td style="font-size:13px; line-height:1.5; color:#808080; text-align:center; padding:16px 50px 31px 50px;">
                        <p style="font-size:13px; line-height:1.5; margin:0; padding:0;">@lang('v.footer_mail_1')</p>
                        <p style="font-size:13px; line-height:1.5; margin:0; padding:0;">@lang('v.footer_mail_2')</p>
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
