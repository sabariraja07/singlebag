@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => trans('Store')])
@endsection
@section('content')
    <style>
        .store-name {
            color: green !important;
        }

        #shopNameHelp {
            font-size: 14px !important;
        }

        .error-txt {
            color: red;
            font-size: 14px !important;
        }

        .success-txt {
            color: green;
            font-size: 14px !important;
        }

        .login-brand img {
            max-width: 200px !important;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Create Store') }}</h4>

                </div>
                <div class="card-body">

                    <form class="basicform_with_redirect" action="{{ route('partner.shop.store') }}" method="post">
                        @csrf

                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Customer First Name') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" required="" name="first_name">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Customer Last Name') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" required="" name="last_name">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Customer Email') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" class="form-control" required="" name="email" id="email">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Customer Mobile Number') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" required="" name="mobile_number"
                                    id="mobile_number" maxlength="10" minlength="10">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Password') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" required="" name="password" minlength="8">
                            </div>
                        </div>


                        {{--
         <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Transaction Method') }}</label>
          <div class="col-sm-12 col-md-7">
            <select class="form-control" name="payment_method">
              @foreach (App\Models\PaymentMethod::where('type', 'payment_gateway')->get() as $row)
              <option value="{{ $row->slug }}">{{ $row->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        --}}

                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Transaction Id') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" required="" name="transaction_id"
                                    id="transaction_id">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Plan') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control" name="plan">
                                    @foreach (App\Models\Plan::where('status', 1)->get() as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Store Name') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input id="shop_name" type="text" class="form-control" name="shop_name" minlength="4"
                                    maxlength="15" placeholder="{{ __('Store Name') }}" />
                                <div id="shopNameHelp" class="form-text text-muted none">
                                    {{ __('Store Url') }} : <span
                                        class="store-name"></span>.{{ env('APP_PROTOCOLESS_URL') }}</div>
                                <div class="shop-helper none"></div>
                            </div>
                        </div>

                        {{--
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Domain Name Without Protocol') }} <br><small class="text-danger">example.com</small></label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="domain_name">
          </div>
        </div>

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Full Domain With Protocol') }}  <small class="text-danger">https://example.com</small></label>

         
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="full_domain">
          </div>
        </div>

        --}}




                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button class="btn btn-success basicbtn" type="submit"
                                    id="submit">{{ __('Save') }}</button>
                            </div>
                        </div>

                        <span>{{ __('Subdomain Example') }}: <b><span
                                    class="text-danger">sub1.</span>{{ env('APP_PROTOCOLESS_URL') }}</b></span><br>
                        @php
                            $ext = '\.';
                            $ext = str_replace('.', '', $ext);
                            $paths = explode($ext, base_path());
                            $count = count($paths);
                        @endphp
                        {{--
        <span>Your Root Path: <b><span class="text-danger">@foreach ($paths as $key => $row) 
          @php
          $key=$key+1;
          @endphp 
         @if ($key != $count){{ $row }}\@endif 
         @endforeach</span></span>
          <br>
        <span>{{ __('Note') }}: <b><span class="text-danger">{{ __('Before Add New Domain Please Create Domain Manually On Your Server The Domain Root Path Is Same With Your Project Directory') }}</span></b></span>
        --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
        "use strict";

        $('#shop_name').keyup(function() {
            var value = $(this).val();
            var value = value.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
            $(this).val(value);
            if (value.length > 0) {
                if (value.length >= 4) checkStoreNameAvailability(value);
                $('#shopNameHelp').show();
                $('span.store-name').text(value);
            } else {
                $('#shopNameHelp').hide();
            }
        });

        function checkStoreNameAvailability(name) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = {
                'store_name': name
            };
            $.ajax({
                type: 'POST',
                url: "{{ url('/store-name-availability') }}",
                data: JSON.stringify(data),
                dataType: 'json',
                contentType: 'application/json',
                processData: false,
                beforeSend: function() {
                    $('.shop-helper').removeClass('success-txt error-txt').hide();
                },
                success: function(response) {
                    if (response.message)
                        $('.shop-helper').text(response.message).addClass('success-txt').show();
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON.message) {
                        $('.shop-helper').text(xhr.responseJSON.message).addClass('error-txt').show();
                    }
                }
            });
        }

        $('#submit').on('click', function() {
            var mobile_number = $('#mobile_number').val();
            var number = /^[0-9]+$/;
            if (mobile_number != "") {
                if (mobile_number.match(number)) {} else {
                    $('#mobile_number').focus();
                    Sweet('error', '{{ __('Please Enter Valid Mobile Number Only') }}');
                    $('#mobile_number').val('');
                    return false;
                }
            }
            var email = $('#email').val();
            var email_validation = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z]+(?:\.[a-zA-Z]+)$/;
            if (email != '') {
                if (!email.match(email_validation)) {
                    $('#email').focus();
                    Sweet('error', '{{ __('Please Enter Valid Email') }}');
                    $('#email').val('');
                    return false;
                }
            }

        });

        $("#transaction_id").keypress(function(event) {
            if (event.which == 32) {
                Sweet('error', '{{ __('Spaces Not Allowed') }}');
                return false;
            }
        });
    </script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
