<!DOCTYPE html>
<html>
<head>
    <title>Payment With Razorpay</title>
</head>
<body>

<!-- // Let's Click this button automatically when this page load using javascript -->
<!-- You can hide this button -->
<button id="rzp-button1" hidden></button>  
<input type="hidden" id="logo" value="{{ current_shop_logo_url() }}">

<form action="{{ url('/payement/razorpay')}}" method="POST" hidden>
    <input type="hidden" value="{{csrf_token()}}" name="_token" /> 
    <input type="text" class="form-control" id="rzp_paymentid"  name="rzp_paymentid">
    <input type="text" class="form-control" id="rzp_orderid" name="rzp_orderid">
    <input type="text" class="form-control" id="rzp_signature" name="rzp_signature">
    <button type="submit" id="rzp-paymentresponse" hidden class="btn btn-fb"></button>
</form>

<input type="hidden" value="{{ $Info['razorpayId'] }}" id="razorpayId">
<input type="hidden" value="{{ $Info['amount'] }}" id="amount">
<input type="hidden" value="{{ $Info['currency'] }}" id="currency">
<input type="hidden" value="{{ $Info['name'] }}" id="name">
<input type="hidden" value="{{ $Info['description'] }}" id="description">
<input type="hidden" value="{{ $Info['orderId'] }}" id="orderId">
<input type="hidden" value="{{ $Info['name'] }}" id="name">
<input type="hidden" value="{{ $Info['email'] }}" id="email">
<input type="hidden" value="{{ $Info['contactNumber'] }}" id="contactNumber">
<input type="hidden" value="{{ $Info['address'] }}" id="address">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{ asset('js/jquery-3.5.1.min.js')}}"></script>
<script src="{{ asset('assets/js/razorpay.js')}}"></script>

</body>
</html>