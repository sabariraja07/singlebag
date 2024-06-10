@extends('singlebag::layouts.app') 
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/thanks.css') }}" />
@endpush
@section('content')
@section('breadcrumb')
<span></span> {{ __('Thank you') }}
@endsection
<div class="container mb-80 mt-50">
	<div class="row">
		<div class="col-lg-8 mb-40">
			<h1 class="heading-2 mb-10">{{ __('Order Confirmation') }}</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 mb-40">
			<div class="card text-center">
				<div class="card-body padding-top-2x">
					<h3 class="card-title">{{ __('Thank you for your order!') }}</h3>
					<p class="card-text">{{ __('Your order has been placed and will be processed as soon as possible.') }}</p>
					<p class="card-text">{{ __('Make sure you make note of your order number, which is') }} <span class="font-weight-bold">
					{{ Session::get('order_no') }}
					</p>
					<p class="card-text">{{ __('You will be receiving an email shortly with confirmation of your order.') }}</p>
					<div class="padding-top-1x padding-bottom-1x"><a class="btn btn-inline" href="{{ url('/shop') }}">{{ __('Go Back Shopping') }}</a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr>
@endsection	