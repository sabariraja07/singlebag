@extends('layouts.seller')
@section('title', 'Pricing Plans')
@section('page-style')
<link rel="stylesheet" type="text/css" href="./admin/assets/css/pages/page-pricing.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .popular_div{
    transition: 1s ease;
    }

    .popular_div:hover{
    -webkit-transform: scale(1.1);
    -ms-transform: scale(1.1);
    transform: scale(1.1);
    transition: 1s ease;
    }

    p:hover {
    cursor: pointer !important;
    }
</style>
@endsection
@section('content')
<!-- BEGIN: Content-->
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row mb-2">
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            {{ Session::get('success') }}
                        </div>
                    </div>
                @endif
                @if(Session::has('fail'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            {{ Session::get('fail') }}
                        </div>
                    </div>
                @endif
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('Pricing Plans') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Settings') }}</a>
                            </li>   
                            <li class="breadcrumb-item active">{{ __('Pricing Plans') }}
                            </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="pricing-plan">
                    <!-- pricing plan cards -->
                    <div class="row pricing-card">
                        <div class="col-12 col-sm-offset-2 col-sm-12 col-md-12 col-lg-offset-2 col-lg-12 mx-auto">
                            <div class="row">
                            @foreach($posts as $row)
                            @php
                            $data=json_decode($row->data);
                            @endphp
                            <!-- basic plan -->
                                <div class="col-12 col-md-4 popular_div mb-3 mt-2">
                                    <div class="card standard-pricing text-center">
                                        <div class="card-body">
                                            <!-- <img src="../../../app-assets/images/illustration/Pot1.svg" class="mb-2 mt-5" alt="svg img" /> -->
                                            <h3><b class="text-black">{{ ucfirst($row->name) }}</b></h3>
                                            <span class="card-text">{{ ucfirst($row->description) }}</span>
                                            <div class="annual-plan">
                                                <div class="plan-price mt-2">
                                                    <span class="pricing-basic-value fw-bolder text-black">{{ amount_admin_format($row->price) }}</span>
                                                    <sub class="pricing-duration text-body font-medium-1 fw-bold">/@if($row->days == 36500) {{ __('Unlimited') }} @elseif($row->days == 365) {{ __('Yearly') }} @elseif($row->days == 30) {{ __('Monthly') }} @else {{ $row->days }} {{ __('Days') }}  @endif</sub>
                                                </div>
                                                <small class="annual-pricing d-none text-muted"></small>
                                                @if(url('/') == env('APP_URL'))
                                                <a href="{{ route('merchant.make_payment',$row->id) }}" class="btn w-100 btn-primary mt-2">{{ __('SUBSCRIBE') }}</a>
                                                @else
                                                    <a href="{{ route('seller.make_payment',$row->id) }}" class="btn w-100 btn-primary mt-2">{{ __('SUBSCRIBE') }}</a>
                                                @endif
                                            </div>
                                            <ul class="list-group list-group-circle text-start">
                                                <li class="list-group-item">{{ __('Products Limit') }} : <b class="text-black">{{  $data->product_limit == -1 ? __('Unlimited')  : $data->product_limit }}</b></li>
                                                @if(env('MULTILEVEL_CUSTOMER_REGISTER') == true)
                                                <li class="list-group-item">{{ __('Customer Limit') }} : <b class="text-black">{{ $data->customer_limit == -1 ? __('Unlimited') : $data->customer_limit }}</b></li>
                                                @endif
                                                <li class="list-group-item">{{ __('Use your own domain') }} &nbsp                                                            
                                                    @if($data->custom_domain == 'true' || $data->custom_domain == true)
                                                    <span class="pricing-item-icon "><i data-feather="check-circle" stroke="#47c363"></i></span>
                                                    @else
                                                    <span class="pricing-item-icon"><i data-feather="x-circle" stroke="#fc544b"></i></span>
                                                    @endif
                                                </b></li>
                                                <li class="list-group-item">{{ __('Multi Language') }} &nbsp
                                                    <span class="pricing-item-icon"><i data-feather="check-circle" stroke="#47c363"></i></span>
                                                </b></li>
                                                <li class="list-group-item">{{ __('Seller Support') }} &nbsp
                                                    <span class="pricing-item-icon"><i data-feather="{{ $data->live_support == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->live_support == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                </b></li>
                                                <div class="accordion-item">
                                                <div id="accordionOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body" style=" padding: 0px; ">
                                                        <li class="list-group-item">{{ __('Storage Limit') }} : <b class="text-black">{{ $data->storage == -1 ? __('Unlimited')  : human_filesize($data->storage) }}</b></li>
                                                        {{-- <li class="list-group-item">{{ __('Storage Limit') }} : <b class="text-black">{{ $data->storage == -1 ? __('Unlimited')  : $data->storage . 'GB'  }}</b></li> --}}
                                                        <li class="list-group-item">{{ __('Shipping Zone Limit') }} : <b class="text-black">{{  $data->location_limit == -1 ? __('Unlimited') : $data->location_limit  }}</b></li>
                                                        <li class="list-group-item">{{ __('Category Limit') }} : <b class="text-black">{{  $data->category_limit == -1 ? __('Unlimited') : $data->category_limit  }}</b></li>
                                                        <li class="list-group-item">{{ __('Brand Limit') }} : <b class="text-black">{{  $data->brand_limit == -1 ? __('Unlimited') : $data->brand_limit }}</b></li>
                                                        <li class="list-group-item">{{ __('Product Variation Limit') }} : <b class="text-black">{{  $data->variation_limit == -1 ? __('Unlimited') : $data->variation_limit  }}</b></li>
                                                        <li class="list-group-item">{{ __('Google Analytics') }}  &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->google_analytics == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->google_analytics == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Facebook Pixel') }}  &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->facebook_pixel == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->facebook_pixel == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Google Tag Manager') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->gtm == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->gtm == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Whatsapp Plugin') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->whatsapp == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->whatsapp == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Inventory Management') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->inventory == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->inventory == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('POS') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->pos == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->pos == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('PWA') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->pwa == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->pwa == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('QRCODE') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->qr_code == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->qr_code == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Custom Js') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->custom_js == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->custom_js == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Custom Css') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->custom_css == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->custom_css == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Customer Panel Access') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="{{ $data->customer_panel == 'false' ? 'x-circle' : 'check-circle'  }}" stroke="{{ $data->customer_panel == 'false' ? '#fc544b' : '#47c363'  }}"></i></span>
                                                        </b></li>
                                                        <li class="list-group-item">{{ __('Image Optimization') }} &nbsp
                                                            <span class="pricing-item-icon"><i data-feather="check-circle" stroke="#47c363"></i></span>
                                                        </b></li>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button text-primary text-center font-weight-bold" type="button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                                        See all features
                                                    </button>
                                                </h2>
                                            </ul>                                                        
                                        </div>
                                    </div>
                                </div>
                                <!--/ basic plan -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!--/ pricing plan cards -->
                </section>

            </div>
        </div>
    </div>
<!-- END: Content-->

@endsection
@section('vendor-script')
<script>
$('.accordion-button').click(function(){
    $('.accordion-button').text(function(i,old){
        return old=='See less features' ?  'See all features' : 'See less features';
    });
});
</script>
@endsection
