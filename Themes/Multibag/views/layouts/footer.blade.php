<!--=====================================
FOOTER PART START
=======================================-->
<footer class="footer-area pt-160">
    <div class="footer-top pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <div class="footer-about footer-about-black mb-40">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ current_shop_logo_url() }}" onerror="this.onerror=null;this.src='/assets/img/empty/logo_300x120.jpg'" alt="logo">
                            </a>
                        </div>
                        <p>{{ Cache::get(domain_info('shop_id').'shop_description','') }}</p>
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
                            <li><img src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-contact.svg') }}" alt="" /><strong>
                                {{ __('Call Us') }}:</strong><span>
                                    @if (current_shop_type() == 'reseller')
                                    {{ $company_info->phone1 ?? '' }}
                                    @else 
                                    {{ $location['phone'] ?? "" }}
                                    @endif
                                </span></li>
                            <li><img src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-email-2.svg') }}"
                                    alt="" /><strong>{{ __('Email') }}:</strong><span> 
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
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        {{ App\Helper\Lphelper::custom_menu_data('multibag::menu.footer_menu', 'left') }}
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        {{ App\Helper\Lphelper::custom_menu_data('multibag::menu.footer_menu', 'center') }}
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-widget mb-40">
                        {{ App\Helper\Lphelper::custom_menu_data('multibag::menu.footer_menu', 'right') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom border-top-2 copyright-ptb-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-4 col-lg-3 col-md-12">
                    <div class="footer-menu">
                        {{-- <nav>
                            <ul>
                                <li><a href="javascript:void(0);">Terms</a></li>
                            </ul>
                        </nav> --}}
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-12">
                    <div class="copyright-2 text-center">
                        <p>
                            {{ __('Copyright') }} &copy; {{ date('Y') }} - {{ __('All rights reserved by') }}
                            <strong class="text-brand">{{ Cache::get(domain_info('shop_id').'shop_name',env('APP_NAME')) }}</strong>
                        </p>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-md-12">
                    @if(Cache::has(domain_info('shop_id').'socials'))
                        @php
                        $socials=json_decode(Cache::get(domain_info('shop_id').'socials',[]));
                        @endphp
                        @if(count($socials) > 0)
                        <div class="social-icon social-icon-right">
                            @foreach($socials as $key => $value)
                            <a href="{{ url($value->url) }}"><i class="{{ $value->icon }}" style=" color: black; "></i></a>
                            @endforeach
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
<!--=====================================
              FOOTER PART END
=======================================-->
