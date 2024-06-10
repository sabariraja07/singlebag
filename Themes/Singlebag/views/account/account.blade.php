@extends('singlebag::account.layout.app')
@section('account_breadcrumb')
    <span></span> {{ __('Account details') }}
@endsection
@section('user_content')
<div class="tab-pane fade active show" id="account-detail" role="tabpanel" aria-labelledby="account-detail-tab">
	<div class="card">
		<div class="card-header">
			<h5>{{ __('Account details') }}</h5>
		</div>
		<div class="card-body">
			@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			@if (session('error'))
				<div class="alert alert-danger">
					{{ session('error') }}
				</div>
			@endif
			@if(Session::get('success'))
				<div class="alert alert-success">
					{{session::get('success')}}
				</div>
   			@endif
			
			<form method="post" class="basicform" name="enq" action="{{ url('/user/settings/update') }}">
				@csrf
				<div class="row">
					<div class="col-lg-8">
						<div class="row">
							<div class="form-group col-md-6">
								<label>{{ __('First Name') }} <span class="required">*</span></label>
								<input required="" class="form-control" name="first_name" id="first_name" type="text" value="{{ Auth::guard('customer')->user()->first_name }}"/>
							</div>
							<div class="form-group col-md-6">
								<label>{{ __('Last Name') }} <span class="required">*</span></label>
								<input required="" class="form-control" name="last_name" id="last_name" type="text" value="{{ Auth::guard('customer')->user()->last_name }}"/>
							</div>
							<div class="form-group col-md-12">
								<label>{{ __('Phone') }} <span class="required">*</span></label>
								<input required="" class="form-control" name="mobile" id="phone" @keypress="$isMobileNumber($event)" maxlength="10" minlength="10" value="{{ Auth::guard('customer')->user()->mobile }}"/>
							</div>
							<div class="form-group col-md-12">
								<label>{{ __('Email') }} <span class="required">*</span></label>
								<input required="" class="form-control" name="email" id="email" type="email" value="{{ Auth::guard('customer')->user()->email }}" />
							</div>
							<div class="form-group col-md-12">
								<label>{{ __('Current password (leave blank to leave unchanged) *') }} <span class="required">*</span></label>
								<input  class="form-control" name="password_current" type="password" name="password_current" id="password_current" autocomplete="off"/>
							</div>
							<div class="form-group col-md-12">
								<label>{{ __('New password (leave blank to leave unchanged) *') }} <span class="required">*</span></label>
								<input  class="form-control" name="password" id="password_1" type="password" autocomplete="off"/>
							</div>
							<div class="form-group col-md-12">
								<label>{{ __('Confirm new password *') }} <span class="required">*</span></label>
								<input class="form-control" name="password_confirmation" id="password_2" type="password" autocomplete="off"/>
							</div>
							<div class="col-md-12">
								<button type="submit" class="btn btn-fill-out submit font-weight-bold" name="submit" value="Submit">{{ __('Save change') }}</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
@push('js')
<!-- <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script> -->
<script>
$(function() {
	$('#password_current').bind('input', function() {
		$(this).val(function(_, v) {
			return v.replace(/\s+/g, '');
		});
	});
}); 
$(function() {
	$('#password_1').bind('input', function() {
		$(this).val(function(_, v) {
			return v.replace(/\s+/g, '');
		});
	});
});
$(function() {
	$('#password_2').bind('input', function() {
		$(this).val(function(_, v) {
			return v.replace(/\s+/g, '');
		});
	});
});
</script>

@endpush