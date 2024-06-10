@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection', ['title' => trans('Buy License')])
@endsection
@section('content')
<style>
    .alert_box_red {
        background-color: #fc544b;
        color: #fff;
        border-radius: 12px;
    }
</style>
@php
$stripe = false;
@endphp
<div class="col-sm-15">
    @if ($errors->any())
    <div class="alert_box_red">
        <ul>
            <p>{{ $errors->first() }}</p>
        </ul>
    </div>
    @endif
</div>

<div class="container py-5">

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="title">{{ __('License Details') }}</h3>
                </div>
                <div class="card-body">

                    <form role="form" method="post" action="{{ route('partner.license.make_charge') }}">
                        <div class="form-group">
                            <label>{{ __('Plan') }}</label>
                            <select class="form-control" id="plan_id" name="plan_id" onchange="getLicencePrice()">
                                <option value="" selected disabled>{{ __('Select Plan') }}</option>
                                @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} ({{ amount_format($plan->price) }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group ">
                            <label>{{ __('Quantity') }}</label>
                            <input type="number" class="form-control" id="quantity" min="1" required="" value="{{ old('quantity') ?? 1 }}" name="quantity">
                        </div>
                        <div class="discount-info" style="display: none;">
                            <div class="form-group ">
                                <label>{{ __('Price') }} : </label>
                                <span id="planAmount"></span>
                            </div>
                            <div class="form-group ">
                                <label>{{ __('Discount') }} : </label>
                                <span id="discountAmount"></span>
                            </div>
                            <div class="form-group ">
                                <label>{{ __('Tax') }} : </label>
                                <span id="taxAmount"></span>
                            </div>
                            <div class="form-group ">
                                <label>{{ __('Payable Amount') }} : </label>
                                <span id="payableAmount"></span>
                            </div>
                            @foreach ($gateways as $key => $row)
                            <div id="{{ $row->slug }}" class="tab-pane fade @if ($key == 0) show active @endif pt-3">
                                @if ($row->slug != 'stripe')
                                @csrf
                                <input type="hidden" name="mode" value="{{ $row->id }}">
                                <input type="hidden" name="payment_method" value="{{ $row->slug }}">
                                <div class="form-group">
                                    <label>
                                        <h6>{{ __('Name') }}</h6>
                                    </label>
                                    <input type="text" name="name" readonly="" value="{{ Auth::user()->fullname }}" class="form-control ">
                                </div>
                                <div class="form-group">
                                    <label>
                                        <h6>{{ __('Email') }}</h6>
                                    </label>
                                    <input type="text" name="email" readonly="" value="{{ Auth::user()->email }}" class="form-control ">
                                </div>
                                <div class="form-group">
                                    <label for="username">
                                        <h6>{{ __('Phone Number') }}</h6>
                                    </label>
                                    <input type="number" name="phone" placeholder="Enter Your Phone Number" required class="form-control ">
                                </div>
                                <button type="submit" class="subscribe btn btn-success btn-block shadow-sm">
                                    {{ __('Make Payment') }} </button>
                                @endif
                            </div>
                            @endforeach

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
@if ($stripe == true)
<script src="{{ asset('assets/js/stripe.js') }}"></script>
@endif

<script>
    $('#quantity').change(function() {
        getLicencePrice();
    });

    function getLicencePrice() {
        var plan_id = $('#plan_id').val();
        var quantity = $('#quantity').val();
        if (!plan_id || !quantity) {
            return;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = {
            'plan_id': plan_id,
            'quantity': quantity
        };
        $.ajax({
            type: 'POST',
            url: "{{ route('partner.license.getprice') }}",
            data: JSON.stringify(data),
            dataType: 'json',
            contentType: 'application/json',
            processData: false,
            beforeSend: function() {
                $('.discount-info').hide();
            },
            success: function(response) {
                console.log(response);
                var icon = response['currency']['symbol'] ?? "";
                $('#planAmount').html(icon + " " + response['amount']);
                $('#discountAmount').html(icon + " " + response['discount']);
                $('#taxAmount').html(icon + " " + response['tax']);
                $('#payableAmount').html(icon + " " + response['total']);
                $('.discount-info').show();
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON.message) {}
            }
        });
    }
</script>
@endpush