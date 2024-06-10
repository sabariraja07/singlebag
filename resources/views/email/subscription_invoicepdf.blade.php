<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            text-align: left;
        }

        .invoice-box table.item {
            text-align: center;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            font-weight: bold;
            padding: 10px 0;
        }



        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /* RTL */
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img width="200" src="{{ admin_logo_url() }}">
                            </td>
                            <td>
                                <strong>Invoice No: </strong>{{ $info->order_no }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table style="margin-bottom: 60px;">
                        <tr>
                            <td style="width: 50%;">
                                <strong>To</strong> <br>
                                {{ $shop->user->fullname ?? '' }}<br>
                                {{ $shop->user->email ?? '' }}<br>
                                {{ $shop->domain->domain ?? '' }}<br>

                            </td>
                            <td style="width: 50%;text-align:right">
                               <div>
                                    @if(!empty($company_info))
                                    <strong>{{ $company_info->name }}</strong><br>
                                    {{ $company_info->address }}, {{ $company_info->city }},
                                    {{ $company_info->state }}, {{ $company_info->country }},
                                    {{ $company_info->zip_code }}<br>
                                    <strong>Email:</strong> {{ $company_info->email1 }}<br>
                                    <strong>Phone:</strong> {{  $company_info->phone1 }}<br>
                                    <strong>GST:</strong> {{  $company_info->gst ?? '' }}<br>
                                    
                                    @endif
                               </div>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>

                            <td><strong>Purchased At</strong> <br>{{ $info->created_at->format('Y-m-d') }}<br></td>

                            <td><strong>Expiry Date</strong> <br>{{ $shop->will_expire }}<br></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Payment Status</strong> <br>


                                @if($info->payment_status==2)
                                <div class="badge">Pending</div>
                                @elseif($info->payment_status==1)
                                <div class="badge">Paid</div>
                                @elseif($info->payment_status==0)
                                <div class="badge">Cancel</div>
                                @elseif($info->payment_status==3)
                                <div class="badge">Incomplete</div>
                                @endif
                            </td>
                            <td>
                                <strong>Payment Mode</strong> <br>
                                {{ $info->PaymentMethod->name ?? "" }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <table class="item">
                <tbody>
                    <tr class="heading">
                        <td class="text-left">Name</td>
                        <td class="text-right">Description</td>
                    </tr>
                    @if(!empty($info->plan_info))
                    <tr>
                        <td class="text-left">Plan Name</td>
                        <td class="text-right">{{ $info->plan_info->name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-left">Amount</td>
                        <td class="text-right">{!! admin_amount_format_pdf(($info->amount ?? 0 )+ ($info->discount ?? 0) - ($info->tax ?? 0)) !!}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Tax</td>
                        <td class="text-right">{!! admin_amount_format_pdf($info->tax ?? 0) !!}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Discount</td>
                        <td class="text-right">-{!! admin_amount_format_pdf($info->discount ?? 0) !!}</td>
                    </tr>
                    <tr class="subtotal">
                        <td></td>
                        <td>
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"></td>
                        <td class="text-right"><strong> Total: </strong>
                            {!! admin_amount_format_pdf($info->amount ?? 0) !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </table>
    </div>
</body>

</html>
