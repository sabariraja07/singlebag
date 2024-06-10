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

        /* .content-cell {
            padding: 35px;
        } */

        /*Media Queries ------------------------------ */

        @media only screen and (max-width: 600px) {

            .img-source {
                height: 108px !important;
                width: 172px !important;
                padding-left: 69px !important;
                margin-top: 32px !important;
            }

            .img-source1 {
                height: 99px !important;
                width: 92px !important;
                float: left !important;
                margin-bottom: 70px !important;
                margin-top: 49px !important;
            }

            .email-body_inner,
            .email-footer {
                width: 100% !important;
            }

            .img_logo {
                min-height: 55px;
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

        .login_button {
            background-color: #212060;
            border-color: #212060;
            color: #fff !important;
            padding: 5px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border: 2px solid !important;
            border-radius: 20px !important;
            margin-top: 10px;
            float: right;
        }

        .img-source {
            height: 261px;
            width: 383px;
            padding-left: 175px;
            margin-top: 38px;
        }

        .img-source1 {
            height: 216px;
            width: 248px;
            float: left;
            margin-bottom: 47px;
            margin-top: 39px;
            

        }
    </style>
</head>
@php
$image = admin_logo_url();
@endphp

<body>

    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        @if (file_exists($image))
                            <td class="email-masthead">
                                <a href="{{ config('app.url') }}" class="f-fallback email-masthead_name">
                                    <img src="{{ admin_logo_url() }}" class="img_logo" height="50"
                                        width="200" style="float: left;">
                                </a>
                                <a href="{{ $shop->domain->full_domain }}/login" class="login_button">Login</a>
                            </td>
                        @else
                            <td>
                                <h4
                                    style="text-transform: uppercase;float: left;
                                margin-left: 10px;">
                                    {{ config('app.name') }}</h4>
                                <a href="{{ $shop->domain->full_domain }}/login" class="login_button">Login</a>
                            </td>
                        @endif

                    </tr>
                    <tr>
                        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="email-body_inner" align="center" width="570" cellpadding="0"
                                cellspacing="0" role="presentation">
                                <a href="{{ config('app.url') }}" class="f-fallback email-masthead_name">
                                    <img src="{{ asset('/frontend/singlebag/imgs/shop/store-1.png') }}"
                                        class="img-source">

                                </a>
                            </table>

                        </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="email-body_inner" align="center" width="570" cellpadding="0"
                                cellspacing="0" role="presentation">
                                <!-- Body content -->

                                <td class="content-cell">
                                    <div class="f-fallback">
                                        <h1 style="color: #333333;text-align: center;margin-top: 12px;">Hi
                                            {{ $shop->user->full_name }}!
                                        </h1>
                                        <h4 style="color: #333333;" align="center">Welcome to Singlebag</h4>

                                        <p style="text-align: center;">Congratulations! Your Singlebag store
                                            registration is confirmed.</p>
                                        <p style="text-align: center;">We’re excited you’re here and can’t wait to help
                                            you get started.</p>

                                    </div>
                                </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                <table class="email-body_inner" width="570" cellpadding="0" cellspacing="0" role="presentation">
                    <!-- Body content -->
                    <td>
                        <a href="{{ config('app.url') }}" class="f-fallback email-masthead_name">
                            <img src="{{ asset('/frontend/singlebag/imgs/shop/store-2.png') }}" class="img-source1">

                        </a>
                    </td>

                    <td>
                        <div style="padding-left: 10px;">
                            <p>
                                You've successfully completed registration for <span
                                    style="font-weight: bold">{{ $shop->name }}</span>. You can customize your store
                                and start selling.
                            </p>
                            <h4 style="color: #333333;margin-top: -9px;text-align: center;">Your free trial starts
                                today.</h4>
                            <h3 style="margin-bottom: 13px;text-align: center;">Your Store Details:</h3>
                            <p style="color: #64434a;">Email: <span>
                                    <a href="mailto: {{ $shop->email }} ">
                                        {{ $shop->email }} </a>
                                </span></p>
                            <p style="color: #64434a;">Store Url: <span>
                                    <a href="{{ $shop->domain->full_domain }}">
                                        {{ $shop->domain->domain }} </a>
                                </span></p>

                        </div>
                    </td>
        </tr>
    </table>
    </td>
    </tr>
    <tr>
        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
                role="presentation">
                <!-- Body content -->

                <td class="content-cell">
                    <div class="f-fallback">
                        <h1 style="color: #333333;text-align: center;">Get Started</h1>
                        <div>
                            <p>
                                Singlebag makes it easier to set up online store. Simply choose a theme, to best
                                represent your brand. Then add your products, manage inventory, track sales, get 24/7
                                support. Everything you need is to start your business today!
                            </p>
                        </div>

                    </div>
                </td>

            </table>
        </td>
    </tr>
    @include('email.email_footer')
    </table>
    </td>
    </tr>
    </table>
</body>

</html>
