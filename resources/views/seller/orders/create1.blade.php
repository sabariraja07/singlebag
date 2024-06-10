@extends('layouts.seller')

@section('title', 'Create Order')
@section('content')
@php
$url=domain_info('full_domain');
@endphp
<div class="content-wrapper container-md p-0 ecommerce-application">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">{{ __('Orders') }}</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/seller/dashboard">{{ __('Home') }}</a>
							<li class="breadcrumb-item"><a href="/seller/orders">{{ __('Orders') }}</a>
                            </li>
                            <li class="breadcrumb-item active">
								{{ __('Create Order') }}
                            </li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
		</div>
	</div>
	<div class="content-body">
		<section id="ecommerce-header">
			<div class="row">
				<div class="col-sm-12">
					<div class="ecommerce-header-items">
						<div class="result-toggler">
							<button class="navbar-toggler shop-sidebar-toggler" type="button" data-bs-toggle="collapse">
								<span class="navbar-toggler-icon d-block d-lg-none"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></span>
							</button>
							<div class="search-results">{{ $posts->total() }} results found</div>
						</div>
						{{-- <div class="view-options d-flex">
							<div class="btn-group dropdown-sort">
								<button type="button" class="btn btn-outline-primary dropdown-toggle me-1 waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="active-sorting">Featured</span>
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="#">Featured</a>
									<a class="dropdown-item" href="#">Lowest</a>
									<a class="dropdown-item" href="#">Highest</a>
								</div>
							</div>
							<div class="btn-group" role="group">
								<input type="radio" class="btn-check" name="radio_options" id="radio_option1" autocomplete="off" checked="">
								<label class="btn btn-icon btn-outline-primary view-btn grid-view-btn waves-effect" for="radio_option1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid font-medium-3"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></label>
								<input type="radio" class="btn-check" name="radio_options" id="radio_option2" autocomplete="off">
								<label class="btn btn-icon btn-outline-primary view-btn list-view-btn waves-effect" for="radio_option2"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list font-medium-3"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></label>
							</div>
						</div> --}}
					</div>
				</div>
			</div>
		</section>
		<div class="body-content-overlay"></div>
		<section id="ecommerce-searchbar" class="ecommerce-searchbar">
			<div class="row mt-1">
				<div class="col-sm-12 col-md-8">
					<form class="card-header-form">
						<div class="input-group">
							<input type="number" class="form-control search-product" name="search" id="shop-search" placeholder="Search Product id" aria-label="Search..." aria-describedby="shop-search">
							<button class="btn btn-outline-primary waves-effect" id="button-addon2" type="submit">Search</button>
						</div>
					</form>
				</div>
			</div>
		</section>
		<div id="place-order" class="list-view product-checkout">
			<div class="checkout-items">
				@foreach($posts as $key => $row)
				@php
					
				$price=amount_format($row->price->price);
				$qty=$row->stock->stock_qty;
				$product_id=$row->id;
				@endphp
				<form class="card ecommerce-card basicform" method="post" action="{{ route('seller.order.store') }}">
					<div class="item-img">
						<a href="{{ route('seller.products.edit',$row->id) }}">
							<img src="{{ asset($product->image ?? 'uploads/default.png') }}" alt="">
						</a>
					</div>
					<div class="card-body">
						@csrf
						<input type="hidden" name="product_id" value="{{ $product_id }}">
						<div class="item-name">
							<h6 class="mb-0">
								<a href="{{ url($url.'/product/'.$row->slug.'/'.$product_id) }}" class="text-body">
									{{ Str::limit($row->title,50) }}
								</a>
							</h6>
						</div>
						@if($row->stock->stock_manage != 0 && ($row->stock->stock_qty <= 0 || $row->stock->stock_status == 0))
						<span class="text-danger mb-1">{{ ('Out of Stock') }}</span>
						@else
						<span class="text-success mb-1">{{ ('In Stock') }}</span>
						@endif
						<div class="item-quantity">
							<span class="quantity-title">Qty:</span>
							<div class="cart-item-qty">
								<div class="input-group">
									<input class="touchspin-cart" type="number" @if($row->stock->stock_manage == 1) max="{{ $row->stock->stock_qty }}" @endif  min="0" required="" value="1" name="qty">
								</div>
							</div>
						</div>
						
						<span class="delivery-date text-muted">Regular Price : {{ amount_format($row->price->regular_price) }}</span>
						<span class="text-success mb-1">{{ round((($row->price->regular_price - $row->price->price) / $row->price->regular_price) * 100, 0) }}% Offer</span>
			
						@include('seller.orders.option', compact('row'))
					</div>
					<div class="item-options text-center">
						<div class="item-wrapper">
							<div class="item-cost">
								<h4 class="item-price">{{ $price }}</h4>
							</div>
						</div>
						@if($row->stock->stock_manage != 0 && ($row->stock->stock_qty <= 0 || $row->stock->stock_status == 0))
						@else
						<button class="btn btn-primary btn-cart waves-effect waves-float waves-light" onclick="assignId({{ $row->id }})" id="submitbtn{{ $row->id }}" type="submit">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
							<span class="add-to-cart">Add to cart</span>
						</button>
						@endif
					</div>
				</form>
				@endforeach
			</div>
			<div class="checkout-options">
                @include('seller.orders.checkout-detail')
				<!-- Checkout Place Order Right ends -->
			</div>
		</div>
		<section id="ecommerce-pagination">
			<div class="row">
				<div class="col-sm-12">
					<div class="d-flex justify-content-center mt-2">
						{{ $posts->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
					</div>
				</div>
			</div>
		</section>

	</div>
</div><input type="hidden" id="removecart_url" value="{{ url('/seller/order/cart/remove/') }}">
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css/pages/app-ecommerce.css') }}">
@endsection
@section('page-script')
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/order_create.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/scripts/pages/app-ecommerce.js') }}"></script>
<script type="text/javascript" src="{{ asset('./admin/js/scripts/forms/form-number-input.js') }}"></script>
<script>
	$(window).on('load', function() {
		if (feather) {
			feather.replace({
				width: 14,
				height: 14
			});
		}
	})
</script>
<script>
	 $("#shop-search").keyup(function(e){
  
		e.preventDefault();

		var src = $("#shop-search").val();

		$.ajax({
			type:'GET',
			url:"src=" + src,
			data:{ src : src },
			//_token: "{{ csrf_token() }}", 
			success:function(data){
				$("#pos-product-list").html(data);
			}
		});

	});
</script>
@endsection
