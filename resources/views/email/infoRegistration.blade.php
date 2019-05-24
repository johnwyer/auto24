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
            background-color: #ff0818 !important;
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
                            <a style="display: inline-block;" href="{{URL::route('home')}}" title="Auto24.md"><img
                                        src="https://auto24.md/images/pdf/logo.png"
                                        style="display:block; width:200px; height:26px; padding:43px 34px 33px 34px; border:0;"
                                        width="200" height="26" border="0" alt=""/></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:100%; text-align:left; background-color:#fff; padding:26px 30px 57px 30px;">
                            <table style="color:#000; font-size:16px; line-height:1.5; width:100%; background-color:#fff; border-collapse:collapse; margin:0; padding:0;"
                                   width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tbody>
                                <tr>
                                    <td class="main-data">
                                        <h3 style="font-size:20px; font-weight:700; margin:0 0 4px 0; padding:0;">@lang('v.hello')</h3>

                                        <p style="font-size:16px; line-height:1.5; margin:0; padding:0;">@lang('v.to_finish_register')
                                            <a style="color:#0363cd; text-decoration:underline;"
                                               href="{{URL::route('user_confirm_token',$token)}}"
                                               target="_blank">@lang('v.go_to_link')</a> @lang('v.click_btn_down')</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center; padding:23px 0 35px 0;">
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
                                           href="{{URL::route('user_confirm_token',$token)}}"
                                           target="_blank">@lang('v.confirm')</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="user-data">
                                        <p style="color:#000; font-size:16px; line-height:1.5; margin:0; padding:0;">@lang('v.date_to_login')</p>

                                        <p style="color:#000; font-size:16px; line-height:1.5; margin:0; padding:0;">@lang('v.login')
                                            : <strong>{{$email}}</strong></p>

                                        <p style="color:#000; font-size:16px; line-height:1.5; margin:0; padding:0;">@lang('v.password')
                                            : <strong>{{$pass}}</strong></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:13px; line-height:1.5; color:#808080;text-align:center; padding:16px 50px;">
                            <p style="font-size:13px; line-height:1.5; margin:0; padding:0;">@lang('v.footer_mail_1')</p>
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
