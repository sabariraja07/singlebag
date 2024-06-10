@extends('layouts.partials.auth')
@if(Route::currentRouteName() == 'seller.signup')
@section('title', 'Seller Register')
@elseif(Route::currentRouteName() == 'supplier.signup')
@section('title', 'Supplier Register')
@elseif(Route::currentRouteName() == 'reseller.signup')
@section('title', 'Reseller Register')
@endif
@section('vendor-style')
<style>
    .card-horizontal {
        display: flex;
        flex: 1 1 auto;
    }
    .ml-2 {
        margin-left: 10px !important;  
    }
    .mr-2 {
        margin-left: 10px !important;  
    }
    #shop_category_id-error{
        position: absolute !important;
    }
    .store-name {
        color: green !important;
    }

    #shopNameHelp {
        font-size: 14px !important;
    }

    .error-txt {
        color: red;
        font-size: 14px !important;
    }

    .success-txt {
        color: green;
        font-size: 14px !important;
    }

</style>
@endsection
@section('content')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-cover">
                    <div class="auth-inner row m-0">
                        <!-- Left Text-->
                        <div class="col-lg-3 d-none d-lg-flex align-items-center p-0">
                            <!-- Brand logo-->
                            <div class="brand-logo d-block" style=" display: block !important; margin: 10px auto auto auto; position: unset !important">
                                <img src="./images/logo.png" alt="" srcset="" style="width: 60%;margin: auto;display: block;">
                                <h2 class="stepper-head" style="width: 100%;"></h2>
                                <img class="" src="./assets/img/auth/signup_banner_new.png" alt="multi-steps" style="width: 100%;">
                                <p class="stepper-content" style=" margin: 20px; ">Stop struggling with manual order managements and focus on the real choke points. Discover a full featured E-Commerce management platform.</p>
                                <p class="mt-2 text-center" style="width: 100%;">Already have an account ?</p>
                                <div class="text-center"><a href="/verify-store" class="btn btn-primary">Login</a></div>
                            </div>
                            <!-- /Brand logo-->
                        </div>
                        <!-- /Left Text-->

                        <!-- Register-->
                        <div class="col-lg-9 d-flex align-items-center auth-bg px-2 px-sm-3 px-lg-5 pt-3">
                            <div class="width-900 mx-auto">
                                <div class="bs-stepper register-multi-steps-wizard shadow-none">
                                    <div class="bs-stepper-header px-0" role="tablist">
                                        <div class="step" data-target="#account-details" role="tab"
                                            id="account-details-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">
                                                    <i data-feather="shopping-bag" class="font-medium-3"></i>
                                                </span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title" style="font-weight: 100;">Create
                                                        Store</span>
                                                    <span class="bs-stepper-subtitle">Enter your details</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="line">
                                            <i data-feather="chevron-right" class="font-medium-2"></i>
                                        </div>
                                        <div class="step" data-target="#category" role="tab" id="category-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">
                                                    <i data-feather="globe" class="font-medium-3"></i>
                                                </span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title" style="font-weight: 100;">Store
                                                        Category</span>
                                                    <span class="bs-stepper-subtitle">Select Store Category</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="line">
                                            <i data-feather="chevron-right" class="font-medium-2"></i>
                                        </div>
                                        <div class="step" data-target="#personal-info" role="tab"
                                            id="personal-info-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">
                                                    <i data-feather="book" class="font-medium-3"></i>
                                                </span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title" style="font-weight: 100;">Store
                                                        Description</span>
                                                    <span class="bs-stepper-subtitle">Enter Information</span>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="line">
                                            <i data-feather="chevron-right" class="font-medium-2"></i>
                                        </div>
                                        <div class="step" data-target="#billing" role="tab" id="billing-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">
                                                    <i data-feather="map-pin" class="font-medium-3"></i>
                                                </span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title" style="font-weight: 100;">Store
                                                        Location</span>
                                                    <span class="bs-stepper-subtitle">Enter address</span>
                                                </span>

                                            </button>
                                        </div>
                                        <div class="line">
                                            <i data-feather="chevron-right" class="font-medium-2"></i>
                                        </div>
                                        <div class="step" data-target="#bankdetails" role="tab"
                                            id="bankdetails-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">
                                                    <i data-feather="home" class="font-medium-3"></i>
                                                </span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title" style="font-weight: 100;">Store Bank
                                                        Details</span>
                                                    <span class="bs-stepper-subtitle">Enter Bank Details</span>
                                                </span>

                                            </button>
                                        </div> 
                                        <div class="line" id="bankdetails-line">
                                            <i data-feather="chevron-right" class="font-medium-2"></i>
                                        </div>
                                        <div class="step" data-target="#language" role="tab" id="language-trigger">
                                            <button type="button" class="step-trigger">
                                                <span class="bs-stepper-box">
                                                    <i data-feather="globe" class="font-medium-3"></i>
                                                </span>
                                                <span class="bs-stepper-label">
                                                    <span class="bs-stepper-title" style="font-weight: 100;">Store
                                                        Language</span>
                                                    <span class="bs-stepper-subtitle">Select language</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content px-0 mt-4">
                                        <div id="account-details" class="content" role="tabpanel"
                                            aria-labelledby="account-details-trigger">
                                            <div class="content-header mb-2">
                                                <h2 class="fw-bolder mb-75">
                                                    @if(Route::currentRouteName() == 'seller.signup')
                                                        Hi There , Let's create your own store!
                                                    @elseif(Route::currentRouteName() == 'supplier.signup')
                                                        Hi There , Let's create your own supplier store!
                                                    @elseif(Route::currentRouteName() == 'reseller.signup')
                                                        Hi There , Let's create your own reseller store!
                                                    @endif
                                                
                                                </h2>
                                                <span>Build an online store with Singlebag by providing your details below,
                                                    find new customers and start selling .</span>
                                            </div>
                                            <form class="basicform" method="post"
                                                action="{{ route('seller.register') }}" id="form1">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="first_name">{{ __('First Name') }}</label>
                                                        <input class="form-control" type="text" name="first_name"
                                                            value="{{ old('first_name') }}" placeholder="john" required />
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="last_name">{{ __('Last Name') }}</label>
                                                        <input class="form-control" type="text" name="last_name"
                                                            value="{{ old('last_name') }}" placeholder="doe" required />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label" for="email">Email</label>
                                                        <input class="form-control" type="email" name="email"
                                                            value="{{ old('email') }}" placeholder="john.doe@email.com"
                                                            required />
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="mobile_number">{{ __('Mobile Number') }}</label>
                                                        <input class="form-control" type="number" name="mobile_number"
                                                            value="{{ old('mobile_number') }}" oninput="this.value=this.value.slice(0,this.maxLength)" placeholder="9876543210"
                                                            maxlength="10" required />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="password">{{ __('Password') }}</label>
                                                        <input class="form-control" type="password" name="password"
                                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                            required />
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="confirm-password">{{ __('Confirm Password') }}</label>
                                                        <input class="form-control" type="password"
                                                            name="password_confirmation"
                                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                            required />
                                                    </div>

                                                    <div class="col-12 mb-1">
                                                        <label class="form-label"
                                                            for="shop_type">{{ __('Shop Type') }}</label>
                                                        <select class="form-control col-sm-12 input" name="shop_type"
                                                            id="shop_type" required>
                                                            <option value="">Select Shop Type</option>
                                                            <option value="seller">Seller</option>
                                                            <option value="supplier">Supplier</option>
                                                            <option value="reseller">Reseller</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 mb-1">
                                                        <label class="form-label"
                                                            for="shop_name    ">{{ __('Shop Name') }}</label>
                                                        <input id="shop_name" class="form-control" name="shop_name"
                                                            type="text" value="{{ old('shop_name') }}"
                                                            placeholder="{{ __('Shop Name') }}" required />
                                                            The entered store name is taken as your store domain name. This can be changed only by adding an existing domain or purchasing a new one.                                                        
                                                        @if ($errors->has('shop_name'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('shop_name') }}</strong>
                                                            </span>
                                                        @endif
                                                        <div id="shopNameHelp" class="form-text text-muted is-hidden">
                                                            {{ __('Store Url') }} : <span
                                                                class="store-name"></span>.{{ env('APP_PROTOCOLESS_URL') }}
                                                        </div>
                                                        <div class="shop-helper is-hidden"></div>
                                                    </div>
                                                    @if (env('NOCAPTCHA_SITEKEY') != null)
                                                        @if (!stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') && !stripos($_SERVER['HTTP_USER_AGENT'], 'iPad'))
                                                            <div class="field pb-10">
                                                                <div class="control">
                                                                    {!! NoCaptcha::renderJs() !!}
                                                                    {!! NoCaptcha::display() !!}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="col-12 mb-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="agree" id="agree" />
                                                            <label class="form-check-label" for="agree">
                                                                {{ _('I agree with the') }}<a
                                                                    href="https://singlebag.com/terms-and-conditions/"
                                                                    class="color-primary" target=_blank>
                                                                    {{ _('terms and conditions') }}</a></label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-2">
                                                    <!-- <button class="btn btn-outline-secondary btn-prev" disabled>
                                                        <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                    </button> --><span></span>
                                                </div>
                                                <button class="btn btn-primary btn-next" type="submit" id="next1">
                                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                    <i data-feather="chevron-right"
                                                        class="align-middle ms-sm-25 ms-0"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div id="category" class="content" role="tabpanel"
                                            aria-labelledby="category-trigger">
                                            <div class="content-header mb-2">
                                                <h2 class="fw-bolder mb-75">Select Category</h2>
                                            </div>
                                            <form method="post" action="{{ route('seller.shopcategory') }}"
                                                id="form2">
                                                <div class="row">
                                                    <div class="col-12 mb-1">
                                                        <input type="hidden" class="shop_id" name="shop_id" />
                                                            @foreach($shop_categories as $shop_category)
                                                                <div class="container-fluid">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="card">
                                                                                <div class="card-horizontal select_category" data-id="{{$shop_category->id}}">
                                                                                    <input type="radio" class="ml-2 mr-2 mt-2 d-none" id="shop_category_id_{{$shop_category->id}}" name="shop_category_id" value="{{$shop_category->id}}" required/>
                                                                                    <div class="img-square-wrapper" style=" margin-top: 25px; margin-bottom: 25px;">
                                                                                        {{-- <img class="" src="{{$shop_category->preview->content}}" alt="{{$shop_category->name}}" width="100" height="100"> --}}
                                                                                        <img class="" src="{{$shop_category->image }}" width="100" height="100">
                                                                                    </div>
                                                                                    <div class="card-body" style=" margin-top: 25px; margin-bottom: 25px;">
                                                                                        <h4 class="card-title">{{$shop_category->name}}</h4>
                                                                                        <p class="card-text">{{ $shop_category->description }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                    </div>
                                                </div>
                                            <div class="d-flex justify-content-between mt-2" style=" float: right; ">
                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                    <i data-feather="chevron-right"
                                                        class="align-middle ms-sm-25 ms-0"></i>
                                                </button>
                                            </form>
                                            </div>
                                            <button class="btn btn-primary" id="skip2" style=" margin-left: 10px; ">Skip</button>
                                        </div>
                                        <div id="personal-info" class="content" role="tabpanel"
                                            aria-labelledby="personal-info-trigger">
                                            <div class="content-header mb-2">
                                                <h2 class="fw-bolder mb-75">Write about your store !</h2>
                                                <span>Describe about your store and business in short, which will be shown
                                                    up in the footer of your store site.</span>
                                            </div>
                                            <form method="post" action="{{ route('seller.shopdescription') }}"
                                                id="form3">
                                                <div class="row">
                                                    <div class="col-12 mb-1">
                                                        <input type="hidden" class="shop_id" name="shop_id" />
                                                        <label class="form-label" for="home-address">Store
                                                            Description</label>
                                                        <textarea class="form-control" rows="6" name="shop_description" required></textarea>
                                                    </div>
                                                </div>
                                            <div class="d-flex justify-content-between mt-2" style=" float: right; ">
                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                    <i data-feather="chevron-right"
                                                        class="align-middle ms-sm-25 ms-0"></i>
                                                </button>
                                            </form>
                                            </div>
                                            <button class="btn btn-primary" id="prev3">Prev</button>
                                            <button class="btn btn-primary" id="skip3" style=" margin-left: 10px; ">Skip</button>
                                        </div>
                                        <div id="billing" class="content" role="tabpanel"
                                            aria-labelledby="billing-trigger">
                                            <div class="content-header mb-2">
                                                <h2 class="fw-bolder mb-75">Where will your business be located ?</h2>
                                                <span>This location will show up on your store footer , which will benefit
                                                    the customers who are looking for your product.</span>
                                            </div>

                                            <form method="post" action="{{ route('seller.shopaddress') }}"
                                                id="form4">
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <input type="hidden" class="shop_id" name="shop_id" />
                                                        <label class="form-label"
                                                            for="address">{{ __('Address') }}</label>
                                                        <input class="form-control" type="text" name="address"
                                                            placeholder="" required />
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="city">{{ __('City') }}</label>
                                                        <input class="form-control" type="text" name="city"
                                                            placeholder="" required />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="state">{{ __('State') }}</label>
                                                        <input class="form-control" type="text" name="state"
                                                            placeholder="" required />
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label"
                                                            for="zip_code">{{ __('Postal / Zip Code') }}</label>
                                                        <input class="form-control" type="text" name="zip_code"
                                                            placeholder="" required/>
                                                    </div>
                                                </div>
                                            

                                            <div class="d-flex justify-content-between mt-2" style=" float: right; ">
                                                {{-- <button class="btn btn-primary btn-prev">
                                                    <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button> --}}
                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                    <i data-feather="chevron-right"
                                                        class="align-middle ms-sm-25 ms-0"></i>
                                                </button>
                                                </form>
                                            </div>
                                            <button class="btn btn-primary" id="prev4">Prev</button>
                                            <button class="btn btn-primary" id="skip4" style=" margin-left: 10px; ">Skip</button>
                                        </div>
                                        <div id="bankdetails" class="content" role="tabpanel"
                                            aria-labelledby="bankdetails-trigger">
                                            <div class="content-header mb-2">
                                                <h2 class="fw-bolder mb-75">Enter you Bank Details!</h2>
                                                <span>This will help us to send your earned margin to your respective bank account.</span>
                                            </div>

                                            <form method="post" action="{{ route('seller.shopbankdetails') }}"
                                                id="form5">
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <input type="hidden" class="shop_id" name="shop_id" />
                                                        <label class="form-label">{{ __('Account Holder Name') }}</label>
                                                        <input type="text" name="account_holder_name"
                                                            class="form-control" required="" placeholder="John"
                                                            value="{{ $bank_details->account_holder_name ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <label class="form-label">{{ __('IFSC Code') }}</label>
                                                        <input type="text" name="ifsc_code" class="form-control ifsc_code"
                                                            required="" placeholder="IAFC001"
                                                            value="{{ $bank_details->ifsc_code ?? '' }}" maxlength="11" minlength="11">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <label class="form-label">{{ __('Account No.') }}</label>
                                                        <input type="text" name="account_no" class="form-control account_no"
                                                            required="" placeholder="238576252"
                                                            value="{{ $bank_details->account_no ?? '' }}">
                                                    </div>
                                                </div>
                                            <div class="d-flex justify-content-between mt-2" style=" float: right; ">
                                                {{-- <button class="btn btn-primary btn-prev">
                                                    <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button> --}}
                                                <button class="btn btn-primary btn-next">
                                                    <span class="align-middle d-sm-inline-block d-none">Next</span>
                                                    <i data-feather="chevron-right"
                                                        class="align-middle ms-sm-25 ms-0"></i>
                                                </button>
                                            </form>
                                            </div>
                                            <button class="btn btn-primary" id="prev5">Prev</button>
                                            <button class="btn btn-primary" id="skip5" style=" margin-left: 10px; ">Skip</button>
                                        </div>
                                        <div id="language" class="content" role="tabpanel"
                                            aria-labelledby="language-trigger">
                                            <div class="content-header mb-2">
                                                <h2 class="fw-bolder mb-75">Set your default language !</h2>
                                                <span>This will be your default user interface language, which you can be
                                                    change anytime.</span>
                                            </div>

                                            <form method="post" action="{{ route('seller.shoplanguage') }}"
                                                id="form6">
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <label class="form-label"
                                                            for="address">{{ __('Select Default Language') }}</label>
                                                        <input type="hidden" class="shop_id" name="shop_id" />
                                                        <select class="form-control col-sm-12 input" name="local"
                                                            id="local" required>
                                                            <option value="">Select Default Language</option>
                                                            @foreach ($def_languages ?? [] as $key => $row)
                                                                <option value="{{ $row['code'] }}">{{ $row['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            <div class="d-flex justify-content-between mt-1" style=" float: right; ">
                                                {{-- <button class="btn btn-primary btn-prev">
                                                    <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                                </button> --}}
                                                <button class="btn btn-success btn-submit" id="submit6">
                                                    <i data-feather="check" class="align-middle me-sm-25 me-0"></i>
                                                    <span class="align-middle d-sm-inline-block d-none">Submit</span>
                                                </button>
                                            </form>
                                            </div>
                                            <button class="btn btn-primary" id="prev6">Prev</button>
                                            <button class="btn btn-primary" id="skip6" style=" margin-left: 10px; ">Skip</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('vendor-script')
    <script src="./vendors/js/forms/wizard/bs-stepper.min.js"></script>
@endsection
@section('page-script')
    <script>
        var registerMultiStepsWizard = document.querySelector('.register-multi-steps-wizard');
        var registerStepper = new Stepper(registerMultiStepsWizard);
        $('#shop_name').keyup(function() {
            var value =  $(this).val();
            var value = value.replace(/[^a-zA-Z0-9]/g,'').toLowerCase();
            $(this).val(value);
            if(value.length > 0){
                checkStoreNameAvailability(value);
                $('#shopNameHelp').removeClass('is-hidden');
                $('span.store-name').text(value);
            }else{
                $('#shopNameHelp').addClass('is-hidden');
            }
        });
        function checkStoreNameAvailability(name){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            var data = {'store_name' : name};
            $.ajax({
            type: 'POST',
            url: "{{url('/store-name-availability')}}",
            data: JSON.stringify(data),
            dataType: 'json',
            contentType: 'application/json',
            processData:false,
            beforeSend: function(){
                $('.shop-helper').removeClass('success-txt error-txt').addClass('is-hidden');
            },
            success: function(response){
                if(response.message)
                $('.shop-helper').text(response.message).addClass('success-txt').removeClass('is-hidden');
            },
            error: function(xhr, status, error) 
            {
                if(xhr.responseJSON.message){
                $('.shop-helper').text(xhr.responseJSON.message).addClass('error-txt').removeClass('is-hidden');
                }
            }
            });
        }
        function update_stepper_details(content_tab) {
            var stepper_content_2 = 'The Category which you are entering here can be changed or edited in your admin dashboard.To change the description, navigate to Admin panel- Settings-Shop settings. Discover a full featured E-Commerce management platform.'; 
            var stepper_content_3 = 'The Description which you are entering here can be changed or edited in your admin dashboard.To change the description, navigate to Admin panel- Settings-Shop settings. Discover a full featured E-Commerce management platform.'; 
            var stepper_content_4 = 'The Location details which you are entering here can be changed or edited in your admin dashboard. To change the description, navigate to Admin panel- Settings- Shop settings- General . Discover a full featured E-Commerce management platform'; 
            var stepper_content_5 = 'The Bank details which you are entering here can be changed or edited in your admin dashboard. To change the description, navigate to Admin panel- Settings- Shop settings- General . Discover a full featured E-Commerce management platform'; 
            var stepper_content_6 = 'The default language which you are entering here can be changed or edited in your admin dashboard. To change the description, navigate to Admin panel- Settings- Shop settings- General . Discover a full featured E-Commerce management platform'; 
            if(content_tab == 2){
                $('.stepper-content').html(stepper_content_2);
            }
            if(content_tab == 3){
                $('.stepper-content').html(stepper_content_3);
            }
            if(content_tab == 4){
                $('.stepper-content').html(stepper_content_4);
            }
            if(content_tab == 5){
                $('.stepper-content').html(stepper_content_5);
            }
            if(content_tab == 6){
                $('.stepper-content').html(stepper_content_6);
            }
        }
        $('.ifsc_code').change(function () {      
            var inputvalues = $(this).val();
            var reg = /[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/;
                if (inputvalues.match(reg)) {    
                    return true;  
                }    
                else {    
                    $('.ifsc_code').val("");   
                    alert("You entered invalid IFSC code");       
                    return false;    
                }    
            });
            $('.account_no').change(function () {      
            var inputvalues = $(this).val();
            var reg = '^[a-zA-Z0-9]{6,20}$';
                if (inputvalues.match(reg)) {    
                    return true;  
                }    
                else {    
                    $('.account_no').val("");   
                    alert("Account No. must be atleast 6 to 20 characters.");      
                    return false;  
                }    
            });
            $('#shop_type').change(function () {      
            var shop_type = $(this).val();
                if (shop_type == 'seller') {    
                    $("#bankdetails-trigger").css("display", "none");
                    $("#bankdetails-line").css("display", "none");
                } else {
                    $("#bankdetails-trigger").css("display", "block");
                    $("#bankdetails-line").css("display", "block");
                }      
            });

        $("#form1").on('submit', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var basicbtnhtml = $('#next1').html();
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#next1').html("Please Wait....");
                    $('#next1').attr('disabled', '');
                },
                success: function(response) {
                    $('.basicbtn').removeAttr('disabled');
                    $('.basicbtn').html(basicbtnhtml);
                    var shop_id = response.shop_id;
                    $('.shop_id').val(shop_id);
                    update_stepper_details(2);
                    registerStepper.next();
                },
                error: function(xhr, status, error) {
                    if ($(".g-recaptcha").length > 0) {
                        grecaptcha.reset();
                    }
                    $('#next1').removeClass('waves-effect');
                    $('#next1').removeClass('waves-float');
                    $('#next1').removeClass('waves-light');
                    $('#next1').html(basicbtnhtml);
                    $('#next1').removeAttr('disabled');
                    $('.errorarea').removeClass('is-hidden');
                    console.log(xhr.responseJSON.errors);
                    $.each(xhr.responseJSON.errors, function(key, item) {
                        Sweet('error', item)
                        $("#errors").html("<li class='text-danger'>" + item + "</li>")
                    });
                    errorsresponse(xhr, status, error);
                }
            })


        });

        $("#form2").on('submit', function(e) {
            console.log('test');
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,

                success: function(response) {
                    // Sweet('success',response.msg);
                    update_stepper_details(3);
                    registerStepper.next();
                },
                error: function(xhr, status, error) {
                    $.each(xhr.responseJSON.errors, function(key, item) {
                        Sweet('error', item);
                    });
                    errorsresponse(xhr, status, error);
                }
            })


        });

        $("#form3").on('submit', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,

                success: function(response) {
                    // Sweet('success',response.msg);
                    update_stepper_details(4);
                    registerStepper.next();
                }
            })


        });

        $("#form4").on('submit', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,

                success: function(response) {
                    // Sweet('success',response.msg);
                    update_stepper_details(5);
                    registerStepper.next();
                    var shop_type =  $( "#shop_type" ).val();
                    if(shop_type == 'seller'){
                        update_stepper_details(6);
                        registerStepper.next();
                    }
                }
            })


        });

        $("#form5").on('submit', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,

                success: function(response) {
                    // Sweet('success',response.msg);
                    update_stepper_details(6);
                    registerStepper.next();
                }
            })


        });

        $("#form6").on('submit', function(e) {

            var basicbtnhtml = $('#submit6').html();

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#submit6').html("Please Wait....");
                    $('#submit6').attr('disabled', '');
                },

                success: function(response) {
                    Sweet('success', response.msg);
                    window.setTimeout(function() {
                        if (response.redirect == true) {
                            window.location.href = response.domain;
                        } else {
                            window.location.href = $('#location_url').val();
                        }
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    $('#submit6').removeClass('waves-effect');
                    $('#submit6').removeClass('waves-float');
                    $('#submit6').removeClass('waves-light');
                    $('#submit6').html(basicbtnhtml);
                    $('#submit6').removeAttr('disabled');
                    if(xhr.status == '422'){

                    } else {
                    window.location.replace("/otp_error_page");
                    }
                }
            })


        });

        function Sweet(icon, title, time = 3000) {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: time,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })


            Toast.fire({
                icon: icon,
                title: title,
            })
        }

        $("#skip2").click(function(){
            update_stepper_details(3);
            registerStepper.next();
        });

        $("#skip3").click(function(){
            update_stepper_details(4);
            registerStepper.next();
        });

        $("#skip4").click(function(){
            update_stepper_details(5);
            registerStepper.next();
            var shop_type =  $( "#shop_type" ).val();
            if(shop_type == 'seller'){
                update_stepper_details(6);
                registerStepper.next();
            }
        });

        $("#skip5").click(function(){
            update_stepper_details(6);
            registerStepper.next();
        });

        $("#skip6").click(function(){
            $("#local :selected").removeAttr("selected");
            $("#local").val('en');
            $('#skip6').html("Please Wait....");
            $('#skip6').attr('disabled', '');
            $("#form6").submit();
        });

        $("#prev3").click(function(){
            update_stepper_details(2);
            registerStepper.previous();
        });

        $("#prev4").click(function(){
            update_stepper_details(3);
            registerStepper.previous(); 
        });

        $("#prev5").click(function(){
            update_stepper_details(4);
            registerStepper.previous(); 
        });

        $("#prev6").click(function(){
            var shop_type =  $( "#shop_type" ).val();
            if(shop_type == 'seller'){
                update_stepper_details(4);
                registerStepper.previous();
            } else {
            update_stepper_details(5); 
            }
            registerStepper.previous();
        });

        $('.select_category').on('click', function(e){
            e.preventDefault();
            $('.select_category').css('border', 'none');
            $(this).css('border', '1px solid blue');
            var id = $(this).attr("data-id");
            $('#shop_category_id_' + id).prop("checked", true);
        });
    </script>
@endsection
