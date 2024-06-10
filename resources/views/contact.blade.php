@extends('main.page')
@section('content-header')

<div id="main-hero" class="hero-body">
    <div class="container has-text-centered">
        <div class="columns is-vcentered">
            <div class="column is-6 is-offset-3 has-text-centered is-subheader-caption">
                <h1 class="title is-2">{{ __('Contact Us') }}</h1>
                <h2 class="subtitle">{{ __('Get to Singlebag quickly for your future is a few steps away') }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')


<div class="section">
    <div class="container">
        <!--Contact Page 1-->
        <div class="contact-page-1">
            <div class="columns">
                <div class="column is-5">
                    <form action="{{ route('send_mail') }}" method="post" class="basicform_with_reset contact__form">
                        @csrf
                        <div class="contact-page-form flex-card">
                            <h3>Contact Us</h3>
                            <p>Use the form below to send us a message.</p>

                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="field">
                                        <label>Full Name</label>
                                        <div class="control has-icons-left">
                                            <input type="text" name="name" id="name" required class="input is-medium"
                                                placeholder="Your name">
                                            <span class="icon is-left is-small">
                                                <i class="sl sl-icon-user"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="field">
                                        <label>Email Address</label>
                                        <div class="control has-icons-left">
                                            <input name="email" id="email" type="email" required class="input is-medium"
                                                placeholder="Your email">
                                            <span class="icon is-left is-small">
                                                <i class="sl sl-icon-envelope-open"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <div class="field">
                                        <label>Message</label>
                                        <div class="control">
                                            <textarea name="message" id="message" class="textarea" required rows="4"
                                                placeholder="Write your message"></textarea>
                                        </div>
                                    </div>
                                </div>
                                @if(env('NOCAPTCHA_SITEKEY') != null)
                                <div class="column is-12">
                                    <div class="field">
                                        <div class="control">
                                            {!! NoCaptcha::renderJs() !!}
                                            {!! NoCaptcha::display() !!}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="column is-12">
                                    <div class="field">
                                        <div class="control">
                                            <button name="submit" type="submit"
                                                class="button button-cta primary-btn no-lh is-bold raised is-fullwidth basicbtn">
                                                Send Message
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="column is-7">
                    <div id="mapbox-2" class="mapbox-map map-1">
                        <div class="mapouter">
                        <div class="mapouter"><div class="gmap_canvas"><iframe width="600" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=singlebag,%20chennai&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://123movies-to.org"></a><br><style>.mapouter{position:relative;text-align:right;height:500px;width:600px;}</style><a href="https://www.embedgooglemap.net">google maps on web page</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:600px;}</style></div></div>
                        </div>
                    </div>
                </div>
            </div>
            @if(Cache::has('site_info'))
            @php
            $info=Cache::get('site_info','');
            @endphp
            <div class="columns has-text-centered pt-80 pb-80">
                <div class="column is-hidden-mobile"></div>
                <!-- Icon box -->
                <div class="column is-3">
                    <div class="shadow-icon-box rounded">
                        <span><i class="fa fa-map fa-lg"></i></span>
                    </div>
                    <div class="shadow-title dark-text">Address</div>
                    <div class="description shadow-description">
                        {{ $info->address ?? '' }}
                    </div>
                </div>
                <!-- Icon box -->
                <div class="column is-3">
                    <div class="shadow-icon-box rounded">
                        <span><i class="fa fa-envelope fa-lg"></i></span>
                    </div>
                    <div class="shadow-title dark-text">Email Us</div>
                    <div class="description shadow-description">{{ $info->email1 ?? '' }}</div>
                </div>
                <!-- Icon box -->
                <div class="column is-3">
                    <div class="shadow-icon-box rounded">
                        <span><i class="fa fa-phone-square fa-lg"></i></span>
                    </div>
                    <div class="shadow-title dark-text">Call Us</div>
                    <div class="description shadow-description">{{ $info->phone1 ?? '7338708058' }}</div>
                </div>
                <div class="column is-hidden-mobile"></div>
            </div>

            @endif
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
