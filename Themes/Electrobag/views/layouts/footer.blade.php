<!--=====================================
FOOTER PART START
=======================================-->
<footer>
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-sm-6 col-md-3 col-xs-b30 col-md-b0">
                    <a href="{{ url('/') }}" class="mb-15">
                    <img src="{{ current_shop_logo_url() }}" style="width:150px" alt="logo" />
                      
                    </a>
                    <div class="empty-space col-xs-b20"></div>
                    <div class="simple-article size-2 light fulltransparent">{{ Cache::get(domain_info('shop_id').'shop_description','') }}</div>
                    @php
                        $location = Cache::get(domain_info('shop_id').'location','');
                        $location = json_decode($location, true);
                        if (current_shop_type() == 'reseller') {
                            $company_info = company_info();
                        }
                    @endphp
                    <div class="empty-space col-xs-b20"></div>
                    {{-- <div class="footer-contact"><i class="fa fa-mobile" aria-hidden="true"></i> {{ __('Call Us') }}: <a href="tel:{{$location['phone'] ?? ""}}">{{ $location['phone'] ?? "" }}</a></div>
                    <div class="footer-contact"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ __('Email') }}: <a href="mailto:{{ $location['email'] ?? "" }}">{{ $location['email'] ?? "" }}</a></div> --}}
                    <div class="footer-contact"><i class="fa fa-mobile" aria-hidden="true"></i> {{ __('Call Us') }}:
                        @if (current_shop_type() == 'reseller')
                            {{ $company_info->phone1 ?? '' }}
                        @else 
                            {{ $location['phone'] ?? "" }}
                        @endif
                    </div>
                    <div class="footer-contact"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ __('Email') }}: 
                        @if (current_shop_type() == 'reseller')                     
                            {{ $company_info->email1 ?? '' }}
                        @else
                            {{ $location['email'] ?? "" }}
                        @endif
                    </div>
                    <div class="footer-contact"><i class="fa fa-map-marker" aria-hidden="true"></i>
                        address: 
                        @if (current_shop_type() != 'reseller')
                        {{ $location['address'] ?? "" }},
                        @endif
                        {{ $location['city'] ?? "" }}
                        {{ $location['state'] ?? "" }}
                        @if (current_shop_type() != 'reseller')
                        -
                        {{ $location['zip_code'] ?? '' }}
                        @endif
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-xs-b30 col-md-b0">
                    <div class="footer-column-links">
                        <div class="row">
                            <div class="col-xs-6" style="width: 90% !important;">
                            {{ App\Helper\Lphelper::MenuCustomForElectrobag('left', 'footer-list mb-sm-5 mb-md-0') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-xs-b30 col-md-b0">
                    <div class="footer-column-links">
                        <div class="row">
                            <div class="col-xs-6">
                            {{ App\Helper\Lphelper::MenuCustomForElectrobag('center', 'footer-list mb-sm-5 mb-md-0') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-xs-b30 col-md-b0">
                    <div class="footer-column-links">
                        <div class="row">
                            <div class="col-xs-6">
                            {{ App\Helper\Lphelper::MenuCustomForElectrobag('right', 'footer-list mb-sm-5 mb-md-0') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>         
        <div class="footer-bottom">
            <div class="row">
                <div class="copyright">Copyright &copy; {{ date('Y') }} All rights reserved by  <strong
                    class="text-brand">{{ Cache::get(domain_info('shop_id').'shop_name',env('APP_NAME')) }}</strong></div>
                @if(Cache::has(domain_info('shop_id').'socials'))
                    @php
                    $socials=json_decode(Cache::get(domain_info('shop_id').'socials',[]));
                    @endphp
                    @if(count($socials) > 0)
                        <div class="col-lg-8 col-xs-text-center col-lg-text-left col-xs-b20 col-lg-b0">
                            <div class="follow">
                            @foreach($socials as $key => $value)
                            <a class="entry" href="{{ url($value->url) }}"><i class="{{$value->icon}}"></i></a>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
                   
        </div>
    </div>         
</footer>    
<!--=====================================
              FOOTER PART END
=======================================-->
