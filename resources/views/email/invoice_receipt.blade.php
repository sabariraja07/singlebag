<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
      @media  print {
        .noPrint{
          display:none;
        }
      }
  </style>
</head>
@php
$shop_name = \App\Models\Shop::where('id', $order->shop_id)->first();
$image = get_shop_logo_url($order->shop_id);
@endphp
<body>
<button onclick="window.print();" class="btn btn-primary noPrint" style="float: right;font-size: 17px;margin: 10px;">
    Print Receipt 
</button>
<div class="col-md-12">
  <div class="text-center">
    <p>
      <span style="font-size: 25px;">
        <b>
           <span style="text-transform:capitalize;">{{ $shop_name->name ?? ''}}</span>
        </b>
      </span>
      <br>
    </p>
    <p>
      <span style="font-size: 12px;">                            
      @if(!empty($location))
        <strong>{{ $location->company_name }}</strong><br>
        {{ $location->address }}, {{ $location->city }}, {{ $location->state }}, {{ $location->zip_code }} <br>        
            Email: {{ $location->email }}<br>
            Phone: {{ $location->phone }}<br>
            @if (isset($gst))
            GST: {{ $gst }}<br>
            @endif
        @endif
        <br>
    </span>
    </p>
  </div>
  <div style="border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;">
  <br>Name: {{ $order_content->name ?? '' }}<br>
  <br>Phone: {{ $order_content->phone ?? '' }}<br><br>
  </div><br>
  <div style="padding-bottom:80px;">
    <div style="float:left;">  
        <span style="font-size:18px;"><b>Sale No:</b> {{ $order->order_no }}</span><br>
        <br>Payment Status:
        @if($order->payment_status==2)
                Pending
            @elseif($order->payment_status==1)
                Paid
            @elseif($order->payment_status==0)
                Cancel
            @elseif($order->payment_status==3)
                Incomplete
        @endif   
    </div>
   <div style="float:right;">
        Date:{{ $order->created_at->format('d/m/Y') }} <br>
        @if(isset($order->shipping_info))
        <br>Delivery Type: {{ $order->shipping_info->shipping_method->name }}<br>
        @endif
    </div>
  </div>
  <table class="table" cellspacing="0" border="0">
        <thead style="border-top: 1px solid #ddd;">
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th style="text-align:center;">Quantity</th>
            <th style="text-align:right;">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->order_item as $key=>$orderitem)
            <tr>
                <td style="width:180px;">
                    {{ ucfirst($orderitem->term->title) }}
                    @php
                    $attributes=$orderitem->info;	 
                    @endphp
                    @foreach ($attributes->options ?? [] as $option)
                    <span>{{ __('Options') }} :</span> <small>{{ $option->name ?? '' }}</small>
                    @endforeach                                   
                </td>
                <td style="text-align:left; width:30px;">{!! amount_format_pdf($orderitem->amount)  !!}</td>
                <td style="text-align:center; width:50px;">{{ $orderitem->qty }}</td>
                <td style="text-align:right; width:70px; ">{!! amount_format_pdf($orderitem->amount*$orderitem->qty) !!}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <table class="table" cellspacing="0" border="0" style="margin-bottom:8px;">
        <tbody>
          <tr>
            <td colspan="2" style="text-align:left;">Discount</td>
            <td colspan="2" style="text-align:right;">- {!! amount_format_pdf($order_content->coupon_discount ?? 0) !!}</td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:left;border:none;">Tax</td>
            <td colspan="2" style="text-align:right;border:none;">{!! amount_format_pdf($order->tax) !!}</td>
          </tr>
          @if($order->order_type == 1)
          <tr>
            <td colspan="2" style="text-align:left;border:none;">Shippping</td>
            <td colspan="2" style="text-align:right;border:none;">{!! amount_format_pdf($order->shipping) !!}</td>
          </tr>
          @endif
          <tr>
            <td colspan="2" style="text-align:left;border:none;">Sub Total</td>
            <td colspan="2" style="text-align:right;border:none;">{!! amount_format_pdf($order_content->sub_total ?? 0) !!}</td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px; font-size: 20px;">Grand Total</td>
            <td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold; font-size: 20px;">{!! amount_format_pdf($order->total) !!}</td>
          </tr>
        </tbody>
      </table>
      <div style="clear:both;">
        <div class="text-center" style="padding:5px;border:1px solid #000;margin:40px 0px 0px 0px;">Thank you for your Visit, Do visit again&nbsp;&nbsp;</div>
      </div>
  </div>
</div>
</body>
</html>