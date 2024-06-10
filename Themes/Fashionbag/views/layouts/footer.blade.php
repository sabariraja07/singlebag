<!--=====================================
FOOTER PART START
=======================================-->
<footer class="section footer-section">
    <!-- Footer Top Start -->
    <div class="footer-top section-padding">
        <div class="container">
            <div class="row mb-n10">
                <div class="col-12 col-sm-6 col-lg-4 col-xl-4 mb-10" data-aos="fade-up" data-aos-delay="200">
                    <div class="single-footer-widget">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ current_shop_logo_url() }}"
                                    onerror="this.onerror=null;this.src='/assets/img/empty/logo_300x120.jpg'"
                                    alt="logo">
                            </a>
                        </div>
                        {{-- <h2 class="widget-title">Contact Us</h2> --}}
                        <p class="desc-content">{{ Cache::get(domain_info('shop_id') . 'shop_description', '') }}</p>
                        <!-- Contact Address Start -->
                        @php
                            $location = Cache::get(domain_info('shop_id') . 'location', '');
                            $location = json_decode($location, true);
                            if (current_shop_type() == 'reseller') {
                            $company_info = company_info();
                        }
                        @endphp
                        <ul class="widget-address">
                            <li><span>{{ __('Address') }}: </span> 
                                @if (current_shop_type() != 'reseller')
                                {{ $location['address'] ?? "" }},
                                @endif
                                {{ $location['city'] ?? '' }},
                                {{ $location['state'] ?? '' }} 
                                @if (current_shop_type() != 'reseller')
                                -
                                {{ $location['zip_code'] ?? '' }}
                                @endif
                            </li>
                            <li><span>{{ __('Call Us') }}: </span>
                                @if (current_shop_type() == 'reseller')
                                {{ $company_info->phone1 ?? '' }}
                                @else  
                                {{ $location['phone'] ?? '' }} 
                                @endif
                            </li>
                            <li><span>{{ __('Email') }}: </span> 
                                @if (current_shop_type() == 'reseller')                     
                                {{ $company_info->email1 ?? '' }}
                                @else
                                {{ $location['email'] ?? '' }} 
                                @endif
                            </li>
                            {{-- <li><span>{{ __('Call Us') }}: </span> <a href="tel:{{$location['phone'] ?? ''}}"> {{ $location['phone'] ?? "" }}</a></li>
                            <li><span>{{ __('Email') }}: </span> <a href="mailto:{{ $location['email'] ?? "" }}"> {{ $location['email'] ?? "" }}</a></li> --}}
                        </ul>
                        <!-- Contact Address End -->

                        <!-- Soclial Link Start -->
                        @if (Cache::has(domain_info('shop_id') . 'socials'))
                            @php
                                $socials = json_decode(Cache::get(domain_info('shop_id') . 'socials', []));
                            @endphp
                            @if (count($socials) > 0)
                                <div class="widget-social justify-content-start mt-4">
                                    @foreach ($socials as $key => $value)
                                        <a href="{{ url($value->url) }}"><i class="{{ $value->icon }}"></i></a>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                        <!-- Social Link End -->
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-2 col-xl-2 mb-10" data-aos="fade-up" data-aos-delay="300">
                    <div class="single-footer-widget">
                        {{ App\Helper\Lphelper::custom_menu_data('fashionbag::menu.footer_menu', 'left') }}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-2 col-xl-2 mb-10" data-aos="fade-up" data-aos-delay="400">
                    <div class="single-footer-widget aos-init aos-animate">
                        {{ App\Helper\Lphelper::custom_menu_data('fashionbag::menu.footer_menu', 'center') }}
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-4 mb-10" data-aos="fade-up" data-aos-delay="500">
                    <div class="single-footer-widget">
                        {{ App\Helper\Lphelper::custom_menu_data('fashionbag::menu.footer_menu', 'right') }}
                        {{-- <h2 class="widget-title">Newsletter</h2>
                        <div class="widget-body">
                            <p class="desc-content mb-0">Get E-mail updates about our latest shop and special offers.</p>

                            <!-- Newsletter Form Start -->
                            <div class="newsletter-form-wrap pt-4">
                                <form id="mc-form" class="mc-form">
                                    <input type="email" id="mc-email" class="form-control email-box mb-40" placeholder="Enter your email here.." name="EMAIL">
                                    <button id="mc-submit" class="newsletter-btn btn btn-fb btn-hover-dark" type="submit">Subscribe</button>
                                </form>
                                <!-- mailchimp-alerts Start -->
                                <div class="mailchimp-alerts text-centre">
                                    <div class="mailchimp-submitting"></div><!-- mailchimp-submitting end -->
                                    <div class="mailchimp-success text-success"></div><!-- mailchimp-success end -->
                                    <div class="mailchimp-error text-danger"></div><!-- mailchimp-error end -->
                                </div>
                                <!-- mailchimp-alerts end -->
                            </div>
                            <!-- Newsletter Form End -->

                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Top End -->

    <!-- Footer Bottom Start -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <div class="copyright-content">
                        <p class="mb-0">
                            {{ __('Copyright') }} &copy; {{ date('Y') }} -
                            {{ __('All rights reserved by') }}
                            {{-- <a target="_blank" href="{{ url('/') }}"> --}}
                            <strong
                                class="text-brand">{{ Cache::get(domain_info('shop_id') . 'shop_name', env('APP_NAME')) }}</strong>
                            {{-- </a> --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Bottom End -->
</footer>
<!--=====================================
              FOOTER PART END
=======================================-->
