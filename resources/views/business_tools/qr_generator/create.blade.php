<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>QR Code Generator</title>
    <!-- Template CSS -->
    <link href="{{ asset('frontend/fashionbag/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <script>
        window.singlebag = {
            baseUrl: '{{ url('/') }}',
            rtl: false,
            storeName: '{{ env('APP_NAME') }}',
            loggedIn: '{{ auth()->guard('customer')->check()? 1: 0 }}',
            csrfToken: '{{ csrf_token() }}',
            langs: {!! get_lang_trans() !!},
            currency: {!! json_encode(currency_info(), true) !!},
            theme_color: '{{ Cache::get(domain_info('shop_id') . 'theme_color', '#dc3545') }}'
        };
    </script>
    <style>
        .HeaderLogo {
            margin: 20px;
            text-align: center;
        }

        hr {
            width: 93% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-top: -5px !important;
        }
          /* Mobile View */
	  @media screen and (max-width: 900px) {
            .seo_set{
				margin-left: 14px !important;
            }
        }
        @media screen and (min-width: 992px) {
            .seo_set{
				margin-left: 21px !important;
            }

        }
    </style>
    @include('layouts.partials.analytics')
</head>

<body>
    {{-- <div class="HeaderLogo"><a href="/">
            <img src="{{ admin_logo_url() }}" alt="Logo" width="150px"></a>
    </div> --}}
    {{-- <div class="section">
        <div class="breadcrumb-area bg-light">
            <div class="container-fluid">
                <div class="breadcrumb-content text-center">
                    <h1 class="title">{{ __('QR Code Generator') }}</h1>
                    <ul>
                        <li>
                            <a href="/">{{ __('Home') }}</a>
                        </li>
                        <li class="active">{{ __('QR Code Generator') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="section" style=" padding: 50px; ">
    <!-- BEGIN: Content-->
   
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        <ul style="margin-top: 1rem;">
                            <li>{{ Session::get('success') }}</li>
                        </ul>
                    </div>
                @endif
                @if (Session::has('error'))
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
               @endif

            </div>
            <div class="content-body">
                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Create your QR Code') }}</h4>
                                    
                                </div>
                                {{-- <p class="seo_set">{{ __('Enter your company information.') }}</p> --}}
                                <hr>
                                <div class="card-body">
                                    <form action="{{ route('qr-generator.store') }}" class="basicform_with_redirect" method="post">
                                        @csrf
                                        <input type="hidden" name="shop_id" id="shop_id" required="" class="form-control"
                                        value="{{ current_shop_id() }}">
                                        <div class="row">
                                            <div class="col-md-12 mb-2 data_type">
                                                <h4>{{ __('Select Data Type') }}</h4><br>
                                                <input type="radio" class="data_type" name="data_type" value="website" {{ old('data_type')=='website' ? 'checked' : '' }}> Website &nbsp;
                                                <input type="radio" class="data_type" name="data_type" value="phone" {{ old('data_type')=='phone' ? 'checked' : '' }}> Phone# &nbsp;
                                                <input type="radio" class="data_type" name="data_type" value="sms" {{ old('data_type')=='sms' ? 'checked' : '' }}> SMS &nbsp;
                                                <input type="radio" class="data_type" name="data_type" value="plain_text" {{ old('data_type')=='plain_text' ? 'checked' : '' }}> Plain Text &nbsp;
                                            </div>                                            
                                            <div class="col-md-6 mb-1 website">
                                                <label class="form-label">{{ __('Website') }}</label>
                                                <input class="form-control" name="website"
                                                    value="{{ $user->website ?? old('website') }}" type="text" placeholder="http://test.com">
                                                @if ($errors->has('website'))
                                                    <span id="signup-website-error" class="error">{{ $errors->first('website') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-1 phone d-none">
                                                <label class="form-label">{{ __('Phone') }}</label>
                                                <input class="form-control" name="phone"
                                                    value="{{ $user->phone ?? old('phone') }}" type="text" >
                                                @if ($errors->has('phone'))
                                                    <span id="signup-phone-error" class="error">{{ $errors->first('phone') }}</span>
                                                @endif           
                                            </div>
                                            <div class="col-md-6 mb-1 sms d-none">
                                                <label class="form-label">{{ __('Mobile Number') }}</label>
                                                <input class="form-control" name="mobile_number"
                                                    value="{{ $user->mobile_number ?? old('mobile_number') }}" type="text" >
                                                @if ($errors->has('mobile_number'))
                                                    <span id="signup-mobile-number-error" class="error">{{ $errors->first('mobile_number') }}</span>
                                                @endif
                                                <br>                                                
                                                <label class="form-label">{{ __('Message') }}</label>
                                                <input class="form-control" name="message"
                                                    value="{{ $user->message ?? old('message') }}" type="text" >
                                                @if ($errors->has('message'))
                                                    <span id="signup-message-error" class="error">{{ $errors->first('message') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-1 plain_text d-none">
                                                <label class="form-label">{{ __('Plain Text') }}</label>
                                                <input class="form-control" name="plain_text"
                                                    value="{{ $user->plain_text ?? old('plain_text') }}" type="text" >
                                                @if ($errors->has('plain_text'))
                                                    <span id="signup-plain-text-error" class="error">{{ $errors->first('plain_text') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary me-1 basicbtn">{{ __('Generate') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Floating Label Form section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <script src="{{ asset('frontend/fashionbag/js/plugins/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('frontend/fashionbag/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script type="text/javascript">
        $(document).on('change', '.data_type', function() {
            var data_type = $('input[name="data_type"]:checked').val();
            if(data_type == 'website'){
                $(".website").removeClass('d-none');
                $(".mobile_number").addClass('d-none');
                $(".plain_text").addClass('d-none');
                $(".sms").addClass('d-none');
            }
            else if(data_type == 'phone'){
                $(".website").addClass('d-none');
                $(".phone").removeClass('d-none');
                $(".plain_text").addClass('d-none');
                $(".sms").addClass('d-none');
            } 
            else if(data_type == 'sms'){
                $(".website").addClass('d-none');
                $(".phone").addClass('d-none');
                $(".plain_text").addClass('d-none');
                $(".sms").removeClass('d-none');
            } 
            else if(data_type == 'plain_text'){
                $(".website").addClass('d-none');
                $(".phone").addClass('d-none');
                $(".plain_text").removeClass('d-none');
                $(".sms").addClass('d-none');
            }
        });
    </script>
</body>

</html>