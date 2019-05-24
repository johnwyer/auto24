<!DOCTYPE html>
<html lang="en"
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
            background-color: #ff0818 !important;
        }
    </style>
</head>
<body style="font-size:100%; font-family:Roboto, Helvetica, sans-serif; position:relative; width:100%; height:100%; margin:0; box-sizing:border-box; transition: all 0.25s ease-in;">

<table style="font-size:100%; font-family:Roboto, Helvetica, sans-serif; width:100%; height:100%; background-color:#f2f2f2; color:#000000; border-collapse:collapse; margin:0; padding:0; transition: all 0.25s ease-in;"
       align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td align="center" style="width:100%; text-align:center; padding:10px 20px 20px 20px; vertical-align:top;">
            <table style="max-width:680px; margin:0 auto; background-color:#f2f2f2; border-collapse:collapse; padding:0;"
                   width="680" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td style="width:100%; height:107px; text-align:center; margin:0; padding:0;">
                        <a href="{{URL::route('home')}}" style="display: inline-block;" title="Auto24.md"><img
                                    src="{{asset('images/mail/logo.png')}}"
                                    style="display:block; width:200px; height:26px; padding:43px 34px 33px 34px; border:0;"
                                    width="200" height="26" border="0" alt=""/></a>
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:left; background-color:#fff; padding:26px 30px 33px 30px;">
                        <table style="color:#000; font-size:16px; line-height:1.5; width:100%; background-color:#fff; border-collapse: collapse; margin:0; padding:0;"
                               width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td class="main-data">
                                    <h3 style="font-size:20px; font-weight:700; margin:0 0 4px 0; padding:0; line-height:1.5;">@lang('v.hello')</h3>
                                    <p style="margin:0; padding:0; line-height:1.5;">@lang('v.new_order_to_repair') <strong style="font-weight:500;">#{{$detail['order']->number}}.</strong></p>
                                    <p style="margin:0; padding:0; line-height:1.5;">@lang('v.from_view') <a style="color:#0363cd;"
                                                                                            href="{{URL::route('autoservice')}}?orders={{$detail['order']->number}}">@lang('v.go_to_link')</a> @lang('v.page_down')
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center; padding:4px 0 43px 0;">
                                    <a class="btn-confirm" style="display:inline-block;
                                                            width:200px;
                                                            border-radius:5px;
                                                            text-decoration:none;
                                                            font-size:14px;
                                                            font-weight:700;
                                                            line-height:1;
                                                            padding:23px 50px;
                                                            text-align:center;
                                                            text-transform:uppercase;
                                                            color:#fff;
                                                            background-color:#e30613;"
                                       href="{{URL::route('autoservice')}}?orders={{$detail['order']->number}}" title="@lang('v.go_to_order')">@lang('v.go_to_order')</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="line-height:2.2;">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="width:35%;">
                                                <p style="margin:0; padding:0; line-height:2.2;">@lang('v.auto')</p>
                                            </td>
                                            <td style="width: 65%">
                                                <p style="margin:0; padding:0; line-height:2.2;">{{$detail['order']->marka}}
                                                    <span style="color:#cccccc;">/</span> {{$detail['order']->model}}
                                                    <span style="color:#cccccc;">/</span> {{$detail['order']->year}}
                                                    @if(!is_null($detail['order']->vin) && !empty($detail['order']->vin)) <span style="color:#cccccc;">/</span> {{$detail['order']->vin}}</p> @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%;">
                                                <p style="margin:0; padding:0; line-height:2.2;">@lang('v.date_command')</p>
                                            </td>
                                            <td style="width: 65%;">
                                                <?php $dt = new \DateTime($detail['order']->date)?>
                                                <p style="margin:0; padding:0; line-height:2.2;">{{$dt->format('d.m.Y H:i')}}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%;">
                                                <p style="margin:0; padding:0; line-height:2.2;">@lang('v.city')</p>
                                            </td>
                                            <td style="width: 65%;">
                                                <p style="margin:0; padding:0; line-height:2.2;">{{$detail['order']->city}}, {{$detail['order']->district}}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 29px 0 0 0;">
                                    <h4 style="font-size: 18px; font-weight:700; margin: 0 0 4px 0; padding:0; line-height: 1.5;">@lang('v.list_required_work')</h4>
                                    <?php $counter = 0; $padding = '0'; ?>
                                    @foreach($detail['service'] as $service)
                                        <?php
                                        if($counter > 0) {
                                            $padding = '14px 0 0 0';
                                        }
                                        ?>
                                        <p style="margin: 0; padding: <?php $padding; ?>; font-weight:700; line-height: 1.5;">{{$service['name']}}</p>

                                        @if(isset($service['child']) &&  count($service['child']))
                                            @foreach($service['child'] as $child)
                                                <p style="margin: 0; padding: 0 0 0 30px; line-height: 1.9;">— {{$child}}</p>
                                            @endforeach
                                        @endif
                                        <?php $counter++ ?>
                                    @endforeach
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
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
