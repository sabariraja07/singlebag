@extends('layouts.seller')
@section('title', 'Privacy Policy Generator')
@section('page-style')
<style>
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
@endsection
@section('content')
@php
	$url=domain_info('full_domain');
@endphp
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
                                    <h4 class="card-title">{{ __('Privacy Policy') }}</h4>
                                    
                                </div>
                                <hr>
                                <p class="seo_set">{{ __('To add this document to your website, create a new page titled “Privacy Policy” and paste the text shown below. Once you’ve created your Privacy Policy page, add a link to it in your website’s footer.') }}</p>
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary me-1 basicbtn" onclick="copyToClipboard()">{{ __('Copy to Clipboard') }}</button>
                                                <br><br>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <textarea class="form-control" rows="70" id="ctc">
Privacy Policy for {{ $company_info->store_name}}

At {{ $company_info->store_name}}, we are committed to protecting your privacy. This privacy policy outlines the information we collect, how we use it, and how we keep it secure.

1. Information we collect

We collect various types of information from our customers, including:

* Personal information such as name, email address, shipping and billing address, and phone number
* Payment information such as credit card details and billing address
* Order information such as the products you purchase, the date and time of your order, and shipping information
* Device information such as IP address, browser type, and operating system

2. How we use your information

We use your information to:

* Process and fulfill your orders
* Communicate with you about your orders, including order confirmations and shipping updates
* Provide customer service and support
* Improve our website and services
* Send you promotional offers and updates (only if you opt-in)

3. How we share your information

We do not sell or rent your personal information to third parties. However, we do share your information with:

* Service providers who assist us in processing and fulfilling your orders, such as shipping carriers and payment processors
* Law enforcement agencies, government bodies, and other organizations as required by law

4. Your choices

You can choose not to provide certain information, although this may prevent us from processing your order or providing certain services. You can also opt-out of receiving promotional offers and updates at any time.

5. Security

* We take the security of your information seriously and have implemented appropriate measures to protect it. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee the absolute security of your information.

6. Cookies and tracking

* We use cookies and other tracking technologies to enhance your experience on our website and to analyze how our website is used. You can disable cookies in your browser settings, although this may affect your ability to use certain features of our website.

7. Children

* Our website is not intended for use by children under the age of 18, and we do not knowingly collect personal information from children under the age of 18.

8. Changes to this policy

* We may update this privacy policy from time to time by posting a new version on our website. You should check this page periodically to ensure you are aware of any changes.

9. Contact us

* If you have any questions or concerns about this privacy policy, please contact us at:

{{ $company_info->store_name  ?? ''}}
{{ $company_info->address ?? ''}}, 
{{ $company_info->city ?? '' }}, {{ $company_info->state ?? '' }}, {{ $company_info->country ?? '' }},
{{ $company_info->zip_code ?? ''}}
Mob No: {{ $company_info->mobile_number ?? '' }}
{{ $company_info->email ?? '' }}

{{ $company_info->website ?? '' }}
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
@endsection
@section('page-script')
    <script>
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
@endsection
