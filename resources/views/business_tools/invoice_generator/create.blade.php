<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Invoice Generator</title>
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
    <script type="text/javascript">!function (o, c) {
            var n = c.documentElement, t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
        }(window, document);
    </script>
    <link href="{{ asset('assets/invoice_generator_css/css-chunk-vendors.1c7f9e6d.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/invoice_generator_css/css-app.964112fb.css') }}" rel="stylesheet" type="text/css">
    <style>
        * {
            -webkit-font-smoothing: antialiased;
        }
        .wrap_class {
            flex-wrap: nowrap !important; 
        }
        .hide_ps, .free_generator__payment_terms, .free_generator__client_notes, .phone_number, .trash[data-v-0ad295d2]{
            display: none;
        }
        input[type=email] {
            display: none;
        } 
        .free_generator__discount-whole-invoice{
            color: blue;
            display: none;
        }
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
        @media print {
            .free-generator__row, .trash-button, .free_generator__currency, .hide_line_btn, .main_menu, .header-navbar, .content-header, .card-header, .header-navbar-shadow, .footer, footer, .scroll-top, .trash[data-v-0ad295d2], .HeaderLogo, .section_breadcrumb {
                visibility: hidden;
            }

            .menu-accordion,.HeaderLogo, .section_breadcrumb {
                display: none !important;
            }
            .card-body, .card, html, .content.app-content, .navbar-floating .header-navbar-shadow {
                padding: 0px !important;
                box-shadow: none;
                margin-top: 0px; 
            }
            .free_generator__card-body {
                padding: 10px 42px;
            }
            hr  {
                margin-bottom: 5px;
            }
            footer {
                margin-top: 0px !important;
            }
            .card{
                border: none;
            }
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
    {{-- <div class="section section_breadcrumb">
        <div class="breadcrumb-area bg-light">
            <div class="container-fluid">
                <div class="breadcrumb-content text-center">
                    <h1 class="title">{{ __('Invoice Generator') }}</h1>
                    <ul>
                        <li>
                            <a href="/">{{ __('Home') }}</a>
                        </li>
                        <li class="active">{{ __('Invoice Generator') }}</li>
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
                                    <h4 class="card-title">{{ __('Create a Professional Invoice in Minutes') }}</h4>
                                    
                                </div>
                                {{-- <p class="seo_set">{{ __('Enter your company information.') }}</p> --}}
                                <hr>
                                <div class="card-body">
                                    <div class="invoice-section" id="invoice">
                                        <div id="app"></div>
                                        
                                    </div>
                                    <footer class="text-center mt-4">
                                      
                                      <div class="btn-group"> <button class="btn btn-primary" onclick="printDiv()">Print</button></div>
                                      <div class="btn-group"> <button class="btn btn-primary" onclick="downloadDiv()">Download</button></div>
                                      <div class="loader"></div>
                                      </footer>
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
<script src="{{ asset('assets/invoice_generator_js/js-app.f630dc13.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/invoice_generator_js/js-chunk-vendors.030c5c1a.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/invoice_generator_js/js-jquery-3.4.1.min.220afd743d.js') }}" type="text/javascript" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="{{ asset('assets/invoice_generator_js/js-webflow.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src=
"https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js">
   </script>

<script>
    $('#light_logo').bind('change', function() {
        console.log('test');
        var file_size = $('#light_logo')[0].files[0].size;
        if (file_size >= 1048576) {
            alert(" Recommended file size is less than 1 mb");
            location.reload();
        } 
    });
    $( document ).ready(function() {
        $('.free_generator__currency, .free-generator__row, .trash-button, .free_generator__currency, .hide_line_btn, .main_menu, .header-navbar, .content-header, .card-header, .header-navbar-shadow, .footer, footer, .scroll-top, .trash[data-v-0ad295d2]').attr('data-html2canvas-ignore', 'true');
    });
    function printDiv() {
     window.print();
    }
    function downloadDiv() {
            const { jsPDF } = window.jspdf;
 
            var doc = new jsPDF('p', 'mm', [950, 1400]);
            var pdfjs = document.querySelector('#invoice');
            pdfjs.onProgress = function(obj) { 

             }

 
            doc.html(pdfjs, {
                callback: function(doc) {
                    doc.save("invoice.pdf");
                },
                x: 0,
                y: 0,
            });               
        }   
</script>
</body>

</html>

