<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />
    <title></title>
    <style type="text/css" rel="stylesheet" media="all">
        /* Base ------------------------------ */

        @import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");

        body {
            width: 100% !important;
            height: 100%;
            margin: 0;
            -webkit-text-size-adjust: none;
        }

        a {
            color: #3869D4;
        }

        a img {
            border: none;
        }

        td {
            word-break: break-word;
        }

        .preheader {
            display: none !important;
            visibility: hidden;
            mso-hide: all;
            font-size: 1px;
            line-height: 1px;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
        }

        /* Type ------------------------------ */

        body,
        td,
        th {
            font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
        }

        h1 {
            margin-top: 0;
            color: #333333;
            font-size: 22px;
            font-weight: bold;
            text-align: left;
        }

        h2 {
            margin-top: 0;
            color: #333333;
            font-size: 16px;
            font-weight: bold;
            text-align: left;
        }

        h3 {
            margin-top: 0;
            color: #333333;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
        }

        td,
        th {
            font-size: 16px;
        }

        p,
        ul,
        ol,
        blockquote {
            margin: .4em 0 1.1875em;
            font-size: 16px;
            line-height: 1.625;
        }

        p.sub {
            font-size: 13px;
        }

        /* Utilities ------------------------------ */

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .align-center {
            text-align: center;
        }

        /* Buttons ------------------------------ */

        .button {
            background-color: #3869D4;
            border-top: 10px solid #3869D4;
            border-right: 18px solid #3869D4;
            border-bottom: 10px solid #3869D4;
            border-left: 18px solid #3869D4;
            display: inline-block;
            color: #FFF;
            text-decoration: none;
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            -webkit-text-size-adjust: none;
            box-sizing: border-box;
        }

        .button--green {
            background-color: #22BC66;
            border-top: 10px solid #22BC66;
            border-right: 18px solid #22BC66;
            border-bottom: 10px solid #22BC66;
            border-left: 18px solid #22BC66;
        }

        .button--red {
            background-color: #FF6136;
            border-top: 10px solid #FF6136;
            border-right: 18px solid #FF6136;
            border-bottom: 10px solid #FF6136;
            border-left: 18px solid #FF6136;
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
                text-align: center !important;
            }
        }

        /* Attribute list ------------------------------ */

        .attributes {
            margin: 0 0 21px;
        }

        .attributes_content {
            background-color: #F4F4F7;
            padding: 16px;
        }

        .attributes_item {
            padding: 0;
        }

        /* Related Items ------------------------------ */

        .related {
            width: 100%;
            margin: 0;
            padding: 25px 0 0 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        .related_item {
            padding: 10px 0;
            color: #CBCCCF;
            font-size: 15px;
            line-height: 18px;
        }

        .related_item-title {
            display: block;
            margin: .5em 0 0;
        }

        .related_item-thumb {
            display: block;
            padding-bottom: 10px;
        }

        .related_heading {
            border-top: 1px solid #CBCCCF;
            text-align: center;
            padding: 25px 0 10px;
        }

        /* Discount Code ------------------------------ */

        .discount {
            width: 100%;
            margin: 0;
            padding: 24px;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #F4F4F7;
            border: 2px dashed #CBCCCF;
        }

        .discount_heading {
            text-align: center;
        }

        .discount_body {
            text-align: center;
            font-size: 15px;
        }

        /* Social Icons ------------------------------ */

        .social {
            width: auto;
        }

        .social td {
            padding: 0;
            width: auto;
        }

        .social_icon {
            height: 20px;
            margin: 0 8px 10px 8px;
            padding: 0;
        }

        /* Data table ------------------------------ */

        .purchase {
            width: 100%;
            margin: 0;
            padding: 35px 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        .purchase_content {
            width: 100%;
            margin: 0;
            padding: 25px 0 0 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        .purchase_item {
            padding: 10px 0;
            color: #51545E;
            font-size: 15px;
            line-height: 18px;
        }

        .purchase_heading {
            padding-bottom: 8px;
            border-bottom: 1px solid #EAEAEC;
        }

        .purchase_heading p {
            margin: 0;
            color: #85878E;
            font-size: 12px;
        }

        .purchase_footer {
            padding-top: 15px;
            border-top: 1px solid #EAEAEC;
        }

        .purchase_total {
            margin: 0;
            text-align: right;
            font-weight: bold;
            color: #333333;
        }

        .purchase_total--label {
            padding: 0 15px 0 0;
        }

        body {
            background-color: #F4F4F7;
            color: #51545E;
        }

        p {
            color: #51545E;
        }

        p.sub {
            color: #6B6E76;
        }

        .email-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #F4F4F7;
        }

        .email-content {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
        }

        /* Masthead ----------------------- */

        .email-masthead {
            padding: 25px 0;
            text-align: center;
        }



        .email-masthead_name {
            font-size: 16px;
            font-weight: bold;
            color: #A8AAAF;
            text-decoration: none;
            text-shadow: 0 1px 0 white;
        }

        /* Body ------------------------------ */

        .email-body {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #FFFFFF;
        }

        .email-body_inner {
            width: 570px;
            margin: 0 auto;
            padding: 0;
            -premailer-width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #FFFFFF;
        }

        .email-footer {
            width: 570px;
            margin: 0 auto;
            padding: 0;
            -premailer-width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            text-align: center;
        }

        .email-footer p {
            color: #6B6E76;
        }

        .body-action {
            width: 100%;
            margin: 30px auto;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            text-align: center;
        }

        .body-sub {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #EAEAEC;
        }

        .content-cell {
            padding: 35px;
        }

        /*Media Queries ------------------------------ */

        @media only screen and (max-width: 600px) {

            .email-body_inner,
            .email-footer {
                width: 100% !important;
            }

            .img_logo {
                min-height: 100px;
            }

            .purchase {
                padding-top: 0px;
            }

            h3 {
                margin-top: 0;
                color: #333333;
                font-size: 8px;
                font-weight: bold;
                text-align: left;
            }

            .purchase_total--label {
                padding: 0 76px 0 0;
            }

            .purchase_total {
                margin: 0;
                font-weight: bold;
                color: #333333;
                font-size: 14px;
            }
        }

        @media (prefers-color-scheme: dark) {

            body,
            .email-body,
            .email-body_inner,
            .email-content,
            .email-wrapper,
            .email-masthead,
            .email-footer {
                background-color: #333333 !important;
                color: #FFF !important;
            }

            p,
            ul,
            ol,
            blockquote,
            h1,
            h2,
            h3 {
                color: #FFF !important;
            }

            .attributes_content,
            .discount {
                background-color: #222 !important;
            }

            .email-masthead_name {
                text-shadow: none !important;
            }
        }

        :root {
            color-scheme: light dark;
            supported-color-schemes: light dark;
        }
    </style>
    <!--[if mso]>
    <style type="text/css">
      .f-fallback  {
        font-family: Arial, sans-serif;
      }
    </style>
  <![endif]-->
</head>
@php
$shop_name = \App\Models\Shop::where('id', $shop_id)->first();
$image = get_shop_logo_url($shop_id);
// $youtube = public_path() . '/frontend/singlebag/imgs/theme/icons/icon-youtube-white.svg';
@endphp

<body>

    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        @if (file_exists($image))
                            <td class="email-masthead">
                                <a href="{{ url($base_url) }}" class="f-fallback email-masthead_name">
                                    <img src="{{ get_shop_logo_url($shop_id) }}" class="img_logo"
                                        height="50" width="200">
                                </a>
                            </td>
                        @else
                            <td
                                style="border-bottom: 1px solid #bbb;
                                padding-bottom:10px;">
                                <h4 style="text-align: center;text-transform: uppercase;">{{ $shop->name ?? '' }}</h4>
                            </td>
                        @endif
                    </tr>
                    <!-- Email Body -->
                    <tr>
                        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="email-body_inner" align="center" width="570" cellpadding="0"
                                cellspacing="0" role="presentation">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        <div class="f-fallback">
                                            <h1 style="text-align: center">ORDER CONFIRMATION</h1>
                                            <h1 style="font-size: 18px;">Hi {{ $order_content->name ?? '' }},</h1>
                                            <p>Thank you for your order!</p>
                                            <p>We have received your order and will contact you as soon as your package
                                                is shipped. You can find your purchase information below.</p>
                                            <!-- Action -->
                                            <table class="body-action" align="center" width="100%" cellpadding="0"
                                                cellspacing="0" role="presentation">
                                                <tr>
                                                    <td align="center">
                                                        <!-- Border based button
           https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->
                                                        <table width="100%" border="0" cellspacing="0"
                                                            cellpadding="0" role="presentation">

                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table class="purchase" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <h3>Order no : {{ $order->order_no }}</h3>
                                                    </td>
                                                    <td>
                                                        <h3 class="align-right">Order placed at :
                                                            {{ $order->created_at->format('d-F-Y') }}</h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if ($order->category_id != null)
                                                            <h3>Payment Method : {{ $order->gateway->name ?? '' }}
                                                            </h3>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $shipping_method = $order->shipping_info ? $order->shipping_info->method->title : '';
                                                    $estimate_date = $order->shipping_info ? $order->shipping_info->method->estimated_delivery : '';
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <h3 style="font-size: 13px;">Shipping Method
                                                            :{{ $shipping_method . '(' . $estimate_date . ')'  }}
                                                        </h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table class="purchase_content" width="100%" cellpadding="0"
                                                            cellspacing="0">
                                                            <tr>
                                                                <th class="purchase_heading" align="left">
                                                                    <p class="f-fallback">Description</p>
                                                                </th>
                                                                <th class="purchase_heading" align="right">
                                                                    <p class="f-fallback">Amount</p>
                                                                </th>
                                                            </tr>
                                                            @foreach ($order->order_item_with_file as $key => $orderitem)
                                                                <tr>
                                                                    <td width="60%" class="purchase_footer"
                                                                        valign="middle">{{ $orderitem->term->title }}
                                                                        x {{ $orderitem->qty }} <br>
                                                                        @php
                                                                            $attributes = $orderitem->info;
                                                                        @endphp
                                                                        @foreach ($attributes->options ?? [] as $option)
                                                                            <span>{{ __('Options') }} :</span>
                                                                            <small>{{ $option->name ?? '' }}</small>
                                                                        @endforeach
                                                                        @if ($order->status == 'completed' && $order->payment_status == 1)
                                                                            <br>
                                                                            @foreach ($orderitem->file ?? [] as $file)
                                                                                <a href="{{ asset($file->url) }}"
                                                                                    target="_blank">Download</a>
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td width="40%"
                                                                        class="align-right purchase_footer">
                                                                        {{ number_format($orderitem->amount * $orderitem->qty, 2) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            @if ($order_content->coupon_discount > 0)
                                                                <tr>
                                                                    <td width="60%" class="purchase_footer"
                                                                        valign="middle">
                                                                        <p
                                                                            class="f-fallback purchase_total purchase_total--label">
                                                                            Discount</p>
                                                                    </td>
                                                                    <td width="40%" class="purchase_footer"
                                                                        valign="middle">
                                                                        <p class="f-fallback purchase_total">
                                                                            {{ number_format($order_content->coupon_discount, 2) }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <td width="60%" class="purchase_footer"
                                                                    valign="middle">
                                                                    <p
                                                                        class="f-fallback purchase_total purchase_total--label">
                                                                        Tax</p>
                                                                </td>
                                                                <td width="40%" class="purchase_footer"
                                                                    valign="middle">
                                                                    <p class="f-fallback purchase_total">
                                                                        {{ number_format($order->tax, 2) }}</p>
                                                                </td>
                                                            </tr>
                                                            @if ($order->order_type == 1)
                                                                <tr>
                                                                    <td width="70%" class="purchase_footer"
                                                                        valign="middle">
                                                                        <p
                                                                            class="f-fallback purchase_total purchase_total--label">
                                                                            Shippping</p>
                                                                    </td>
                                                                    <td width="40%" class="purchase_footer"
                                                                        valign="middle">
                                                                        <p class="f-fallback purchase_total">
                                                                            {{ number_format($order->shipping, 2) }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            <tr>
                                                                <td width="60%" class="purchase_footer"
                                                                    valign="middle">
                                                                    <p
                                                                        class="f-fallback purchase_total purchase_total--label">
                                                                        Subtotal</p>
                                                                </td>
                                                                <td width="40%" class="purchase_footer"
                                                                    valign="middle">
                                                                    <p class="f-fallback purchase_total">
                                                                        {{ number_format($order_content->sub_total ?? 0) }}
                                                                    </p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td width="60%" class="purchase_footer"
                                                                    valign="middle">
                                                                    <p
                                                                        class="f-fallback purchase_total purchase_total--label">
                                                                        Total</p>
                                                                </td>
                                                                <td width="40%" class="purchase_footer"
                                                                    valign="middle">
                                                                    <p class="f-fallback purchase_total">
                                                                        {{ number_format($order->total, 2) }}</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <hr>
                                            <table class="purchase" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <h2>Billing</h3>
                                                            @if (!empty($location))
                                                                <p style="max-width: 50% !important;">
                                                                    {{ $shop->name }},<br>
                                                                    {{ $location->address }},
                                                                    {{ $location->city }}-{{ $location->zip_code }}.
                                                                </p>
                                                            @endif
                                                    </td>
                                                    <td>
                                                        <h2 class="align-right">Shipping</h2>
                                                        @if (!empty($location))
                                                            <p class="align-right">
                                                                {{ $shop->name }}<br>
                                                                {{ $order_content->address }},
                                                                {{ $order_content->zip_code }}.
                                                            </p>
                                                        @endif
                                                    </td>
                                                </tr>

                                            </table>
                                            <table
                                                style="width: 100%;
                                            margin: 0;
                                            padding: 13px 0;"
                                                width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td align="center">
                                                        <p class="f-fallback sub align-center"
                                                            style="font-size: 26px;color: black;">Thank you for
                                                            ordering!</p>
                                                        <p class="f-fallback sub align-center"
                                                            style="font-size: 18px;color: black;">Keep Shopping</p>

                                                    </td>
                                                </tr>
                                            </table>
                                            @php
                                                $social_icon = \App\Models\ShopOption::where('key', 'socials')
                                                    ->where('shop_id', $order->shop_id)
                                                    ->first();
                                                $socials = $social_icon ? json_decode($social_icon->value) : [];
                                            @endphp
                                            @if(!empty($social_icon))
                                            @if (count($socials) > 0)
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="center">
                                                            @foreach ($socials as $key => $value)
                                                                @if ($value->icon == 'fa fa-instagram')
                                                                    <a href="{{ $value->url }}"><img
                                                                            src="{{ asset('/frontend/singlebag/imgs/theme/icons/' . $value->icon . '.svg') }}" /></a>
                                                                @endif
                                                                @if ($value->icon == 'fa fa-facebook')
                                                                    <a href="{{ $value->url }}"><img
                                                                            src="{{ asset('/frontend/singlebag/imgs/theme/icons/' . $value->icon . '.svg') }}" /></a>
                                                                @endif
                                                                @if ($value->icon == 'fa fa-twitter')
                                                                    <a href="{{ $value->url }}"><img
                                                                            src="{{ asset('/frontend/singlebag/imgs/theme/icons/' . $value->icon . '.svg') }}" /></a>
                                                                @endif
                                                                @if ($value->icon == 'fa fa-youtube')
                                                                    <a href="{{ $value->url }}"><img
                                                                            src="{{ asset('/frontend/singlebag/imgs/theme/icons/' . $value->icon . '.svg') }}" /></a>
                                                                @endif
                                                            @endforeach

                                                        </td>
                                                    </tr>

                                                </table>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="email-footer" align="center" width="570" cellpadding="0"
                                cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-cell" align="center">
                                        @if (!empty($location))
                                            <p class="f-fallback sub align-center">Our mailing address is: <span>
                                                    <a href="mailto: {{ $shop->email }} "> {{ $shop->email }}</a>
                                                </span>
                                            </p>

                                            <p class="f-fallback sub align-center">
                                                {{ $shop->name }},
                                                {{ $location->address }},
                                                {{ $location->city }}-{{ $location->zip_code }}.
                                            </p>
                                            <p class="f-fallback sub align-center" style="color: black;">&copy;
                                                {{ date('Y') }}
                                                {{ $shop->name }}. All rights reserved.</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
