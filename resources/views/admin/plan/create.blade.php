@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Create Plan'])
@endsection
@section('content')
<div class="row">
	<div class="col-12 mt-2">
		<div class="card">
			<div class="card-body">
				<form method="post" action="{{ route('admin.plan.store') }}" class="basicform_with_reset">
					@csrf
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group">
								<label>{{ __('Shop Type') }}</label>
								<select class="form-control shop_type" name="shop_type" required="">
									<option value=''>{{ __('Select Shop Type') }}</option>
									<option value='Seller'>{{ __('Seller') }}</option>
									<option value='Supplier'>{{ __('Supplier') }}</option>
									<option value='Reseller'>{{ __('Reseller') }}</option>
								</select>
							</div>
							<div class="form-group">
								<label>{{ __('Plan Name') }}</label>
								<input type="text" name="name" class="form-control" required> 
							</div>

							<div class="form-group">
								<label>{{ __('Plan description') }}</label>
								<input type="text" name="description" class="form-control" required> 
								
							</div> 


							<div class="form-group">
								<label>{{ __('Plan Price') }}</label>
								<input type="number" min="0" step="any" name="price" class="form-control" required> 
							</div>
							<br>
                            <small>{{ __('Note :') }}</small>
							<small class="text-danger mt-4">{{ __('Limit set -1 for unlimited access') }}</small>
							<br>
							<div class="form-group">
								<label>{{ __('Product Limit') }}</label>
								<input type="number" min="-1"  name="product_limit" class="form-control"> 
							</div>
							<div class="form-group">
								<label>{{ __('Storage Limit') }} (1024 MB = 1 GB )</label>
								<input type="number" min="-1" name="storage" class="form-control"> 
							</div>
							<div class="form-group customer_limit">
								<label>{{ __('Customer Limit') }}</label>
								<input type="number"  min="-1" name="customer_limit" class="form-control"> 
							</div>
							<div class="form-group agent_limit">
								<label>{{ __('Delivery Agent Limit') }}</label>
								<input type="number" min="-1"  name="agent_limit" class="form-control"> 
							</div>
							<div class="form-group">
								<label>{{ __('Category Limit') }}</label>
								<input type="number"  min="-1" name="category_limit" class="form-control"> 
							</div>
							<div class="form-group location_limit">
								<label>{{ __('Location Limit') }}</label>
								<input type="number"  min="-1" name="location_limit" class="form-control"> 
							</div>
							<div class="form-group">
								<label>{{ __('Brand Limit') }}</label>
								<input type="number"  min="-1" name="brand_limit" class="form-control"> 
							</div>
							<div class="form-group variation_limit">
								<label>{{ __('Variation Limit') }}</label>
								<input type="number"  min="-1" name="variation_limit" class="form-control"> 
							</div>
							
							<div class="fom-group" style="margin-bottom: 20px;">
								<label>{{ __('Image') }}</label>
								<input type="file" name="file" accept="image/*" class="form-control" required="">
							</div>
							<div class="form-group">
								<button class="btn btn-success basicbtn" type="submit" >{{ __('Save') }}</button>
							</div>
						</div>
						<div class="col-sm-4">
							
							<div class="form-group">
								<label>{{ __('Duration') }}</label>
								<select class="form-control" name="days" required>
									<option value="">{{ __('Select Duration') }}</option>
									<option value="36500">{{ __('Unlimited') }}</option>
									<option value="365">{{ __('Yearly') }}</option>
									<option value="30">{{ __('Monthly') }}</option>
									<option value="7">{{ __('Weekly') }}</option>
									<option value="14">{{ __('14 Days') }}</option>
									<option value="4">{{ __('4 Days') }}</option>
								</select>
							</div>
							<div class="form-group custom_domain">
								<label>{{ __('Custom Domain') }}</label>
								<select class="form-control" name="custom_domain">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group inventory">
								<label>{{ __('Inventory') }}</label>
								<select class="form-control" name="inventory">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group pos">
								<label>{{ __('POS') }}</label>
								<select class="form-control" name="pos">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group customer_panel">
								<label>{{ __('Customer Panel Access') }}</label>
								<select class="form-control" name="customer_panel">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group pwa">
								<label>{{ __('PWA') }}</label>
								<select class="form-control" name="pwa">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group whatsapp">
								<label>{{ __('Whatsapp modules') }}</label>
								<select class="form-control" name="whatsapp">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group live_support">
								<label>{{ __('Support') }}</label>
								<select class="form-control" name="live_support">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group qr_code">
								<label>{{ __('QR code') }}</label>
								<select class="form-control" name="qr_code">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group facebook_pixel">
								<label>{{ __('Facebook Pixel') }}</label>
								<select class="form-control" name="facebook_pixel">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group custom_css">
								<label>{{ __('Custom Css') }}</label>
								<select class="form-control" name="custom_css">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group custom_js">
								<label>{{ __('Custom Js') }}</label>
								<select class="form-control" name="custom_js">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group gtm">
								<label>{{ __('GTM') }}</label>
								<select class="form-control" name="gtm">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group google_analytics">
								<label>{{ __('Google Analytics') }}</label>
								<select class="form-control" name="google_analytics">
									<option value=true>{{ __('Enable') }}</option>
									<option value=false>{{ __('Disable') }}</option>
								</select>
							</div>
							
							<div class="form-group">
								<label>{{ __('Is Featured ?') }}</label>
								<select class="form-control" name="featured">
									<option value="0">{{ __('No') }}</option>
									<option value="1">{{ __('Yes') }}</option>
								</select>
							</div>

							<div class="form-group">
								<label>{{ __('Is Trial ?') }}</label>
								<select class="form-control" name="is_trial">
									<option value="0">{{ __('No') }}</option>
									<option value="1">{{ __('Yes') }}</option>
								</select>
							</div>
							
							
							<div class="form-group">
								<label>{{ __('Status') }}</label>
								<select class="form-control" name="status">
									<option value="1">{{ __('Enable') }}</option>
									<option value="0">{{ __('Disable') }}</option>
								</select>
							</div>
						</div>
					</div>					
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
	$('.shop_type').on('change', function() {
		var shop_type = $('.shop_type').val();
		if(shop_type == 'Supplier') {
			$(".pos").addClass("d-none");
			$(".whatsapp").addClass("d-none");
			$(".live_support").addClass("d-none");
			$(".custom_css").addClass("d-none");
			$(".custom_js").addClass("d-none");
			$(".inventory").removeClass("d-none");
			$(".agent_limit").removeClass("d-none");
			$(".variation_limit").removeClass("d-none");
			$(".location_limit").removeClass("d-none");
			$(".custom_domain").addClass("d-none");
			$(".customer_panel").addClass("d-none");
			$(".pwa").addClass("d-none");
			$(".qr_code").addClass("d-none");
			$(".gtm").addClass("d-none");
			$(".facebook_pixel").addClass("d-none");
			$(".google_analytics").addClass("d-none");
			$(".customer_limit").addClass("d-none");
		}
		else if(shop_type == 'Reseller') {
			$(".pos").addClass("d-none");
			$(".whatsapp").addClass("d-none");
			$(".live_support").addClass("d-none");
			$(".custom_css").addClass("d-none");
			$(".custom_js").addClass("d-none");
			$(".inventory").addClass("d-none");
			$(".agent_limit").addClass("d-none");
			$(".variation_limit").addClass("d-none");
			$(".location_limit").addClass("d-none");
			$(".custom_domain").removeClass("d-none");
			$(".customer_panel").removeClass("d-none");
			$(".pwa").removeClass("d-none");
			$(".qr_code").removeClass("d-none");
			$(".gtm").removeClass("d-none");
			$(".facebook_pixel").removeClass("d-none");
			$(".google_analytics").removeClass("d-none");
			$(".customer_limit").removeClass("d-none");
		}
		else {
			$('.d-none').removeClass('d-none');
		}
    });
</script>
@endpush