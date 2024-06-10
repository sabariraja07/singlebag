@extends('layouts.auth')

@section('title') {{'Register Singlebag | Create your Store'}} @endsection
@push('css')
<style>
    .row-equal {
        display: flex;
        flex-wrap: wrap;
        background: #212060;
    }
    .col-equal {
        margin: auto;
    }
    .store-name{
      color:green !important;
    }
    #shopNameHelp{
      font-size: 14px !important;
    }
    .error-txt{
      color: red;
      font-size: 14px !important;
    }
    .success-txt{
      color: green;
      font-size: 14px !important;
    }
    .login-brand img{
      max-width: 200px !important;
    }
    .form-shop-name{
        font-weight: 600;
        color: #34395e;
        font-size: 12px;
        letter-spacing: .5px;
    }
    .signup-hero img.mockup {
      bottom: 5%;
      left: 0;
      right: 0;
      z-index: 0;
      max-width: 80%;
      margin: 0 auto;
  }
  .signup-hero img.mockup-alt{
    bottom: -15px;
    margin: 0 auto;
    width: 60%;
    left: 0;
    right: 0;
  }


html {
    height: 100%
}

p {
    color: grey
}

#heading {
    text-transform: uppercase;
    color: #673AB7;
    font-weight: normal
}

#msform {
    text-align: center;
    position: relative;
    margin-top: 20px;
    padding: 2.8rem 2.4rem 2.8rem;
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

.form-card {
    text-align: left
}

#msform fieldset:not(:first-of-type) {
    display: none
}

/* #msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #673AB7;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: #673AB7;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 0px 10px 5px;
    float: right
}

#msform .action-button:hover,
#msform .action-button:focus {
    background-color: #311B92
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px 10px 0px;
    float: right
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    background-color: #000000
} */

.card {
    z-index: 0;
    border: none;
    position: relative
}

.fs-title {
    font-size: 20px;
    margin-bottom: 15px;
    font-weight: 649;
    text-align: left
}

.purple-text {
    color: #673AB7;
    font-weight: normal
}

.steps {
    font-size: 25px;
    color: gray;
    margin-bottom: 10px;
    font-weight: normal;
    text-align: right
}

.fieldlabels {
    color: gray;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #673AB7
}

#progressbar li {
    list-style-type: none;
    font-size: 15px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400
}

#progressbar #account:before {
    font-family: FontAwesome;
    content: "\f13e"
}

#progressbar #personal:before {
    font-family: FontAwesome;
    content: "\f007"
}

#progressbar #payment:before {
    font-family: FontAwesome;
    content: "\f030"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: #673AB7
}

#skip2, #skip3, .previous{
    border: none !important;
}


.progress {
    height: 20px
}

.progress {
    display: -ms-flexbox;
    display: flex;
    height: 1rem;
    overflow: hidden;
    font-size: .75rem;
    background-color: #e9ecef;
    border-radius: 0.25rem;
}

.progress-bar {
    background-color: #673AB7
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

.progress-bar-striped {
    background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
    background-size: 1rem 1rem;
}

.fit-image {
    width: 50%;
    margin: auto;
    object-fit: cover
}
</style>
@endpush
@section('content')
<div class="login-wrapper is-gapless">
    <!-- Form section -->
    <div class="row row-equal">
    <div class="column is-5 col-equal">
        <div class="hero is-fullheight">
            <!-- Header -->
            <!-- <div class="hero-heading">
                <div class="auth-logo">
                    <a href="{{ url('/') }}">
                        <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}" alt="" />
                    </a>
                </div>
            </div> -->

            <!-- Body -->
            <div class="hero-body">
                <div class="container">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-11 col-sm-10 col-md-10 col-lg-6 col-xl-5 col-equal text-center p-0 mt-3 mb-2">
                                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                                    <h2 id="heading">                            
                                        <div class="auth-logo" style=" margin: 0px; ">
                                            <a href="{{ url('/') }}">
                                                <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}"
                                                    alt="" />
                                            </a>
                                        </div>
                                    </h2>
                <div id="msform">
                    <!-- progressbar -->
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div> 
                        <span id="progress_percent" style=" color: green; "></span>
                    <br> <!-- fieldsets -->
                    <fieldset>
                        <div class="form-card">
                            <div class="row">
                                <div class="col-7 col-equal">
                                    <b><h1 class="fs-title">Hi There , Let's create your own store!</h1></b>
                                    <p>Build an online store with Singlebag by providing your details below, find new customers and start selling .</p><br>
                                </div>
                            </div> 
                                <!-- Sign up form -->
                                <form class="basicform" method="post" action="{{ route('seller.register') }}" id="form1">
                                @csrf
                                <div id="signup-form" class="login-form animated preFadeInLeft fadeInLeft">
                                    <div class="field pb-10">
                                        <div class="control has-icons-right required @if ($errors->has('first_name')) has-error @endif">
                                        @if(Route::current()->getName() == 'seller.signup')
                                            <input type="hidden" name="shop_type" value="seller">
                                        @elseif(Route::current()->getName() == 'supplier.signup')
                                            <input type="hidden" name="shop_type" value="supplier">
                                        @elseif(Route::current()->getName() == 'reseller.signup')
                                            <input type="hidden" name="shop_type" value="reseller">
                                        @endif
                                            <input class="input is-medium has-shadow" type="text"
                                                name="first_name"
                                                value="{{ old('first_name') }}"
                                                placeholder="{{ __('First Name') }}" required />
                                            <span class="icon is-medium is-right">
                                                <i class="sl sl-icon-user"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('first_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="field pb-10">
                                        <div class="control has-icons-right required @if ($errors->has('last_name')) has-error @endif">
                                            <input class="input is-medium has-shadow" type="text"
                                                name="last_name"
                                                value="{{ old('last_name') }}"
                                                placeholder="{{ __('Last Name') }}" required />
                                            <span class="icon is-medium is-right">
                                                <i class="sl sl-icon-user"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('last_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="field pb-10">
                                        <div class="control has-icons-right required @if ($errors->has('email')) has-error @endif">
                                            <input class="input is-medium has-shadow" type="email"
                                                name="email"
                                                value="{{ old('email') }}"
                                                placeholder="{{ __('Email') }}" required />
                                            <span class="icon is-medium is-right">
                                                <i class="sl sl-icon-envelope-open"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="field pb-10">
                                        <div class="control has-icons-right required @if ($errors->has('mobile_number')) has-error @endif">
                                            <input class="input is-medium has-shadow" type="text"
                                                name="mobile_number"
                                                value="{{ old('mobile_number') }}"
                                                placeholder="{{ __('Mobile Number') }}" maxlength="10" minlength="10" required/>
                                            <span class="icon is-medium is-right">
                                                <i class="sl sl-icon-phone"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('mobile_number'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('mobile_number') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="field pb-10">
                                        <div class="control has-icons-right required @if ($errors->has('password')) has-error @endif">
                                            <input class="input is-medium has-shadow" type="password"
                                                name="password"
                                                placeholder="{{ __('Password') }}" required />
                                            <span class="icon is-medium is-right">
                                                <i class="sl sl-icon-lock"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="field pb-10">
                                        <div class="control has-icons-right">
                                            <input class="input is-medium has-shadow" type="password"
                                                name="password_confirmation"
                                                placeholder="{{ __('Password Confirmation') }}" required />
                                            <span class="icon is-medium is-right">
                                                <i class="sl sl-icon-lock"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="field pb-10">
                                        <div class="control has-icons-right required @if ($errors->has('shop_name')) has-error @endif">
                                            <input id="shop_name" class="input is-medium has-shadow"
                                                name="shop_name"  type="text"
                                                value="{{ old('shop_name') }}"
                                                placeholder="{{ __('Shop Name') }}" required />
                                            <span class="icon is-medium is-right">
                                                <i class="fa fa-store"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('shop_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('shop_name') }}</strong>
                                        </span>
                                        @endif
                                        <div id="shopNameHelp" class="form-text text-muted is-hidden">
                                        {{ __('Store Url') }} : <span class="store-name"></span>.{{ env('APP_PROTOCOLESS_URL') }}
                                        </div>
                                        <div class="shop-helper is-hidden"></div>
                                    </div>
                                    @if(env('NOCAPTCHA_SITEKEY') != null)
                                    @if(!stripos($_SERVER['HTTP_USER_AGENT'],"iPhone") && !stripos($_SERVER['HTTP_USER_AGENT'],"iPad"))
                                    <div class="field pb-10">
                                      <div class="control">
                                          {!! NoCaptcha::renderJs() !!}
                                          {!! NoCaptcha::display() !!}
                                      </div>
                                    </div>
                                    @endif
                                    @endif
                                    <div class="field pb-10">
                                        <div class="control">
                                            <label class="checkbox-wrap is-small muted-text">
                                                <input type="checkbox" class="d-checkbox" name="agree" id="agree" required>
                                                <span></span>
                                                {{ _('I agree with the') }}<a href="https://singlebag.com/terms-and-conditions/" class="color-primary" target=_blank> {{ _('terms and conditions') }}</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        </div> <br><button class="next button button-cta basicbtn primary-btn btn-align-lg rounded raised no-lh" id="next1">Next</button>
                        </form>
                        <button id="nextbutton1" style="display:none;"></button>
                    </fieldset>
                    <fieldset>
                        <form method="post" action="{{ route('seller.shopdescription') }}" id="form2">
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Write about your store !</h2>
                                        <p>Describe about your store and business in short, which will be shown up in the footer of your store site.</p><br>
                                    </div>
                                </div>
                                <div class="field pb-20">
                                    <div class="control">
                                        <input type="hidden" class="shop_id" name="shop_id"/>
                                        <textarea class="form-control textarea has-shadow" rows="6" name="shop_description" required ></textarea>
                                    </div>
                                </div>
                            </div><button class="next button button-cta basicbtn primary-btn btn-align-lg rounded raised no-lh is-pulled-right" id="next2" style="margin-left: 10px;">Next</button>
                        </form>
                        <button class="button button-cta btn-align-lg rounded raised no-lh is-pulled-right" id="skip2">Skip</button>                            
                        <button id="nextbutton2" style="display:none;"></button>
                    </fieldset>
                    <fieldset>
                        <form method="post" action="{{ route('seller.shopaddress') }}" id="form3">
                        <div class="form-card">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">Where will your business be located ?</h2>
                                    <p>This location will show up on your store footer , which will benefit the customers who are looking for your product.</p><br>
                                </div>
                            </div>
                            <div class="field pb-10">
                                <div class="control has-icons-right required">
                                    <input type="hidden" class="shop_id" name="shop_id"/>
                                    <input class="input is-medium has-shadow" type="text"
                                        name="address"
                                        placeholder="{{ __('Address') }}" required />
                                    <span class="icon is-medium is-right">
                                        <i class="sl sl-icon-paper-plane"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field pb-10">
                                <div class="control has-icons-right required">
                                    <input class="input is-medium has-shadow" type="text"
                                        name="city"
                                        placeholder="{{ __('City') }}" required />
                                    <span class="icon is-medium is-right">
                                        <i class="sl sl-icon-paper-plane"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field pb-10">
                                <div class="control has-icons-right required">
                                    <input class="input is-medium has-shadow" type="text"
                                        name="state"
                                        placeholder="{{ __('State') }}" required />
                                    <span class="icon is-medium is-right">
                                        <i class="sl sl-icon-paper-plane"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field pb-20">
                                <div class="control has-icons-right required">
                                    <input class="input is-medium has-shadow" type="text"
                                        name="zip_code"
                                        placeholder="{{ __('Postal / Zip Code') }}" required  />
                                    <span class="icon is-medium is-right">
                                        <i class="sl sl-icon-paper-plane"></i>
                                    </span>
                                </div>
                            </div>
                        </div><button class="next button button-cta basicbtn primary-btn btn-align-lg rounded raised no-lh is-pulled-right" id="next3" style="margin-left: 10px;" >Next</button> 
                        </form>
                        <button class="button button-cta btn-align-lg rounded raised no-lh is-pulled-right" id="skip3">Skip</button> 
                        <input type="button" name="previous" class="previous button button-cta basicbtn btn-align-lg rounded raised no-lh is-pulled-left" value="< Back" />                            
                        <button id="nextbutton3" style="display:none;"></button>
                    </fieldset>
                    <fieldset>
                        <form method="post" action="{{ route('seller.shoplanguage') }}" id="form4">
                        <div class="form-card">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">Set your default language !</h2>
                                    <p>This will be your default user interface language, which you can be change anytime.</p><br>
                                </div>
                            </div>
                            <div class="field pb-20">
                                <div class="control has-icons-right required">
                                <input type="hidden" class="shop_id" name="shop_id"/>
                                <select class="form-control col-sm-12 input" name="local" id="local" required>
                                        <option value="">Select Default Language</option>
                                    @foreach ($def_languages ?? [] as $key => $row)
                                        <option value="{{ $row['code'] }}">{{ $row['name'] }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div> <button class="next button button-cta basicbtn primary-btn btn-align-lg rounded raised no-lh is-pulled-right" id="next4" style="margin-left: 10px;">Submit</button> 
                        </form>
                        <button class="button button-cta btn-align-lg rounded raised no-lh is-pulled-right" id="skip4">Skip</button>
                        <input type="button" name="previous" class="previous button button-cta basicbtn btn-align-lg rounded raised no-lh is-pulled-left" value="< Back" />                            
                        <button id="nextbutton4" style="display:none;"></button>           
                    </fieldset>
                    <fieldset>
                        <div class="form-card">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="fs-title">Finish:</h2>
                                </div>
                            </div> <br><br>
                            <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2> <br>
                            <div class="row justify-content-center">
                                <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div>
                            </div> <br><br>
                            <div class="row justify-content-center">
                                <div class="col-7 text-center">
                                    <h5 class="purple-text text-center">You Have Successfully Signed Up</h5>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
                    <div class="columns">
                        <div class="column is-8 is-offset-2">
                            <div class="auth-content">
                                <a href="{{ url(env('APP_URL') . '/verify-store') }}">Have an account? Sign In </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image section (hidden on mobile) -->
    <div class="column login-column is-7 is-hidden-mobile hero-banner">
        <div class="hero signup-hero is-fullheight is-theme-primary is-relative">
            <div class="columns has-text-centered">
                <div class="column is-12">
                    <h1 class="title is-2 light-text">Start creating store now</h1>
                    <h3 class="subtitle is-5 light-text">
                    Stop struggling with manual order managements and focus on the real choke points. 
                    Discover a full featured E-Commerce management platform.
                    </h3>
                    <!-- App mockup -->
                    <img class="mockup-alt" src="{{ asset('assets/img/auth/signup_banner.png') }}" alt=""/>
                    <!-- Link to login page -->
                    <div class="already">
                        <span>Already have an account ?</span>
                        <a href="{{ url(env('APP_URL') . '/verify-store') }}"
                            class="button btn-align btn-outlined is-bold light-btn rounded">Login</a>
                    </div>
                </div>
                <!-- <div class="column is-12">
                <img class="mockup-alt" src="{{ asset('assets/img/auth/signup_banner.png') }}" alt="" />
                </div> -->
            </div>
        </div>
    </div>
    </div>
</div>

@endsection
@push('js')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>

<script type="text/javascript">
  "use strict";

  $('#shop_name').keyup(function() {
      var value =  $(this).val();
      var value = value.replace(/[^a-zA-Z0-9]/g,'').toLowerCase();
      $(this).val(value);
      if(value.length > 0){
        if(value.length >= 4) checkStoreNameAvailability(value);
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

  // $('shop_name').on('change', function(){

  // });
  $(document).ready(function(){

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var current = 1;
var steps = $("fieldset").length;

setProgressBar(current);


$("#nextbutton1").click(function(){
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative'
    });
    next_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(++current);   
});

$("#nextbutton2").click(function(){
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative'
    });
    next_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(++current);   
});

$("#nextbutton3").click(function(){
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative'
    });
    next_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(++current);   
});

$("#nextbutton4").click(function(){
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative'
    });
    next_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(++current);   
});

$("#skip2").click(function(){
    $("#nextbutton2").click(); 
});

$("#skip3").click(function(){
    $("#nextbutton3").click(); 
});

$("#skip4").click(function(){
    $("#nextbutton4").click();
    $("#local :selected").remove();
    $("#form4").submit(); 
});

$(".previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 500
});
setProgressBar(--current);
});

function setProgressBar(curStep){
var percent = parseFloat(100 / steps) * curStep;
percent = percent.toFixed();
if(percent <= 100) {
var percentage = percent+"%";
$("#progress_percent").text(percentage);
}
$(".progress-bar")
.css("width",percent+"%")
}
$(".submit").click(function(){
return false;
})

}); 
  $("#form1").on('submit', function(e){
    e.preventDefault();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var basicbtnhtml=$('#next1').html();
    $.ajax({
      type: 'POST',
      url: this.action,
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,       
      beforeSend: function() {
      $('#next1').html("Please Wait....");
      $('#next1').attr('disabled','')
    },    
      success: function(response){ 
        $('.basicbtn').removeAttr('disabled')
        // Sweet('success',response.msg);
        $('.basicbtn').html(basicbtnhtml);
        $("#nextbutton1").click();
        var shop_id = response.shop_id;
        $('.shop_id').val(shop_id);
        // if (response.redirect == true) {
        //   window.location.href = response.domain;
        // }
        // else{
        //   window.location.href = $('#location_url').val();
        // }
        
      },
      error: function(xhr, status, error) 
      {
        if($(".g-recaptcha").length > 0) {
            grecaptcha.reset();
        }
        $('.basicbtn').html(basicbtnhtml);
        $('.basicbtn').removeAttr('disabled')
        $('.errorarea').removeClass('is-hidden');
        $.each(xhr.responseJSON.errors, function (key, item) 
        {
          Sweet('error',item)
          $("#errors").html("<li class='text-danger'>"+item+"</li>")
        });
        errosresponse(xhr, status, error);
      }
    })


  });

  $("#form2").on('submit', function(e){
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
      processData:false,  

      success: function(response){
        // Sweet('success',response.msg);
        $("#nextbutton2").click();   
      }
    })


  });
  
  $("#form3").on('submit', function(e){
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
      processData:false,  

      success: function(response){
        // Sweet('success',response.msg);
        $("#nextbutton3").click();
      }
    })


  });

  $("#form4").on('submit', function(e){
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
      processData:false,  

      success: function(response){
        Sweet('success',response.msg);
        $("#nextbutton4").click();
        window.setTimeout(function() {
            if (response.redirect == true) {
            window.location.href = response.domain;
            }
            else{
            window.location.href = $('#location_url').val();
            }
        }, 3000);
      }
    })


  });
  function Sweet(icon,title,time=3000){
    
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
</script>
@endpush