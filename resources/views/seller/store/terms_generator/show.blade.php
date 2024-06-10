@extends('layouts.seller')
@section('title', 'Terms & Conditions Generator')
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
                                    <h4 class="card-title">{{ __('Terms & Conditions') }}</h4>
                                    
                                </div>
                                <hr>
                                <p class="seo_set">{{ __('To add this document to your website, create a new page titled “Terms & Conditions” and paste the text shown below. Once you’ve created your Terms & Conditions page, add a link to it in your website’s footer.') }}</p>
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary me-1 basicbtn" onclick="copyToClipboard()">{{ __('Copy to Clipboard') }}</button>
                                                <br><br>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <textarea class="form-control" rows="70" id="ctc">
Terms & Conditions for {{ $company_info->store_name}}

Welcome to {{ $company_info->store_name}}. By using our website, you agree to these terms and conditions. Please read them carefully..

1. Use of our website

You may use our website for lawful purposes only. You may not use our website:

* In any way that violates any applicable federal, state, local or international law or regulation
* To transmit, or procure the sending of, any advertising or promotional material, including any "junk mail", "chain letter" or "spam" or any other similar solicitation
* To impersonate or attempt to impersonate {{ $company_info->store_name}}, our employees, another user, or any other person or entity
* To engage in any other conduct that restricts or inhibits anyone's use or enjoyment of the website, or which, as determined by us, may harm {{ $company_info->store_name}} or users of the website or expose them to liability.

2. Intellectual property

The content on our website, including text, graphics, images, logos, and software, is the property of {{ $company_info->store_name}} or its suppliers and is protected by copyright and other intellectual property laws. You may not copy, distribute, modify, publish, transmit, or create derivative works of any portion of our website without our prior written consent.

3. Product information

We make every effort to ensure that the products on our website are accurately described and depicted. However, we cannot guarantee that the colors, sizes, or other features of the products will be exactly as they appear on our website. We reserve the right to change product information at any time without prior notice.

4. Payment and shipping

We accept payment via the methods specified on our website. We will ship your order to the address you provide at the time of purchase. Please see our shipping policy for more information.

5. Returns and refunds

Please see our returns and refunds policy for information on returning products and obtaining refunds.

6. Limitation of liability

{{ $company_info->store_name}} is not liable for any direct, indirect, incidental, consequential, punitive, or any other damages arising out of or in connection with your use of our website, products, or services.

7. Indemnification

You agree to indemnify and hold {{ $company_info->store_name}} and its affiliates, officers, agents, employees, and partners harmless from any claim or demand, including reasonable attorneys' fees, made by any third-party due to or arising out of your breach of these terms and conditions or your violation of any law or the rights of a third-party.

8. Termination

We reserve the right to terminate your use of our website, products, or services at any time without notice.

9. Changes to these terms and conditions

We may update these terms and conditions from time to time by posting a new version on our website. You should check this page periodically to ensure you are aware of any changes.

10. Contact us

If you have any questions or concerns about these terms and conditions, please contact us at:

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
