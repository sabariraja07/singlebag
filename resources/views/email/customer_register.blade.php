<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title></title>
    <!--[if (mso 16)]>
    <style type="text/css">
    a {text-decoration: none;}
    </style>
    <![endif]-->
    <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]-->
    <!--[if gte mso 9]>
<xml>
    <o:OfficeDocumentSettings>
    <o:AllowPNG></o:AllowPNG>
    <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
</xml>
<![endif]-->

    <style>
        a.login-btn {
            display: inline-block;
            padding: 0.55em 1.2em;
            border: 0.1em solid #FFFFFF;
            margin: 0 0.3em 0.3em 0;
            border-radius: 0.12em;
            box-sizing: border-box;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
            font-weight: 300;
            color: #FFFFFF;
            text-align: center;
            outline: none;
            border-color: #00b289;
            background-color: #00b289;
            color: #fff;
            -webkit-transition: all .5s;
            transition: all .5s;
        }

        a.login-btn:hover {
            background: #009688 none repeat scroll 0% 0%;
            border-color: #4caf50;
            -webkit-box-shadow: 0 14px 26px -12px rgb(0 178 137 / 42%), 0 4px 23px 0px rgb(0 0 0 / 12%), 0 8px 10px -5px rgb(0 178 137 / 20%) !important;
            box-shadow: 0 14px 26px -12px rgb(0 178 137 / 42%), 0 4px 23px 0px rgb(0 0 0 / 12%), 0 8px 10px -5px rgb(0 178 137 / 20%) !important;
            opacity: .9 .5;
        }

        @media all and (max-width:30em) {
            a.login-btn {
                display: block;
                margin: 0.4em auto;
            }
        }
    </style>
</head>
@php
$shop_name = \App\Models\Shop::where('id', $data->shop_id)->first();
$image = get_shop_logo_url($data->shop_id);
@endphp

<body>
    <div class="es-wrapper-color">
        <!--[if gte mso 9]>
   <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
    <v:fill type="tile" color="#fafafa"></v:fill>
   </v:background>
  <![endif]-->
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td class="esd-email-paddings" valign="top">
                        <table class="es-header" cellspacing="0" cellpadding="0" align="center">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" esd-custom-block-id="15610" align="center">
                                        <table class="es-header-body" style="background-color: transparent;"
                                            width="600" cellspacing="0" cellpadding="0" align="center">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure" align="left">
                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="esd-container-frame" width="600"
                                                                        valign="top" align="center">
                                                                        <table width="100%" cellspacing="0"
                                                                            cellpadding="0" style="margin-top:20px;">
                                                                            <tbody>
                                                                                @if (file_exists($image))
                                                                                    <tr>
                                                                                        <td class="esd-block-image es-p20b"
                                                                                            align="center"
                                                                                            style="font-size:0;background: #f3f3f3;padding: 20px 0px;">
                                                                                            <a href="{{ env('APP_URL') }}"
                                                                                                target="_blank">
                                                                                                <img src="{{ get_shop_logo_url($data->shop_id) }}"
                                                                                                    alt
                                                                                                    style="display: block;"
                                                                                                    width="154">
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                @else
                                                                                    <td
                                                                                        style="border-bottom: 1px solid #bbb;
                                padding-bottom:10px;">
                                                                                        <h4 style="text-align: center;text-transform: uppercase;">
                                                                                            {{ $shop_name->name ?? '' }}
                                                                                        </h4>
                                                                                    </td>
                                                                                @endif
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
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-content" align="center"
                            style="margin-top: 30px;">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" align="center">
                                        <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0"
                                            cellspacing="0" width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p15t es-p20r es-p20l" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="esd-container-frame"
                                                                        align="center" valign="top">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center"
                                                                                        class="esd-block-image es-p10t es-p10b"
                                                                                        style="font-size: 0px;"><a
                                                                                            target="_blank"><img
                                                                                                src="https://tlopuf.stripocdn.email/content/guids/CABINET_f3fc38cf551f5b08f70308b6252772b8/images/96671618383886503.png"
                                                                                                alt
                                                                                                style="display: block;"
                                                                                                width="100"></a></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="center"
                                                                                        class="esd-block-text es-p15t es-p15b es-m-txt-c"
                                                                                        esd-links-underline="none">
                                                                                        <h1>Thanks for joining us!</h1>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="esd-block-text es-m-txt-l es-p30t es-p20r es-p20l"
                                                                                        align="left">
                                                                                        <h4 style="color: #333333;">
                                                                                            Hello
                                                                                            {{ $data->fullname ?? '' }},
                                                                                        </h4>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left"
                                                                                        class="esd-block-text es-p10t es-p10b">
                                                                                        <p style="font-size: 16px;">
                                                                                            Thanks for joining us! You
                                                                                            are successfully registered
                                                                                            with us. Enjoy shopping with
                                                                                            us!</p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-structure esdev-adapt-off es-p20" align="left">
                                                        <table width="560" cellpadding="0" cellspacing="0"
                                                            class="esdev-mso-table">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="esdev-mso-td" valign="top">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            class="es-left" align="left">
                                                                            <tbody>
                                                                                <tr class="es-mobile-hidden">
                                                                                    <td width="146"
                                                                                        class="esd-container-frame"
                                                                                        align="left">
                                                                                        <table cellpadding="0"
                                                                                            cellspacing="0"
                                                                                            width="100%">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td align="center"
                                                                                                        class="esd-block-spacer"
                                                                                                        height="40">
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td class="esdev-mso-td" valign="top">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            class="es-left" align="left">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td width="121"
                                                                                        class="esd-container-frame"
                                                                                        align="left">
                                                                                        <table cellpadding="0"
                                                                                            cellspacing="0"
                                                                                            width="100%"
                                                                                            bgcolor="#e8eafb"
                                                                                            style="background-color: #e8eafb; border-radius: 10px 0 0 10px; border-collapse: separate;">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td align="right"
                                                                                                        class="esd-block-text es-p10t">
                                                                                                        <p>Email:</p>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td class="esdev-mso-td" valign="top">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            class="es-left" align="left">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td width="155" align="left"
                                                                                        class="esd-container-frame">
                                                                                        <table cellpadding="0"
                                                                                            cellspacing="0"
                                                                                            width="100%"
                                                                                            bgcolor="#e8eafb"
                                                                                            style="background-color: #e8eafb; border-radius:0 10px 10px 0; border-collapse: separate;">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td align="left"
                                                                                                        class="esd-block-text es-p10t es-p10l"
                                                                                                        style="padding-right: 20px;">
                                                                                                        <p>&nbsp;<strong>{{ $data->email ?? '' }}</strong>
                                                                                                        </p>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td class="esdev-mso-td" valign="top">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            class="es-right" align="right">
                                                                            <tbody>
                                                                                <tr class="es-mobile-hidden">
                                                                                    <td width="138"
                                                                                        class="esd-container-frame"
                                                                                        align="left">
                                                                                        <table cellpadding="0"
                                                                                            cellspacing="0"
                                                                                            width="100%">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td align="center"
                                                                                                        class="esd-block-spacer"
                                                                                                        height="40">
                                                                                                    </td>
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
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-structure es-p10b es-p20r es-p20l" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="esd-container-frame"
                                                                        align="center" valign="top">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                            width="100%"
                                                                            style="border-radius: 5px; border-collapse: separate;">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center"
                                                                                        class="esd-block-button es-p10t es-p10b"
                                                                                        style="padding-top: 30px;">
                                                                                        <span class="es-button-border"
                                                                                            style="border-radius: 6px;">
                                                                                            <a href="{{ $data->shop->domain->full_domain }}"
                                                                                                class="es-button login-btn"
                                                                                                target="_blank">SHOP
                                                                                                NOW</a>
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left"
                                                                                        class="esd-block-text es-p20t es-p10b">
                                                                                        <p style="line-height: 150%;">
                                                                                            Got a question? Email us
                                                                                            at&nbsp;<a target="_blank"
                                                                                                href="mailto:{{ $data->shop->email ?? '' }}">{{ $data->shop->email ?? '' }}</a>.
                                                                                        </p>
                                                                                        <p><br>Thanks,</p>
                                                                                        <p><strong>{{ ucfirst($data->shop->name) }}
                                                                                            </strong>Team!</p>
                                                                                    </td>
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
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
