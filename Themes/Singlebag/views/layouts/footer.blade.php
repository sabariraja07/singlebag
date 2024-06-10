<!--=====================================
FOOTER PART START
=======================================-->
<footer class="main">
    <section class="section-padding footer-mid">
        <div class="container pt-15 pb-20">
            <div class="row">
                <div class="col">
                    <div class="widget-about font-md mb-md-3 mb-lg-3 mb-xl-0 wow animate__animated animate__fadeInUp"
                        data-wow-delay="0">
                        <div class="logo mb-30">
                            <a href="{{ url('/') }}" class="mb-15">
                                <img src="{{ get_shop_logo_url(domain_info('shop_id')) }}" alt="logo" />
                            </a>
                            <p class="font-lg text-heading">
                                {{ Cache::get(domain_info('shop_id').'shop_description','') }}</p>
                        </div>
                        @php
                        $location = Cache::get(domain_info('shop_id').'location','');
                        $location = json_decode($location, true);
                        if (current_shop_type() == 'reseller') {
                            $company_info = company_info();
                        }
                        @endphp
                        <ul class="contact-infor">
                            <li><img src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-location.svg') }}"
                                    alt="" /><strong>{{ __('Address') }}: </strong>
                                <span>
                                    @if (current_shop_type() != 'reseller')
                                    {{ $location['address'] ?? "" }},
                                    @endif
                                    {{ $location['city'] ?? "" }}
                                    {{ $location['state'] ?? "" }}
                                    @if (current_shop_type() != 'reseller')
                                    -
                                    {{ $location['zip_code'] ?? '' }}
                                    @endif
                                </span>
                            </li>
                            <li><img src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-contact.svg') }}" alt="" />
                                <strong>
                                    {{ __('Call Us') }}:
                                </strong>
                                <span>
                                    @if (current_shop_type() == 'reseller')
                                    {{ $company_info->phone1 ?? '' }}
                                    @else 
                                    {{ $location['phone'] ?? "" }}
                                    @endif
                                </span>
                            </li>
                            <li><img src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-email-2.svg') }}"
                                    alt="" />
                                <strong>
                                    {{ __('Email') }}:
                                </strong>
                                <span> 
                                    @if (current_shop_type() == 'reseller')                     
                                    {{ $company_info->email1 ?? '' }}
                                    @else
                                    {{ $location['email'] ?? "" }}
                                    @endif
                                </span>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col"></div>
                <div class="footer-link-widget col wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    {{ App\Helper\Lphelper::MenuCustomForSingleBag('left', 'footer-list mb-sm-5 mb-md-0') }}
                </div>
                <div class="footer-link-widget col wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                    {{ App\Helper\Lphelper::MenuCustomForSingleBag('center', 'footer-list mb-sm-5 mb-md-0') }}
                </div>
                <div class="footer-link-widget col wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                    {{ App\Helper\Lphelper::MenuCustomForSingleBag('right', 'footer-list mb-sm-5 mb-md-0') }}
                </div>
                {{-- <div class="footer-link-widget widget-install-app col wow animate__animated animate__fadeInUp"
                    data-wow-delay=".5s">
                    <h4 class="widget-title">{{ __('Install App') }}</h4>
                    <p class="">{{ __('From App Store or Google Play') }}</p>
                    <div class="download-app">
                        <a href="#" class="hover-up mb-sm-2 mb-lg-0"><img class="active"
                                src="{{ asset('frontend/singlebag/imgs/theme/app-store.jpg') }}" alt="" /></a>
                        <a href="#" class="hover-up mb-sm-2"><img src="{{ asset('/singlebag/imgs/theme/google-play.jpg') }}"
                                alt="" /></a>
                    </div>
                </div> --}}
            </div>
    </section>
    <div class="container pb-30 wow animate__animated animate__fadeInUp" data-wow-delay="0">
        <div class="row align-items-center">
            <div class="col-12 mb-30">
                <div class="footer-bottom"></div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <p class="font-sm mb-0">{{ __('Copyright') }} &copy; {{ date('Y') }}, {{ __('All rights reserved by') }}
                    <strong
                        class="text-brand">{{ Cache::get(domain_info('shop_id').'shop_name',env('APP_NAME')) }}</strong>
                </p>
            </div>
            @if(Cache::has(domain_info('shop_id').'socials'))
            @php
            $socials=json_decode(Cache::get(domain_info('shop_id').'socials',[]));
            @endphp
            @if(count($socials) > 0)
            <div class="col-xl-4 col-lg-6 col-md-6 text-end d-none d-md-block">
                <div class="mobile-social-icon">
                    <h6>{{ __('Follow Us') }}</h6>
                    @foreach($socials as $key => $value)
                    <a href="{{ url($value->url) }}">
                        
                        <i class="{{ $value->icon }}" style="font-size: 30px" aria-hidden="true"></i></a>
                        {{-- <img
                            src="{{ 'frontend/singlebag/imgs/theme/icons/icon-' . $value->icon . '-white.svg' }}"
                            alt="" /></a> --}}
                    @endforeach
                    <!-- <a href="#"><img src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-twitter-white.svg') }}" alt="" /></a> -->
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</footer>
<!--=====================================
              FOOTER PART END
=======================================-->
