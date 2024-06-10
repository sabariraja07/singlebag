@extends('layouts.seller')

@section('title', 'Create Shop')

@section('content')
<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">{{ __('Shops') }}</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/seller/dashboard">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="/seller/shops">{{ __('My Shops') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Create Shop') }}
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
        <div class="row justify-content-center">
            <div class="col-md-8 col-12">
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        {{ session()->get('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Create Shop') }}</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-vertical" id="configform" action="{{ route('seller.shop.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="signup-shop-name">{{ __('Shop name') }}</label>
                                        <input type="text" name="shop_name" id="signup-shop-name" class="form-control @if($errors->has('shop_name')) is-invalid @endif" value="{{ $user->shop_name ?? old('shop_name') }}" placeholder="Your Shop name" onkeypress="return /^[a-zA-Z0-9_]*$/i.test(event.key)"/>
                                        @if ($errors->has('shop_name'))
                                        <span id="signup-shop-name-error" class="error">{{ $errors->first('shop_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="email">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ $user->email ?? old('email') }}" placeholder="user@email.com"  />
                                        @if ($errors->has('email'))
                                        <span id="signup-email-error" class="error">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="mobile_number">{{ __('Mobile Number') }}</label>
                                        <input type="text" name="mobile_number" id="mobile_number" class="form-control @if($errors->has('mobile_number')) is-invalid @endif" value="{{ $user->mobile_number ?? old('mobile_number') }}" placeholder="9878979709" />
                                        @if ($errors->has('mobile_number'))
                                        <span id="signup-mobile_number-error" class="error">{{ $errors->first('mobile_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-1">
                                    <label class="form-label" for="address_line_1">{{ __('Address') }}</label>
                                    <input type="text" name="address" id="address_line_1" class="form-control @if($errors->has('address')) is-invalid @endif" value="{{ old('address') }}" placeholder="Address" />
                                    @if ($errors->has('address'))
                                    <span id="signup-address-error" class="error">{{ $errors->first('address') }}</span>
                                    @endif
                                  </div>
                                <div class="mb-1 col-12 col-md-6">
                                  <label class="form-label" for="storeTypeSelect">{{ __('Store Type') }}</label>
                                  <select name="shop_type" class="form-select @if($errors->has('shop_type')) is-invalid @endif" value="{{ old('shop_type') }}" id="storeTypeSelect">
                                      <option selected="" disabled="" >{{ __('Select your Store Type') }}</option>
                                      <option value="seller">{{ __('seller') }}</option>
                                      <option value="supplier">{{ __('Supplier') }}</option>
                                      <option value="reseller">{{ __('Reseller') }}</option>
                                  </select>
                                  @if ($errors->has('shop_type'))
                                  <label id="storeTypeSelect-error" class="error invalid-feedback">{{ $errors->first('shop_type') }}</label>
                                  @endif
                                </div>
                                <div class="mb-1 col-12 col-md-6">
                                    <label class="form-label" for="town-city">{{ __('Town/City') }}</label>
                                    <input type="text" name="city" id="town-city" class="form-control @if($errors->has('city')) is-invalid @endif" value="{{ old('city') }}" placeholder="Town/City" />
                                    @if ($errors->has('city'))
                                    <span id="signup-city-error" class="error">{{ $errors->first('city') }}</span>
                                    @endif
                                </div>
                                <div class="mb-1 col-12 col-md-6">
                                    <label class="form-label" for="town-state">{{ __('State') }}</label>
                                    <input type="text" name="state" id="town-state" class="form-control @if($errors->has('state')) is-invalid @endif" value="{{ old('state') }}" placeholder="state" />
                                    @if ($errors->has('state'))
                                    <span id="signup-state-error" class="error">{{ $errors->first('state') }}</span>
                                    @endif
                                </div>
                                <div class="mb-1 col-12 col-md-6">
                                    <label class="form-label" for="town-zip_code">{{ __('Pincode') }}</label>
                                    <input type="text" name="zip_code" id="town-zip_code" class="form-control @if($errors->has('zip_code')) is-invalid @endif" value="{{ old('zip_code') }}" placeholder="Pincode" />
                                    @if ($errors->has('zip_code'))
                                    <span id="signup-zip_code-error" class="error">{{ $errors->first('zip_code') }}</span>
                                    @endif
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="language">{{ __('Language') }}</label>
                                    <select name="language" class="form-select @if($errors->has('language')) is-invalid @endif" value="{{ old('language') }}" name="language" id="language">
                                        @php
                                        $all_language = active_lang_list();
                                        @endphp
                                        <option value="" label="Select Language"></option>
                                        @foreach ($all_language ?? [] as $key => $row)
                                            <option value="{{ $row['code'] }}">{{ $row['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('language'))
                                    <span id="signup-language-error" class="error">{{ $errors->first('language') }}</span>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <div class="mb-1">
                                        <label class="d-block form-label" for="shopDescription">{{ __('Description') }}</label>
                                        <textarea class="form-control" id="shopDescription" name="description" value="{{ old('description') }}" rows="3"></textarea>
                                        @if ($errors->has('description'))
                                        <span id="signup-description-error" class="error">{{ $errors->first('description') }}</span>
                                        @endif
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="mb-1">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck3">
                                            <label class="form-check-label" for="customCheck3">Remember me</label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">{{ __('Submit') }}</button>
                                    <button type="reset" id="configreset" class="btn btn-outline-secondary waves-effect">{{ __('Reset') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
@section('page-script')
    <script>
        $('#configreset').click(function(){
            $('#configform')[0].reset();
        });
    </script>
@endsection


