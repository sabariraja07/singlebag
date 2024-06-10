@extends('multibag::layouts.app') 
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/thanks.css') }}" />
@endpush
@section('content')
@section('breadcrumb')
@php
$title="Order Confirmation";
@endphp
<li class="active">{{ __('Order Confirmation') }}</li>
@endsection
<div class="order-complete-area bg-gray pt-80 pb-160">
	<div class="container">
		<div class="order-product-details text-center">
			<div class="card-body padding-top-2x">
				<h3 class="card-title">{{ __('Thank you for your order!') }}</h3>
				<p class="card-text">{{ __('Your order has been placed and will be processed as soon as possible.') }}</p>
				<p class="card-text">{{ __('Make sure you make note of your order number, which is') }} <span class="font-weight-bold">{{ Session::get('order_no') }}</p>
				<p class="card-text">{{ __('You will be receiving an email shortly with confirmation of your order.') }}</p>
				<div class="padding-top-1x padding-bottom-1x">
					<a class="default-btn btn-md" href="{{ url('/shop') }}">{{ __('Go Back Shopping') }}</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection	