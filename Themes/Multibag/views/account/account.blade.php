@extends('multibag::account.layout.app')
@section('account_breadcrumb')
@php
$title = 'Account details';
@endphp
<li class="active"> {{ __('Account details') }} </li>
@endsection
@section('user_content')

<div class="myaccount-content">
	<h3>{{ __('Account details') }}</h3>
	<div class="account-details-form">
		<div class="row">
			<div class="col-sm-12">
				@if ($errors->any())
				<div class="alert alert-danger mb-40">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif
				@if (session('error'))
					<div class="alert alert-danger mb-40">
						{{ session('error') }}
					</div>
				@endif
				@if(Session::get('success'))
					<div class="alert alert-success mb-40">
						{{session::get('success')}}
					</div>
				@endif
			</div>
		</div>
		<form method="post" class="basicform" name="enq" action="{{ url('/user/settings/update') }}">
			@csrf
			<div class="row">
				<div class="col-lg-6">
					<div class="single-input-item">
						<label for="first-name" class="required">{{ __('First Name') }}</label>
						<input required="" class="form-control" name="first_name" id="first_name" type="text" value="{{ Auth::guard('customer')->user()->first_name }}"/>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="single-input-item">
						<label for="last-name" class="required">{{ __('Last Name') }}</label>
						<input required="" class="form-control" name="last_name" id="last_name" type="text" value="{{ Auth::guard('customer')->user()->last_name }}"/>					</div>
				</div>
			</div>
			<div class="single-input-item">
				<label for="display-name" class="required">{{ __('Phone') }}</label>
				<input required="" class="form-control" name="mobile" id="phone" @keypress="$isMobileNumber($event)" maxlength="10" minlength="10" value="{{ Auth::guard('customer')->user()->mobile }}"/>
			</div>
			<div class="single-input-item">
				<label for="display-name" class="required">{{ __('Email') }} <span class="required">*</span></label>
				<input required="" class="form-control" name="email" id="email" type="email" value="{{ Auth::guard('customer')->user()->email }}" />
			</div>
			<fieldset>
				<legend>Password change</legend>
				<div class="single-input-item">
					<label for="current-pwd" class="required">{{ __('Current password (leave blank to leave unchanged) *') }}</label>
					<input  class="form-control" name="password_current" type="password" name="password_current" id="password_current" autocomplete="off"/>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="single-input-item">
							<label for="new-pwd" class="required">{{ __('New password (leave blank to leave unchanged) *') }}</label>
							<input  class="form-control" name="password" id="password_1" type="password" autocomplete="off"/>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="single-input-item">
							<label for="confirm-pwd" class="required">{{ __('Confirm new password *') }}</label>
							<input class="form-control" name="password_confirmation" id="password_2" type="password" autocomplete="off"/>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="single-input-item">
				<button class="check-btn sqr-btn ">Save Changes</button>
			</div>
		</form>
	</div>
</div>
@endsection
@push('js')
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