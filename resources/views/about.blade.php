@extends('main.app')
@section('content')

<style>
    .section-subtitle-alt {
        text-align: justify !important;
        /* For Edge */
        text-align-last: center !important;
        max-width: 100% !important;
    }

</style>
<!-- Hero -->
<div class="hero parallax is-cover is-relative is-fullheight"
    data-background="{{ asset('assets/img/about_slider_1.jpeg') }}" data-color="#837FCB" data-color-opacity=".5"
    data-demo-background="{{ asset('assets/img/about_slider_1.jpeg') }}" data-page-theme="blue">
    <!-- Hero caption -->
    <div id="main-hero" class="hero-body">
        <div class="container">
            <div class="columns is-vcentered">
                <div class="column is-4 signup-column has-text-left">
                    <h1 class="title main-title text-bold is-2">{{ __('Singlebag your Store!') }}</h1>
                    <h2 class="subtitle is-5 no-margin-bottom is-light">
                        {{ __('One platform for all your dreams.') }}
                    </h2>
                </div>
                <div class="column is-7 is-offset-1">
                    <!-- Hero square video -->
                    <div class="bulkit-player-container is-square reversed-play" style="width: 100%;">
                        <iframe src="https://www.youtube.com/embed/3CLHZ0ug0lw?frameborder=0" 
                            allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            title="Singlebag story"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Benefits section -->
<div class="section is-medium is-grey">
    <div id="start" class="container">
        <!-- Title -->
        <div class="section-title-wrapper has-text-centered">
            <!-- <div class="bg-number">1</div> -->
            <h2 class="section-title-landing">Our Story</h2>
        </div>
        <div class="content-wrapper">
            <!-- Feature -->
            <div class="columns is-vcentered">
                <!-- Featured image -->
                <div class="column is-6 is-offset-1 has-text-centered">
                    <div class="masked-image">
                        <img class="featured-svg" src="{{ asset('assets/img/our_story.png') }}" alt="" />
                    </div>
                </div>
                <!-- Feature content -->
                <div class="column is-4 is-offset-1">
                    <span class="section-feature-description"> 
                        Singlebag is your go-to partner for setting <br>e-commerce business store online. Our <br>e-commerce platform
                        helps you set up your site and reach customers—from stock management to managing orders and billing.<span><br /><br />
                        <span class="section-feature-description">Singlebag e-commerce business comes with unimaginable features and a user-friendly approach to serve
                        you. We are the first <br>e-commerce platform for all kinds of businesses of different realms that you can
                        imagine. Having the future in our mind, we function on AI and well-versed technology.
                    </span>
                </div>
            </div>
        </div>
</div>
</div>

<!-- Features section -->
<div id="services" class="section is-medium">
    <div class="container">
        <!-- Title -->
        <div class="section-title-wrapper has-text-centered">
            <!-- <div class="bg-number d-none">2</div> -->
            <h2 class="section-title-landing">Our Milestone</h2>
        </div>
        <div class="content-wrapper">
            <!-- Feature -->
            <div class="columns is-vcentered">
                <!-- Feature content -->
                <div class="column is-4 is-offset-1">
                    <span class="section-feature-description">It's a single platform to sell products to anyone,
                        anywhere—in person with the Point of Sale and online through your website, social media and
                        online marketplaces.
                        We include everyone who wants to make the best use of Singlebag e-commerce platform to come up
                        in life.
                        Moreover, you can find expertise as well as learners.</span><br /><br />
                    <span class="section-feature-description">Our sole mission is to more happily serve the people who come to us with good products and
                        ideas, help them shine in their e-commerce business.
                    </span>
                </div>

                 <!-- Featured image -->
                 <div class="column is-6 has-text-centered">
                    <div class="masked-image">
                        <img class="main-image" src="{{ asset('assets/img/milestone.png') }}" alt="">
                        <img class="image-mask" src="{{ asset('assets/img/shapes/mask-1.svg') }}" alt="">
                        <div class="dot dot-primary dot-1 levitate"></div>
                        <div class="dot dot-success dot-2 levitate delay-3"></div>
                        <div class="dot dot-info dot-3 levitate delay-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="solution" class="section">
    <div class="container">
        <div class="section-title-wrapper py-4">
            <h2 class="title section-title has-text-centered dark-text text-bold">
            Why Singlebag?
            </h2>
        </div>

        <!-- Feature -->
        <div class="columns is-vcentered">
            <div class="column is-6 is-offset-1 has-text-centered">
                <div class="masked-image">
                    <img class="main-image" src="{{ asset('assets/img/partner/feature1.png') }}" alt="" />
                    <img class="image-mask" src="{{ asset('assets/img/shapes/mask-1.svg') }}" alt="" />
                    <!--Dots-->
                    <div class="dot dot-primary dot-1 levitate"></div>
                    <div class="dot dot-success dot-2 levitate delay-3"></div>
                    <div class="dot dot-info dot-3 levitate delay-2"></div>
                </div>
            </div>

            <div class="column is-4">
                <span class="section-feature-description">
                E-commerce is the top-approached platform and business ideas in India as well as abroad. It's a
                single online destination for all kinds of shopping you do offline. As the world travels towards
                technology, all our activities from the basic also try to adapt it for a long run.
                </span>
            </div>
        </div>

        <!-- Feature -->
        <div class="columns is-vcentered">
            <div class="column is-6 has-text-centered is-hidden-tablet is-hidden-desktop">
                <div class="masked-image">
                    <img class="main-image" src="{{ asset('assets/img/about_banner_1.png') }}" alt="" />
                    <img class="image-mask" src="{{ asset('assets/img/shapes/mask-2.svg') }}" alt="" />
                    <!--Dots-->
                    <div class="dot dot-primary dot-4 levitate"></div>
                    <div class="dot dot-success dot-5 levitate delay-3"></div>
                    <div class="dot dot-info dot-3 levitate delay-2"></div>
                </div>
            </div>

            <div class="column is-4 is-offset-1">
                <span class="section-feature-description">
                Adopting e-commerce is an inevitable choice & makes your e-commerce business ideas also
                inevitable. With an e-commerce store in Singlebag, your selling processes become quicker than
                you think and your income doubles consecutively.
                </span>
            </div>

            <div class="column is-6 is-offset-1 has-text-centered is-hidden-mobile">
                <div class="masked-image">
                    <img class="main-image" src="{{ asset('assets/img/about_banner_2.png') }}" alt="" />
                    <img class="image-mask" src="{{ asset('assets/img/shapes/mask-2.svg') }}" alt="" />
                    <!--Dots-->
                    <div class="dot dot-primary dot-4 levitate"></div>
                    <div class="dot dot-success dot-5 levitate delay-3"></div>
                    <div class="dot dot-info dot-3 levitate delay-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process section -->
<div id="about" class="section is-app-grey is-medium">
    <div class="container">
        <!-- Title -->
        <div class="section-title-wrapper has-text-centered">
            <!-- <div class="bg-number">4</div> -->
            <h2 class="section-title-landing">Our Process</h2>
            <h3 class="section-subtitle-alt">
                    Before creation, there's thinking. Our process
                    is sharp and let us craft the best for you
            </h3>
        </div>

        <!-- Process steps -->
        <div class="content-wrapper">
            <div class="columns is-vcentered">
                <!-- Process step -->
                <div class="column is-4">
                    <div class="process-block has-line">
                        <div class="process-icon is-icon-reveal">
                            <div class="icon-wrapper">
                                <img src="{{ asset('assets/img/about-think.png') }}" width="80" height="80" alt="">
                                <div class="process-number">1</div>
                            </div>
                        </div>
                        <div class="process-info">
                            <!-- <div class="step-number">1</div> -->
                            <div class="details">
                                <div class="motto">Think</div>
                                <!-- <p class="description">
                                        Lorem ipsum dolor sit amet, eam ex probo tation tractatos. Ut
                                        vel hinc solet tincidunt, nec et iisque placerat pertinax. Ei
                                        minim probatus mea.
                                    </p> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Process step -->
                <div class="column is-4">
                    <div class="process-block has-line">
                        <div class="process-icon is-icon-reveal">
                            <div class="icon-wrapper">
                                <img src="{{ asset('assets/img/about-create.png') }}" width="80" height="80" alt="">
                                <div class="process-number">2</div>
                            </div>
                        </div>
                        <div class="process-info">
                            <!-- <div class="step-number">2</div> -->
                            <div class="details">
                                <div class="motto">Create</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Process step -->
                <div class="column is-4">
                    <div class="process-block">
                        <div class="process-icon is-icon-reveal">
                            <div class="icon-wrapper">
                                <img src="{{ asset('assets/img/about-earn.png') }}" width="80" height="80" alt="">
                                <div class="process-number">3</div>
                            </div>
                        </div>
                        <div class="process-info">
                            <!-- <div class="step-number">3</div> -->
                            <div class="details">
                                <div class="motto">Earn</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div id="contact" class="section is-medium section-feature-grey">
    <div class="container">
        <!-- Title -->
        <div class="section-title-wrapper has-text-centered">
            <!-- <div class="bg-number">5</div> -->
            <h2 class="section-title-landing">Drop us a Line</h2>
            <h3 class="section-subtitle-alt">
                Ask your doubts & start your store
            </h3>
        </div>

        <div class="columns">
            <div class="column is-6">
                <!-- Contact form -->
                <div class="contact-form">
                    <form method="POST" action="{{ route('send_mail') }}" class="basicform_with_reset">
                    @csrf
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="control">
                                    <label>Full name *</label>
                                    <input class="input is-medium" name="name" type="text" />
                                </div>
                            </div>
                            <div class="column is-12">
                                <div class="control">
                                    <label>Email *</label>
                                    <input class="input is-medium" name="email" type="email" />
                                </div>
                            </div>
                            <div class="column is-12">
                                <div class="control">
                                    <label>Message *</label>
                                    <textarea name="message" class="textarea" rows="4"></textarea>
                                </div>
                            </div>
                            @if(env('NOCAPTCHA_SITEKEY') != null)
                            <div class="column is-12">
                              <div class="control">
                                  {!! NoCaptcha::renderJs() !!}
                                  {!! NoCaptcha::display() !!}
                              </div>
                            </div>
                            @endif
                        </div>
                        <div class="submit-wrapper">
                            <button type="submit" class="button button-cta is-bold btn-align primary-btn raised basicbtn">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="column is-5 is-offset-1">
                <!-- Contact info tabs -->
                <div class="contact-toggler">
                    <ul class="tabbed-links">
                        <li class="is-active" data-contact="contact-email">Email</li>
                        <li data-contact="contact-location">Location</li>
                        {{-- <li data-contact="contact-phone">Phone</li> --}}
                    </ul>
                    <div class="contact-blocks">
                        <!-- Tab content -->
                        <div id="contact-email" class="contact-block animated preFadeInUp fadeInUp">
                            <div class="contact-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="contact-info">
                                <span>Contact us by email</span>
                                <span>{{ $info->email1 ?? "" }}</span>
                            </div>
                        </div>
                        <!-- Tab content -->
                        <div id="contact-location" class="contact-block animated preFadeInUp fadeInUp is-hidden">
                            <div class="contact-icon">
                                <i class="fa fa-map"></i>
                            </div>
                            <div class="contact-info">
                                <span>Visit us @ our Office</span>
                                <span>{{ $info->address  ?? "" }} ,<br>
                                    {{ $info->city ?? "" }},
                                    {{ $info->state ?? "" }},<br>
                                    {{ $info->country ?? "" }}-
                                    {{ $info->zip_code ?? "" }}
                                </span>
                            </div>
                        </div>
                        <!-- Tab content -->
                        {{-- <div id="contact-phone" class="contact-block animated preFadeInUp fadeInUp is-hidden">
                            <div class="contact-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="contact-info">
                                <span>Call our Support team</span>
                                <span>{{ $info->phone1 ?? "" }}</span>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <!-- Illustration -->
                <img src="assets/img/graphics/drawings/drawing-6-core.svg"
                    data-base-url="assets/img/graphics/drawings/drawing-6" data-extension=".svg" alt="" />
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush