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
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 25px;
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
@php
    $shop_name = \App\Models\Shop::where('id', $order->shop_id)->first();
    $image = get_shop_logo_url($order->shop_id);
@endphp

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            @if (file_exists($image))
                                <td class="title">
                                    <img height="80" width="160"
                                        src="{{ get_shop_logo_url($order->shop_id) }}">
                                </td>
                            @else
                                <td>
                                    <h4 style="text-transform: uppercase;">{{ $shop_name->name ?? '' }}</h4>
                                </td>
                            @endif

                            <td>
                                <strong>Invoice No: </strong>{{ $order->order_no }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>

                            <td>
                                @if ($order->order_type == 1)
                                    <strong>Shipped To</strong><br>
                                    {{ $order_content->name ?? '' }}<br>
                                    {{ $order_content->email ?? '' }}<br>
                                    {{ $order_content->phone ?? '' }}<br>
                                    <br>
                                    @if (isset($order_content->address))
                                        <strong>Address:</strong> <br />
                                        {{ $order_content->address ?? '' }}<br>
                                        {{ $order->shipping_info->city->name ?? '' }}<br>
                                        {{ $order_content->zip_code ?? '' }}
                                        <br />
                                    @endif
                                    <br>
                                @endif
                                @if ($order->order_type == 0)
                                    <b>Name:</b> {{ $order_content->name ?? '' }}<br>
                                    <b>Email:</b> {{ $order_content->email ?? '' }}<br>
                                @endif
                            </td>




                            <td>
                                @if (!empty($location))
                                    <strong>{{ $location->company_name }}</strong><br>
                                    {{ $location->address }}, {{ $location->city }}, {{ $location->state }},
                                    {{ $location->zip_code }} <br>

                                    <b>Email: </b>{{ $location->email }}<br>
                                    <b>Phone:</b> {{ $location->phone }}<br>
                                    @if (isset($gst))
                                        <b>GST: </b>{{ $gst }}<br>
                                    @endif
                                @endif
                                <br><br>
                                @php
                                    $shipping_method = $order->shipping_info ? $order->shipping_info->method->title : '';
                                    $estimate_date = $order->shipping_info ? $order->shipping_info->method->estimated_delivery : '';
                                @endphp
                                @if (isset($order->shipping_info))
                                    <strong>Shipping Method:</strong><br>
                                    {{ $shipping_method . '(' . $estimate_date . ')' ?? '' }}<br>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Payment Status:</strong> <br>


                                @if ($order->payment_status == 2)
                                    <div class="badge">Pending</div>
                                @elseif($order->payment_status == 1)
                                    <div class="badge">Paid</div>
                                @elseif($order->payment_status == 0)
                                    <div class="badge">Cancel</div>
                                @elseif($order->payment_status == 3)
                                    <div class="badge">Incomplete</div>
                                @endif

                            </td>

                            <td>
                                <strong>Order Date:</strong> <br>
                                {{ $order->created_at->format('d-F-Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <table class="item">
                <tbody>
                    <tr class="heading">
                        <td class="text-left">Product</td>

                        <td class="text-center">Product Price</td>
                        <td class="text-right">Totals</td>
                    </tr>
                    @foreach ($order->order_item as $key => $orderitem)
                        <tr>
                            <td class="text-left">{{ $orderitem->term->title }}
                                @php
                                    $attributes = $orderitem->info;
                                @endphp
                                @foreach ($attributes->options ?? [] as $option)
                                    <span>{{ __('Options') }} :</span> <small>{{ $option->name ?? '' }}</small>
                                @endforeach
                            </td>
                            <td class="text-center">{!! amount_format_pdf($orderitem->amount) !!} x {{ $orderitem->qty }}</td>
                            <td class="text-right">{!! amount_format_pdf($orderitem->amount * $orderitem->qty) !!}</td>
                        </tr>
                    @endforeach
                    <tr class="subtotal">

                        <td></td>
                        <td></td>
                        <td>
                            <hr>
                        </td>
                    </tr>


                    <tr class="subtotal">

                        <td></td>
                        <td class="text-right"><strong>Discount:</strong></td>
                        <td class="text-right">- {!! amount_format_pdf($order_content->coupon_discount ?? 0) !!}</td>
                    </tr>

                    <tr>

                        <td></td>
                        <td class="text-right"><strong>Tax:</strong></td>
                        <td class="text-right">{{ $order->tax }}</td>
                    </tr>
                    @if ($order->order_type == 1)
                        <tr>

                            <td></td>
                            <td class="text-right"><strong>Shippping:</strong></td>
                            <td class="text-right">{!! amount_format_pdf($order->shipping) !!}</td>
                        </tr>
                    @endif
                    <tr class="subtotal">

                        <td></td>
                        <td class="text-right"><strong>Subtotal:</strong></td>
                        <td class="text-right">{!! amount_format_pdf($order_content->sub_total ?? 0) !!}</td>
                    </tr>
                    <tr>

                        <td></td>

                        <td class="text-right"><strong>Total:</strong></td>
                        <td class="text-right">{!! amount_format_pdf($order->total) !!}</td>
                    </tr>
                </tbody>
            </table>
        </table>
    </div>
</body>

</html>
