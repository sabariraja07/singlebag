@extends('layouts.seller')
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

        .select2-container--classic .select2-selection--single .select2-selection__arrow b,
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23d8d6de' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: 18px 14px, 18px 14px;
            background-repeat: no-repeat;
            height: 1rem;
            padding-right: 1.5rem;
            margin-left: 0;
            margin-top: 0;
            left: -8px;
            border-style: none;
        }

        /* Desktop View */
    @media screen and (min-width: 992px) {
          .order_set {
            position: relative;
            display: inline-flex;
            vertical-align: middle;
            }
           .change_agent{
            padding-left: 166px;
           }

        }
         /* Mobile View */
        @media screen and (max-width: 900px) {
            .row{
			--bs-gutter-x: 2px !important;
			}
            .payment_set{
            margin-left: 32px !important;
            }
            .order_set {
            padding: 10px !important;
            margin-left: 23px !important;
            }
          
        }
        
			
    </style>

@endsection
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('ORDER NO') }}:{{ $info->order_no }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('/seller/orders/all') }}">{{ __('All Orders') }}</a>
                            </li>
                            {{-- <li class="breadcrumb-item active">{{ __('Order No : ' . $info->order_no) }} --}}
                                <li class="breadcrumb-item active">{{ __('ORDER NO') }}:{{ $info->order_no }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
        </div>
    </div>
    <div class="content-body">
        <section class="invoice-preview-wrapper">
            <div class="row invoice-preview">
                <div class="col-xl-9 col-md-8 col-12">
                    <div class="card invoice-preview-card">

                        {{-- <hr class="invoice-spacing" /> --}}
                        <div class="card-body invoice-padding">
                            <div class="row invoice-spacing">
                                <div>
                                    <h6 class="mb-2" style="font-weight: bolder;">{{ __('Shipping Details') }}</h6>
                                    <table>
                                        <tbody style="border-collapse: unset;">
                                            <tr>
                                                <td class="pe-1">{{ __('Customer Name') }}:</td>
                                                <td><span class="fw-bold"> {{ $order_content->name ?? '' }}</span></td>
                                            </tr>
                                            @if (current_shop_type() != 'reseller')
                                                <tr>
                                                    <td class="pe-1">{{ __('Customer Email') }}:</td>
                                                    <td>{{ $order_content->email ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">{{ __('Customer Phone') }}:</td>
                                                    <td>{{ $order_content->phone ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">{{ __('Location') }}:</td>
                                                    <td>{{ $info->shipping_info->city->name ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">{{ __('Zip Code') }}:</td>
                                                    <td>{{ $order_content->zip_code ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">{{ __('Address') }}:</td>
                                                    <td>{{ $order_content->address ?? '' }}</td>
                                                </tr>

                                                @php
                                                    if (!empty($info->shipping_info)) {
                                                        $shipping_method = $info->shipping_info->method->title ?? '';
                                                        $estimate_date = $info->shipping_info->method->estimated_delivery ?? '';
                                                    } else {
                                                        $shipping_method = '';
                                                        $estimate_date = '';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="pe-1">{{ __('Shipping Method') }}:</td>
                                                    <td>{{ $shipping_method . '(' . $estimate_date . ')' }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div id="order">
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
                                                @if (current_shop_type() != 'reseller')
                                                    <a
                                                        href="{{ url('/seller/products/?highlight=' . $row->product->id) }}">{{ $row->product->title ?? '' }}<br>
                                                    </a>
                                                @else
                                                    {{ $row->product->title ?? '' }}
                                                @endif
                                                @php
                                                    $attributes = json_decode($row->info);
                                                @endphp
                                                <br>
                                                @foreach ($attributes->options ?? [] as $option)
                                                    <span>{{ __('Options') }} :</span>
                                                    <small>{{ $option->name ?? '' }}</small><br />
                                                @endforeach

                                            </div>
                                            <div class="col-3 text-right">
                                                {{ $row->amount }} × {{ $row->qty }}
                                            </div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($row->amount * $row->qty) }}
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                    @if(isset($info->shipping_info))
                                    <li class="list-group-item border-0 mt-3">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-end">{{ __('Shipping Fee') }} :</div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($info->shipping) }}
                                            </div>
                                            {{-- <p>{{ $info->shipping_info->shipping_method->name ?? '' }}</p> --}}
                                        </div>
                                    </li>
                                    @endif
                                    <li class="list-group-item border-0">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-end">{{ __('Tax') }} :</div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($info->tax) }}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-end">{{ __('Discount') }}:</div>
                                            <div class="col-3 text-right text-danger">
                                                {{ amount_format($order_content->coupon_discount ?? 0) }}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0">
                                        <div class="row align-items-center">
                                            <div class="col-9 text-end">{{ __('Subtotal') }}:</div>
                                            <div class="col-3 text-right">
                                                {{ amount_format($order_content->sub_total ?? 0) }}
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 mt-1">
                                        <div class="row align-items-end">
                                            <div class="col-9 text-end">
                                                <strong>{{ __('Total') }}:</strong>
                                            </div>
                                            <div class="col-3 text-right">
                                                <strong>{{ amount_format($info->total) }}</strong>
                                            </div>
                                        </div>
                                    </li>
                                    @if (isset($order_content->comment))
                                        <li class="list-group-item border-0 mt-3 mb-1">
                                            <div class="row align-items-end">
                                                <div class="col-12">
                                                    <strong>{{ __('Note') }}</strong>
                                                    <p class="mb-0">
                                                        {{ $order_content->comment }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            @if (current_shop_type() != 'reseller')
                                <div class="card-footer">
                                    <div class="text-right">
                                        <form method="POST" action="{{ route('seller.order.update', $info->id) }}"
                                            accept-charset="UTF-8" class="d-inline basicform">
                                            @csrf
                                            @method('PUT')
                                            <input type="checkbox" id="vehicle3" name="mail_notify" value="1"
                                                checked>
                                            <label for="vehicle3"> {{ __('Notify To Customer') }}</label>
                                            <div class="btn-group">
                                                @if (current_shop_type() == 'supplier' || $info->PaymentMethod->name != 'COD' || $info->payment_status == '1')
                                                    <select class="form-control payment_set" name="payment_status"
                                                        style="width: 202px;" disabled>
                                                        <option disabled=""><b>{{ __('Select Payment Status') }}</b>
                                                        </option>
                                                        <option value="1"
                                                            @if ($info->payment_status == '1') selected="" @endif>
                                                            {{ __('Payment Complete') }}</option>
                                                        <option value="2"
                                                            @if ($info->payment_status == '2') selected="" @endif>
                                                            {{ __('Payment Pending') }}</option>
                                                        <option value="0"
                                                            @if ($info->payment_status == '0') selected="" @endif>
                                                            {{ __('Payment Cancel') }}</option>
                                                        <option value="3"
                                                            @if ($info->payment_status == '3') selected="" @endif>
                                                            {{ __('Payment Incomplete') }}</option>
                                                    </select>
                                                    &nbsp&nbsp
                                                @else
                                                    <select class="form-select payment_set" name="payment_status"
                                                        style="width: 202px;">
                                                        <option disabled=""><b>{{ __('Select Payment Status') }}</b>
                                                        </option>
                                                        <option value="1"
                                                            @if ($info->payment_status == '1') selected="" @endif>
                                                            {{ __('Payment Complete') }}</option>
                                                        <option value="2"
                                                            @if ($info->payment_status == '2') selected="" @endif>
                                                            {{ __('Payment Pending') }}</option>
                                                        <option value="0"
                                                            @if ($info->payment_status == '0') selected="" @endif>
                                                            {{ __('Payment Cancel') }}</option>
                                                        <option value="3"
                                                            @if ($info->payment_status == '3') selected="" @endif>
                                                            {{ __('Payment Incomplete') }}</option>
                                                    </select>
                                                    &nbsp&nbsp

                                                @endif
                                               

                                            </div>
                                            <div class="order_set">
                                                <select class="form-select" name="status" style="width: 202px;">
                                                    <option disabled=""><b>{{ __('select order status') }}</b>
                                                    </option>
                                                    <option value="pending"
                                                        @if ($info->status == 'pending') selected="" @endif>
                                                        {{ __('Awaiting processing') }}</option>
                                                    <option value="processing"
                                                        @if ($info->status == 'processing') selected="" @endif>
                                                        {{ __('Processing') }}</option>
                                                    <option value="ready-for-pickup"
                                                        @if ($info->status == 'ready-for-pickup') selected="" @endif>
                                                        {{ __('Ready for pickup') }}</option>
                                                    <option value="picked_up"
                                                        @if ($info->status == 'picked_up') selected="" @endif>
                                                        {{ __('Picked-up') }}</option>
                                                    <option value="delivered"
                                                        @if ($info->status == 'delivered') selected="" @endif>
                                                        {{ __('Delivered') }}</option>
                                                    <option value="completed"
                                                        @if ($info->status == 'completed') selected="" @endif>
                                                        {{ __('Completed') }}</option>
                                                    <option value="archived"
                                                        @if ($info->status == 'archived') selected="" @endif>
                                                        {{ __('Archived') }}</option>
                                                    <option value="canceled"
                                                        @if ($info->status == 'canceled') selected="" @endif>
                                                        {{ __('Cancelled') }}</option>
    
                                                </select>
                                            </div>
                                            <div class="card-footer mt-1">
                                                <div class="col-6" style=" display: flex; ">
                                                    <label for=""> {{ __('Estimated Delivery Date') }}</label> 
                                                    <input type="date" class="form-control flatpickr-basic mb-1" placeholder="YYYY-MM-DD"
                                                    name="estimated_delivery_date" id="estimated_delivery_date" 
                                                    value="<?php if($info->estimated_delivery_date != NULL) echo strftime('%Y-%m-%d',strtotime($info->estimated_delivery_date)); ?>"
                                                    min="<?php echo date("Y-m-d"); ?>">
                                                </div>
                                            </div>
                                           
                                    </div>

                                    <button type="submit"
                                        class="btn btn-success  mt-2 ml-2 basicbtn">{{ __('Save Changes') }}</button>
                                    <a href="{{ route('seller.invoice_receipt', $info->id) }}"
                                        class="btn btn-info text-right mt-2 ml-2">{{ __('Print Receipt') }}</a>
                                    <a href="{{ route('seller.invoice', $info->id) }}"
                                        class="btn btn-warning text-right mt-2 ml-2">{{ __('Download Invoice') }}</a>
                                    </form>
                                    {{-- @if ($info->status == 'ready-for-pickup')
                                <a href="{{ route('seller.agent',$info->id) }}" class="btn btn-primary mt-2">{{ __('Map Delivery Agent') }}</a>
                                @endif --}}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($info->status == 'ready-for-pickup')
                    @if ($info->agent_id == null)
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Assign Delivery Agent') }}</h4>
                        </div>
                        <div class="card-body card-agent">
                            <form class="basicform"
                                action="{{ route('seller.orders.agent-update', $info->id) }}"
                                method="post">
                                @csrf
                                @method('POST')
                                <div class="card-body">
                                    <div style="margin-bottom: -5px;">
                                        <select name="agents" id="agents" class="agents select2 form-select" style="width: 360px;">
                                        </select><br>
                                        <button type="submit" class="btn btn-primary float-right"
                                            style="margin-top: 58px;">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    @endif
                    @if ($info->agent_id != null)
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Delivery Agent') }}</h4>
                        </div>
                        <div class="card-body">
                            <img src="{{ asset($info->agent_avathar_details->avathar ?? '') }}"
                                height="80" width="80" style="border-radius: 50%"
                                class="float-left" />
                            
                            <p class="float-left" style="font-weight: bold;font-size: 25px;padding: 14px;">
                                {{ $info->agent_details->first_name ?? '' }}</p>
                            @if (current_shop_type() != 'reseller')    
                            <p class="agent_order"> {{ $agent->orders ?? '' }} {{ __('Current Orders') }} </p>
                           @endif 
                        </div>
                        @if (current_shop_type() != 'reseller')
                            @if ($info->status != 'delivered' && $info->status != 'completed')
                                <div class="card-footer">
                                    <a href="javascript:void(0)" data-bs-target="#change_agent"
                                        data-bs-toggle="modal">
                                        <span class="btn btn-primary float-right">{{ __('Change') }}</span>
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                    @endif
                </div>
                <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                    <div class="card-grouping">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Status') }}</h4>
                            </div>
                            <div class="card-body">
                                <p>{{ __('Payment Status') }}
                                    @if ($info->payment_status == 2)
                                        <span
                                            class="float-right badge rounded-pill bg-warning">{{ __('Pending') }}</span>
                                    @elseif($info->payment_status == 1)
                                        <span
                                            class="float-right badge rounded-pill bg-success">{{ __('Paid') }}</span>
                                    @elseif($info->payment_status == 0)
                                        <span
                                            class="float-right badge rounded-pill bg-danger">{{ __('Cancel') }}</span>
                                    @elseif($info->payment_status == 3)
                                        <span
                                            class="float-right badge rounded-pill bg-danger">{{ __('Incomplete') }}</span>
                                    @endif
                                </p>


                                <p>{{ __('Order Status') }} @if ($info->status == 'pending')
                                        <span
                                            class="badge rounded-pill bg-warning float-right">{{ __('Awaiting processing') }}</span>
                                    @elseif($info->status == 'processing')
                                        <span
                                            class="badge rounded-pill bg-primary float-right">{{ __('Processing') }}</span>
                                    @elseif($info->status == 'ready-for-pickup')
                                        <span
                                            class="badge rounded-pill bg-info float-right">{{ __('Ready for pickup') }}</span>
                                    @elseif($info->status == 'completed')
                                        <span
                                            class="badge rounded-pill bg-success float-right">{{ __('Completed') }}</span>
                                    @elseif($info->status == 'delivered')
                                        <span
                                            class="badge rounded-pill bg-info float-right">{{ __('Delivered') }}</span>
                                    @elseif($info->status == 'picked_up')
                                        <span
                                            class="badge rounded-pill bg-info float-right">{{ __('Picked-up') }}</span>
                                    @elseif($info->status == 'archived')
                                        <span
                                            class="badge rounded-pill bg-danger float-right">{{ __('Archived') }}</span>
                                    @elseif($info->status == 'canceled')
                                        <span
                                            class="badge rounded-pill bg-danger float-right">{{ __('Cancelled') }}</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-primary float-right">{{ $info->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if (current_shop_type() != 'reseller')
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('Payment Mode') }}</h4>

                                </div>
                                <div class="card-body">
                                    @if ($info->payment_method != null)
                                        <p>{{ __('Transaction Method') }} <span
                                                class="badge rounded-pill bg-success  float-right">{{ $info->PaymentMethod->name ?? '' }}
                                            </span></p>
                                        <p>{{ __('Transaction Id') }} <span
                                                class="float-right">{{ $info->transaction_id ?? '' }}</span></p>
                                    @else
                                        <p>{{ __('Incomplete Payment') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-header-title">{{ __('CUSTOMER') }}</h4>
                                </div>
                                <div class="card-body">
                                    @if ($info->customer != null)
                                        @if (current_shop_type() == 'seller')
                                            <a href="{{ route('seller.customer.show', $info->customer->id) }}">{{ $info->customer->fullname }}
                                                (#{{ $info->customer->id }})</a>
                                        @else
                                            {{ $info->customer->fullname }}
                                        @endif
                                    @else
                                        {{ __('Guest Customer') }}
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (current_shop_type() != 'seller')
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('Order Settlement') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if (current_shop_type() == 'supplier')
                                        <div class="col-12">
                                            <div class="mb-2 pb-50">
                                                <h5>{{ __('Your Amount') }} <strong>:</strong></h5>
                                                <span>{{ amount_format($info->supplier_settlement->total ?? 0) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-2 pb-50">
                                                <h5>{{ __('Reseller Amount') }} <strong>:</strong></h5>
                                                <span>{{ amount_format($info->reseller_settlement->total ?? 0) }}</span>
                                            </div>
                                        </div>
                                        {{-- <div class="col-12">
                                            <div class="mb-2 pb-50">
                                                <h5>{{ __('Commission') }} <strong>:</strong></h5>
                                                <span>{{ amount_format($info->commission) }}</span>
                                            </div>
                                        </div> --}}
                                        @else
                                        <div class="col-12">
                                            <div class="mb-2 pb-50">
                                                <h5>{{ __('Your Amount') }} <strong>:</strong></h5>
                                                <span>{{ amount_format($info->reseller_settlement->total ?? 0) }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </section>

        <!-- Modal -->
        <div class="modal fade" id="change_agent" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-5 px-sm-5 pt-50">
                        <div class="text-center mb-2">
                            <h1 class="mb-1">{{ __('Change Delivery Agent') }}</h1>

                        </div>
                        <hr />
                        <form class="basicform_with_reload row gy-1 pt-75"
                            action="{{ route('seller.orders.agent-update', $info->id) }}" method="post">
                            @csrf
                            <div class="col-12 col-md-10 change_agent">
                                <select name="agents" id="agents-1" class="form-control agents-1">

                                </select>
                            </div>



                            <div class="col-12 text-center mt-2 pt-50">
                                <button type="submit" class="btn btn-primary me-1 basicbtn"
                                    id="submit">{{ __('Save') }}</button>
                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">{{ __('Discard') }}
                                    
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
