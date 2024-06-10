@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Edit Plan'])
@endsection
@section('content')
<div class="row">
	<div class="col-12 mt-2">
		<div class="card">
			<div class="card-body">
				<form method="post" action="{{ route('admin.plan.update',$info->id) }}" class="basicform">
					@csrf
					@method('PUT')
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group">
								<label>{{ __('Shop Type') }}</label>
								<select class="form-control shop_type" name="shop_type" required="" disabled>
									<option value=''>{{ __('Select Shop Type') }}</option>
									<option value='Seller' @if($info->shop_type=='Seller' || $info->shop_type == NULL) selected @endif>{{ __('Seller') }}</option>
									<option value='Supplier' @if($info->shop_type=='Supplier') selected @endif>{{ __('Supplier') }}</option>
									<option value='Reseller' @if($info->shop_type=='Reseller') selected @endif>{{ __('Reseller') }}</option>
								</select>
							</div>
							<div class="form-group">
								<label>{{ __('Plan Name') }}</label>
								<input type="text" name="name" class="form-control" required value="{{ $info->name }}"> 
							</div>

							<div class="form-group">
								<label>{{ __('Plan description') }}</label>
								<input type="text" class="form-control" required="" name="description" value="{{ $info->description }}"></textarea>
							</div> 


							<div class="form-group">
								<label>{{ __('Plan Price') }}</label>
								<input type="number" min="0" step="any" name="price" class="form-control" required value="{{ $info->price }}"> 
							</div>
							
							<br>
                            <small>{{ __('Note :') }}</small>
							<small class="text-danger mt-4">{{ __('Limit set -1 for unlimited access') }}</small>
							<br>
							<div class="form-group">
								<label>{{ __('Product Limit') }}</label>
								<input type="number" min="-1" name="product_limit" class="form-control" value="{{ $plan_info->product_limit }}"> 
							</div>
							<div class="form-group">
								<label>{{ __('Storage Limit') }} (1024 MB = 1 GB)</label>
								<input type="number" min="-1" name="storage" class="form-control"  value="{{ $plan_info->storage }}"> 
							</div>
							<div class="form-group customer_limit">
								<label>{{ __('Customer Limit') }}</label>
								<input type="number" min="-1" value="{{ $plan_info->customer_limit }}"  name="customer_limit" class="form-control"> 
							</div>
							<div class="form-group agent_limit">
								<label>{{ __('Delivery Agent Limit') }}</label>
								<input type="number" min="-1" value="{{ $plan_info->agent_limit ?? -1 }}"  name="agent_limit" class="form-control"> 
							</div>
							<div class="form-group">
								<label>{{ __('Category Limit') }}</label>
								<input type="number" min="-1" value="{{ $plan_info->category_limit }}"  name="category_limit" class="form-control"> 
							</div>
							<div class="form-group location_limit">
								<label>{{ __('Location Limit') }}</label>
								<input type="number" min="-1" value="{{ $plan_info->location_limit }}"  name="location_limit" class="form-control"> 
							</div>
							<div class="form-group">
								<label>{{ __('Brand Limit') }}</label>
								<input type="number" min="-1" value="{{ $plan_info->brand_limit }}"  name="brand_limit" class="form-control"> 
							</div>
							<div class="form-group variation_limit">
								<label>{{ __('Variation Limit') }}</label>
								<input type="number" min="-1" value="{{ $plan_info->variation_limit }}"  name="variation_limit" class="form-control"> 
							</div>
							<div class="fom-group" style="margin-bottom: 20px;">
								<label>{{ __('Image') }}</label>
								<input type="file" name="file" accept="image/*" class="form-control" >
							</div>

							<div class="form-group">
								<button class="btn btn-success basicbtn" type="submit" >{{ __('Update') }}</button>
							</div>
						</div>
						<div class="col-sm-4">
							
							<div class="form-group">
								<label>{{ __('Duration') }}</label>
								<select class="form-control" name="days">
									<option value="36500" @if($info->days==36500) selected="" @endif>{{ __('Unlimited') }}</option>
									<option value="365"@if($info->days==365) selected="" @endif>{{ __('Yearly') }}</option>
									<option value="30"@if($info->days==30) selected="" @endif>{{ __('Monthly') }}</option>
									<option value="7"@if($info->days==7) selected="" @endif>{{ __('Weekly') }}</option>
									<option value="14" @if($info->days==14) selected="" @endif>{{ __('14 Days') }}</option>
									<option value="4" @if($info->days==4) selected="" @endif>{{ __('4 Days') }}</option>
								</select>
							</div>

							<div class="form-group inventory">
								<label>{{ __('Inventory') }}</label>
								<select class="form-control" name="inventory">
									<option @if($plan_info->inventory  == true || $plan_info->inventory  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->inventory  == false || $plan_info->inventory  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group custom_domain">
								<label>{{ __('Custom Domain') }}</label>
								<select class="form-control" name="custom_domain">
									<option @if($plan_info->custom_domain  == true || $plan_info->custom_domain  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->custom_domain  == false || $plan_info->custom_domain  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>

							
							<div class="form-group pos">
								<label>{{ __('POS') }}</label>
								<select class="form-control" name="pos">
									<option @if($plan_info->pos  == true || $plan_info->pos  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->pos  == false || $plan_info->pos  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group customer_panel">
								<label>{{ __('Customer Panel Access') }}</label>
								<select class="form-control" name="customer_panel">
									<option @if($plan_info->customer_panel  == true || $plan_info->customer_panel  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->customer_panel  == false || $plan_info->customer_panel  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group pwa">
								<label>{{ __('PWA') }}</label>
								<select class="form-control" name="pwa">
									<option @if($plan_info->pwa  == true || $plan_info->pwa  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->pwa  == false || $plan_info->pwa  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group whatsapp">
								<label>{{ __('Whatsapp modules') }}</label>
								<select class="form-control" name="whatsapp">
									<option @if($plan_info->whatsapp  == true ||  $plan_info->whatsapp  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->whatsapp  == false ||  $plan_info->whatsapp  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group live_support">
								<label>{{ __('Support') }}</label>
								<select class="form-control" name="live_support">
									<option @if($plan_info->live_support  == true || $plan_info->live_support  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->live_support  == false || $plan_info->live_support  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group qr_code">
								<label>{{ __('QR code') }}</label>
								<select class="form-control" name="qr_code">
									<option @if($plan_info->qr_code  == true || $plan_info->qr_code  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->qr_code  == false || $plan_info->qr_code  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group facebook_pixel">
								<label>{{ __('Facebook Pixel') }}</label>
								<select class="form-control" name="facebook_pixel">
									<option @if($plan_info->facebook_pixel  == true || $plan_info->facebook_pixel  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->facebook_pixel  == false || $plan_info->facebook_pixel  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group custom_css">
								<label>{{ __('Custom Css') }}</label>
								<select class="form-control" name="custom_css">
									<option @if($plan_info->custom_css  == true || $plan_info->custom_css  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->custom_css  == false || $plan_info->custom_css  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group custom_js">
								<label>{{ __('Custom Js') }}</label>
								<select class="form-control" name="custom_js">
									<option @if($plan_info->custom_js  == true || $plan_info->custom_js  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->custom_js  == false || $plan_info->custom_js  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group gtm">
								<label>{{ __('GTM') }}</label>
								<select class="form-control" name="gtm">
									<option @if($plan_info->gtm  == true || $plan_info->gtm  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->gtm  == false || $plan_info->gtm  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							<div class="form-group google_analytics">
								<label>{{ __('Google Analytics') }}</label>
								<select class="form-control" name="google_analytics">
									<option @if($plan_info->google_analytics  == true || $plan_info->google_analytics  == 'true') selected @endif  value=true >{{ __('Enable') }}</option>
									<option  @if($plan_info->google_analytics  == false || $plan_info->google_analytics  == 'false') selected @endif value=false >{{ __('Disable') }}</option>
								</select>
							</div>
							
							
							<div class="form-group">
								<label>{{ __('Is Featured ?') }}</label>
								<select class="form-control" name="featured">
									<option value="0" @if($info->featured==0) selected="" @endif>{{ __('No') }}</option>
									<option value="1" @if($info->featured==1) selected="" @endif>{{ __('Yes') }}</option>
								</select>
							</div>

							<div class="form-group">
								<label>{{ __('Is Trial ?') }}</label>
								<select class="form-control" name="is_trial">
									<option value="0" @if($info->is_trial==0) selected="" @endif>{{ __('No') }}</option>
									<option value="1" @if($info->is_trial==1) selected="" @endif>{{ __('Yes') }}</option>
								</select>
							</div>
							
							<div class="form-group">
								<label>{{ __('Status') }}</label>
								<select class="form-control" name="status">
									<option value="1" @if($info->status==1) selected="" @endif>{{ __('Enable') }}</option>
									<option value="0" @if($info->status==0) selected="" @endif>{{ __('Disable') }}</option>
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
$( document ).ready(function() {
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