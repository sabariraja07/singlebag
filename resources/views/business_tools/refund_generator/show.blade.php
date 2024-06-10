<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Return/Refund Policy Generator</title>
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
                    <h1 class="title">{{ __('Return/Refund Policy Generator') }}</h1>
                    <ul>
                        <li>
                            <a href="/">{{ __('Home') }}</a>
                        </li>
                        <li class="active">{{ __('Return/Refund Policy Generator') }}</li>
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
            </div>
            <div class="content-body">
                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Return/Refund Policy') }}</h4>
                                    
                                </div>
                                <hr>
                                <p class="seo_set">{{ __('To add this document to your website, create a new page titled “Return/Refund Policy” and paste the text shown below. Once you’ve created your Return/Refund Policy page, add a link to it in your website’s footer.') }}</p>
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary me-1 basicbtn" onclick="copyToClipboard()">{{ __('Copy to Clipboard') }}</button>
                                                <br><br>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <textarea class="form-control" rows="50" id="ctc">
Return/Refund Policy for {{ $company_info->store_name}}

Effective Date:  {{ date('d-m-Y'); }}

Please read our Return and Refund Policy carefully.

RETURNS
----
1.1. Eligibility: To be eligible for a return, the item(s) must be unused, undamaged, and in the same condition as when you received them. They should also be in the original packaging, with all tags and labels intact. You'll also need the receipt or proof of purchase.

1.2. Return Timeframe: You have [number of days, e.g., 30 days] from the date of delivery to initiate a return request.

1.3. Return Process:

a. Contact our Customer Support team at Mob: {{ $company_info->mobile_number ?? '' }}, Email: {{ $company_info->email ?? '' }} to initiate your return request.
b. Our team will guide you through the return process and provide you with a return authorization number (RA number) and the return shipping address [address].
If your return is accepted, we’ll send you a return shipping label, as well as instructions on how and where to send your package. Items sent back to us without first requesting a return will not be accepted. Please note that if your country of residence is not India, shipping your goods may take longer than expected.
c. Please securely package the item(s) to prevent any damage during transit.
d. Include the RA number on the return package.
e. Ship the item(s) back to us using a reliable and trackable shipping method.

1.4. Return Shipping: The customer is responsible for the return shipping costs unless the return is due to a defect or an error on our part.

1.5. Return Inspection and Refund: Once we receive the returned item(s), our team will inspect them to ensure they meet the return eligibility criteria mentioned in section 1.1. If approved, we will initiate the refund process.
- Refunds will be issued to the original payment method used for the purchase.
- The refund process may take [number of days, e.g., 7-10 days] to complete, depending on your payment provider.
- Shipping charges, if applicable, are non-refundable.
Exchanges

2.1. We currently do not offer direct exchanges. If you wish to exchange an item, you can follow the return process mentioned in Section 1 and place a new order for the desired item.
Damaged or Defective Items

3.1. In the event that you receive a damaged or defective item, please contact our Customer Support team immediately, providing details of the issue along with supporting documentation or images.

3.2. Once the damage or defect is verified, we will arrange for a replacement or offer a refund, as per your preference.
Non-Returnable Items

4.1. Certain items are non-returnable, including but not limited to:
- [Specify non-returnable items, such as perishable goods, personalized/customized products, intimate apparel, etc.]

4.2. Non-returnable items will be clearly indicated on the product page.

Contact Information
{{ $company_info->store_name}}

Address: 
{{ $company_info->address ?? ''}}, 
{{ $company_info->city ?? '' }}, {{ $company_info->state ?? '' }}, 
{{ $company_info->country ?? '' }}, {{ $company_info->zip_code ?? ''}}

Customer Support: 
Mob No: {{ $company_info->mobile_number ?? '' }}
Email: {{ $company_info->email ?? '' }}

Please note that this Return and Refund Policy applies to online purchases made through our {{ $company_info->website ?? '' }}. If you have made a purchase through any other channel, such as a physical store or third-party platform, separate policies may apply.
We reserve the right to update or modify this Return and Refund Policy at any time. Any changes will be effective immediately upon posting on our website. Please review this policy periodically.
If you have any further questions or need assistance, please do not hesitate to reach out to our Customer Support team. We are here to help you.

Last updated: {{ date('d-m-Y'); }}

                                                </textarea>
                                            </div>
                                        </div>
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
    <script type="text/javascript">
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
        function copyToClipboard(element) {
            var $temp = $("<textarea>");
            $("body").append($temp);
            $temp.val($('#ctc').text()).select();
            document.execCommand("copy");
            $temp.remove();
            Sweet('success','Text Copied');
        }
    </script>
</body>

</html>
