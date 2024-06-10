@extends('layouts.app')
@section('title', 'Order Details')
@section('page-style')
<style>
    .float-right {
        float: right !important;
    }

    #shipping-details-table tr td:first-child {
        font-weight: 900;
    }

    #shipping-details-table tr td {
        height: 30px;
    }

    hr {
        width: 93% !important;
        margin-left: auto !important;
        margin-right: auto !important;
        margin-top: -5px !important;
    }


    .vertical-layout.vertical-menu-modern.menu-expanded .footer {
        margin-left: -27px !important;
    }
</style>
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <h5>{{ __('Order No : ' . $info->order_no) }}</h5>
    </div>
    <div class="content-body">
        <section class="invoice-preview-wrapper">
            <div class="row invoice-preview">
                <!-- Invoice -->
                <div class="col-xl-8 col-md-8 col-12">
                    <div class="card invoice-preview-card">


                        {{-- <hr class="invoice-spacing" /> --}}

                        <!-- Address and Contact starts -->
                        <div class="card-body invoice-padding">
                            <div class="row invoice-spacing">
                                <div>
                                    <h6 class="mb-2" style="font-weight: bolder;">Shipping Details</h6>
                                    <table>
                                        <tbody style="border-collapse: unset;">
                                            <tr>
                                                <td class="pe-1">Customer Name:</td>
                                                <td><span class="fw-bold"> {{ $order_content->name ?? '' }}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Customer Email:</td>
                                                <td>{{ $order_content->email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Customer Phone:</td>
                                                <td>{{ $order_content->phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Location:</td>
                                                <td>{{ $info->shipping_info->city->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Zip Code:</td>
                                                <td>{{ $order_content->zip_code ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Address:</td>
                                                <td>{{ $order_content->address ?? '' }}</td>
                                            </tr>
                                            {{-- @php
                                                    $shipping_method = $info->shipping_info ? $info->shipping_info->method->title : '';
                                                    $estimate_date = $info->shipping_info ? $info->shipping_info->method->estimated_delivery : '';
                                                @endphp
                                                <tr>
                                                    <td class="pe-1">{{ __('Shipping Method') }}:</td>
                                            <td>{{ $shipping_method .'('.$estimate_date. ')'  }}</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- Address and Contact ends -->





                        <hr>
                        <div id="order">
                            {{-- @if ($info->status == 'pending')
                            <div class="card card-warning">
                            @elseif($info->status == 'processing')
                                <div class="card card-primary">
                                @elseif($info->status == 'ready-for-pickup')
                                    <div class="card card-info">
                                    @elseif($info->status == 'picked_up')
                                        <div class="card card-pickup">
                                        @elseif($info->status == 'delivered')
                                            <div class="card card-delivered">
                                            @elseif($info->status == 'completed')
                                                <div class="card card-success">
                                                @elseif($info->status == 'archived')
                                                    <div class="card card-danger">
                                                    @elseif($info->status == 'canceled')
                                                        <div class="card card-danger">
                                                        @else
                                                            <div class="card card-primary">
                        @endif --}}

                            <div class="card-body">
                                <ul class="list-group list-group-lg list-group-flush list">
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                <strong>{{ __('Product') }}</strong>
                                            </div>
                                            <div class="col-3 text-right">
                                                <strong>{{ __('Amount') }}</strong>
                                            </div>
                                            <div class="col-3 text-right">
                                                <strong>{{ __('Total') }}</strong>
                                            </div>
                                        </div>
                                    </li>
                                    @foreach ($info->order_item as $row)
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                {{ $row->term->title ?? '' }}
                                                @php
                                                $attributes = $row->info;
                                                @endphp
                                                <br>
                                                @foreach ($attributes->options ?? [] as $option)
                                                <span>{{ __('Options') }} :</span>
                                                <small>{{ $option->name ?? '' }}</small><br />
                                                @endforeach
                                            </div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($row->amount) }} × {{ $row->qty }}
                                            </div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($row->amount * $row->qty) }}
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach

                                    <li class="list-group-item">
                                        <div class="row align-items-center">


                                            <div class="col-6">
                                                {{ $info->shipping_info->shipping_method->name ?? '' }}
                                            </div>
                                            <div class="col-3 text-right">
                                                {{ __('Shipping Fee') }}
                                            </div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($info->shipping) }}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-right">{{ __('Tax') }}</div>
                                            <div class="col-3 text-right"> {{ amount_format($info->tax) }} </div>
                                        </div>
                                    </li>


                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-right">{{ __('Discount') }}</div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($order_content->coupon_discount ?? 0) }}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-right">{{ __('Subtotal') }}</div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($order_content->sub_total ?? 0) }}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-right">{{ __('Total') }}</div>
                                            <div class="col-3 text-right">{{ amount_format($info->total) }}</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-footer">

                                <div class="text-right">

                                    <div class="btn-group">
                                        <select class="form-control" name="payment_status" style="width: 202px;" disabled>
                                            <option disabled=""><b>{{ __('Select Payment Status') }}</b>
                                            </option>
                                            <option value="1" @if ($info->payment_status == '1') selected="" @endif>
                                                {{ __('Payment Complete') }}
                                            </option>
                                            <option value="2" @if ($info->payment_status == '2') selected="" @endif>
                                                {{ __('Payment Pending') }}
                                            </option>
                                            <option value="0" @if ($info->payment_status == '0') selected="" @endif>
                                                {{ __('Payment Cancel') }}
                                            </option>
                                            <option value="3" @if ($info->payment_status == '3') selected="" @endif>
                                                {{ __('Payment Incomplete') }}
                                            </option>
                                        </select>
                                        &nbsp&nbsp
                                        <select class="form-control" name="status" style="width: 202px;" disabled>
                                            <option disabled=""><b>{{ __('Select Order Status') }}</b>
                                            </option>
                                            <option value="pending" @if ($info->status == 'pending') selected="" @endif>
                                                {{ __('Awaiting processing') }}
                                            </option>
                                            <option value="processing" @if ($info->status == 'processing') selected="" @endif>
                                                {{ __('Processing') }}
                                            </option>
                                            <option value="ready-for-pickup" @if ($info->status == 'ready-for-pickup') selected="" @endif>
                                                {{ __('Ready for pickup') }}
                                            </option>
                                            <option value="picked_up" @if ($info->status == 'picked_up') selected="" @endif>
                                                {{ __('Picked_up') }}
                                            </option>
                                            <option value="delivered" @if ($info->status == 'delivered') selected="" @endif>
                                                {{ __('Delivered') }}
                                            </option>
                                            <option value="completed" @if ($info->status == 'completed') selected="" @endif>
                                                {{ __('Completed') }}
                                            </option>
                                            <option value="archived" @if ($info->status == 'archived') selected="" @endif>
                                                {{ __('Archived') }}
                                            </option>
                                            <option value="canceled" @if ($info->status == 'canceled') selected="" @endif>
                                                {{ __('Cancelled') }}
                                            </option>

                                        </select>

                                    </div>



                                </div>

                            </div>

                            @if ($info->status == 'ready-for-pickup')
                            @if ($info->agent_id == null)
                            <div class="card-agent">
                                <div class="card-header">
                                    <h4>{{ __('Assign Delivery Agent') }}</h4>

                                </div>
                                <div class="card-body">


                                    <div style="margin-bottom: -5px;">

                                        <select name="agents" id="agents" class="agents" style="width: 360px;">

                                        </select><br>
                                        <button type="submit" class="btn btn-primary float-right" style="margin-top: -58px;">{{ __('Submit') }}</button>
                                    </div>


                                </div>

                            </div>
                            @endif
                            @endif
                            @if ($info->agent_id != null)
                            <div class="card-agent form-padding">
                                <div class="card-header">
                                    <h4>{{ __('Delivery Agent') }}</h4>

                                </div>

                                <div class="card-body">

                                    <img src="{{ asset($info->agent_avathar_details->avathar ?? '') }}" height="80" width="80" style="border-radius: 50%" class="float-left" />
                                    <p class="float-left" style="font-weight: bold;font-size: 25px;padding: 14px;">
                                        {{ $info->agent_details->first_name ?? '' }}
                                    </p>
                                    <p class="agent_order"> {{ $agent->orders ?? '' }} Current Orders</p>

                                </div>
                                @if ($info->status != 'delivered' && $info->status != 'completed')
                                <div class="card-footer">
                                    <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">{{ __('Change') }}</a>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>


                        <!-- Invoice Note starts -->
                        <div class="card-body invoice-padding pt-0">
                            <div class="row">
                                <div class="col-12">
                                    {{-- <span class="fw-bold">Note:</span>
                                        <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
                                            projects. Thank You!</span> --}}

                                </div>
                            </div>
                        </div>
                        <!-- Invoice Note ends -->
                    </div>
                </div>



                <!-- /Invoice -->

                <!-- Invoice Actions -->
                {{-- <div class="col-xl-4 col-md-5 col-12 invoice-actions mt-md-0 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary w-100 mb-75" data-bs-toggle="modal" data-bs-target="#send-invoice-sidebar">
                                    Status
                                </button>
                                <button class="btn btn-outline-secondary w-100 btn-download-invoice mb-75">Download</button>
                                <a class="btn btn-outline-secondary w-100 mb-75" href="./app-invoice-print.html" target="_blank"> Print </a>
                                <a class="btn btn-outline-secondary w-100 mb-75" href="./app-invoice-edit.html"> Edit </a>
                                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#add-payment-sidebar">
                                    Add Payment
                                </button>
                            </div>
                        </div>
                    </div> --}}
                <!-- /Invoice Actions -->
                <div class="col-xl-4 col-md-5 col-12 invoice-actions mt-md-0 mt-2">
                    <div class="card-grouping">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Status') }}</h4>

                            </div>
                            <div class="card-body">

                                <p>{{ __('Payment Status') }}
                                    @if ($info->payment_status == 2)
                                    <span class="float-right badge rounded-pill bg-warning">{{ __('Pending') }}</span>
                                    @elseif($info->payment_status == 1)
                                    <span class="float-right badge rounded-pill bg-success">{{ __('Paid') }}</span>
                                    @elseif($info->payment_status == 0)
                                    <span class="float-right badge rounded-pill bg-danger">{{ __('Cancel') }}</span>
                                    @elseif($info->payment_status == 3)
                                    <span class="float-right badge rounded-pill bg-danger">{{ __('Incomplete') }}</span>
                                    @endif
                                </p>


                                <p>{{ __('Order Status') }} @if ($info->status == 'pending')
                                    <span class="badge rounded-pill bg-warning float-right">{{ __('Awaiting processing') }}</span>
                                    @elseif($info->status == 'processing')
                                    <span class="badge rounded-pill bg-primary float-right">{{ __('Processing') }}</span>
                                    @elseif($info->status == 'ready-for-pickup')
                                    <span class="badge rounded-pill bg-info float-right">{{ __('Ready for pickup') }}</span>
                                    @elseif($info->status == 'completed')
                                    <span class="badge rounded-pill bg-success float-right">{{ __('Completed') }}</span>
                                    @elseif($info->status == 'delivered')
                                    <span class="badge rounded-pill bg-info float-right">{{ __('Delivered') }}</span>
                                    @elseif($info->status == 'picked_up')
                                    <span class="badge rounded-pill bg-info float-right">{{ __('Picked_Up') }}</span>
                                    @elseif($info->status == 'archived')
                                    <span class="badge rounded-pill bg-danger float-right">{{ __('Archived') }}</span>
                                    @elseif($info->status == 'canceled')
                                    <span class="badge rounded-pill bg-danger float-right">{{ __('Cancelled') }}</span>
                                    @else
                                    <span class="badge rounded-pill bg-primary float-right">{{ $info->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Payment Mode') }}</h4>

                            </div>
                            <div class="card-body">
                                @if ($info->payment_method != null)
                                <p>{{ __('Transaction Method') }} <span class="badge rounded-pill bg-success  float-right">{{ $info->PaymentMethod->name ?? '' }}
                                    </span></p>
                                <p>{{ __('Transaction Id') }} <span class="float-right">{{ $info->transaction_id ?? '' }}</span></p>
                                @else
                                <p>{{ __('Incomplete Payment') }}</p>
                                @endif
                            </div>
                        </div>
                        @if (isset($order_content->comment))
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-header-title">{{ __('Note') }}</h4>

                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $order_content->comment ?? 0 }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-header-title">{{ __('Customer') }}</h4>
                            </div>
                            <div class="card-body">
                                @if ($info->customer != null)
                                {{ $info->customer->fullname }}
                                (#{{ $info->customer->id }})</a>
                                @else
                                {{ __('Guest Customer') }}
                                @endif
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </section>




        <!-- Modal -->
        <div class="modal fade model-center" id="exampleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #6777ef;" id="exampleModalLabel">
                            {{ __('Change Delivery Agent') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">

                            <select name="agents" id="agents-1" class="form-control agents-1">

                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>


        @endsection
        @section('page-script')
        <script src="{{ asset('assets/js/form.js') }}"></script>
        @endsection