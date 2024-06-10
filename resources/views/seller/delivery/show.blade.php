@extends('layouts.seller')
@section('title', 'Agent Info')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
    <style>
        .vertical-layout.vertical-menu-modern.menu-expanded .footer {
            margin-left: -27px !important;
        }

        @media screen and (max-width: 900px) {
            #export_btn {
                margin: auto !important;
                display: block !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Agent Info') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ url('/seller/delivery') }}">{{ __('Delivery Agent') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Agent Info') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">

                        <h5></h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Name') }}: <b>{{ $info->fullname }}</b>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Email') }}: <b>{{ $info->email }}</b>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Mobile Number') }}: <b>{{ $info->mobile ?? '' }}</b>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Id Proff') }}: <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">{{ __('Click Here') }}</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('REGISTERED AT') }}: <b>{{ $info->created_at->diffForHumans() }}</b>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Registration Date') }}: <b>{{ $info->created_at->format('d-F-Y') }}</b>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Total Assigned Orders') }}
                                <span>{{ $info->agent_orders_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Total Ready For Pickup Orders') }}
                                <span>{{ $info->agent_orders_ready_pickup_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Total Picked Up Orders') }}
                                <span>{{ $info->agent_orders_processing_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Total Complete Orders') }}
                                <span>{{ $info->agent_orders_complete_count }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Export History') }}</h4>
                    </div>
                    <form action="{{ route('seller.agent.export', $info->id) }}" class="form-padding mb-1">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <label>{{ __('From') }}

                            <input type="date" class="form-control flatpickr-basic mb-1" placeholder="YYYY-MM-DD"
                                name="from_date" required>
                        </label>
                        <label>{{ __('To') }}

                            <input type="date" class="form-control flatpickr-basic mb-1" placeholder="YYYY-MM-DD"
                                name="to_date" required>
                        </label>
                        &nbsp;&nbsp;
                        <button class="btn btn-primary mb-1" id="export_btn"
                            type="submit">{{ __('Export to excel') }}</button>

                    </form>
                </div>
            </div>
        </div>


        <div class="row desktop_view">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Order History') }}</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>{{ __('Invoice ID') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Items') }}</th>
                                        <th>{{ __('Payment Method') }}</th>
                                        <th>{{ __('Payment Status') }}</th>
                                        <th>{{ __('Order Status') }}</th>
                                        <th>{{ __('Due Date') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    @foreach ($orders as $row)
                                        <tr>
                                            <td> {{ $row->order_no }} </td>
                                            <td class="font-weight-00">{{ amount_format($row->total) }}</td>
                                            <td class="font-weight-600">{{ $row->order_item_count }}</td>
                                            <td>
                                                {{ strtoupper($row->payment_method ?? '')  }}
                                            </td>
                                            <td>
                                                @if ($row->payment_status == 2)
                                                    <span
                                                        class="badge rounded-pill badge-light-warning">{{ __('Pending') }}</span>
                                                @elseif($row->payment_status == 1)
                                                    <span
                                                        class="badge rounded-pill badge-light-success">{{ __('Complete') }}</span>
                                                @elseif($row->payment_status == 0)
                                                    <span
                                                        class="badge rounded-pill badge-light-danger">{{ __('Cancel') }}</span>
                                                @elseif($row->payment_status == 3)
                                                    <span
                                                        class="badge rounded-pill badge-light-danger">{{ __('Incomplete') }}</span>
                                                @endif

                                            </td>
                                            <td>

                                                @if ($row->status == 'pending')
                                                    <span
                                                        class="badge rounded-pill badge-light-warning">{{ __('Awaiting processing') }}</span>
                                                @elseif($row->status == 'processing')
                                                    <span
                                                        class="badge rounded-pill badge-light-primary">{{ __('Processing') }}</span>
                                                @elseif($row->status == 'ready-for-pickup')
                                                    <span
                                                        class="badge rounded-pill badge-light-info">{{ __('Ready for pickup') }}</span>
                                                @elseif($row->status == 'completed')
                                                    <span
                                                        class="badge rounded-pill badge-light-success">{{ __('Completed') }}</span>
                                                @elseif($row->status == 'delivered')
                                                    <span
                                                        class="badge rounded-pill badge-light-info">{{ __('Delivered') }}</span>
                                                @elseif($row->status == 'picked_up')
                                                    <span
                                                        class="badge rounded-pill badge-light-info">{{ __('Picked Up') }}</span>
                                                @elseif($row->status == 'archived')
                                                    <span
                                                        class="badge rounded-pill badge-light-warning">{{ __('Archived') }}</span>
                                                @elseif($row->status == 'canceled')
                                                    <span
                                                        class="badge rounded-pill badge-light-danger">{{ __('Canceled') }}</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill badge-light-info">{{ $row->status }}</span>
                                                @endif

                                            </td>
                                            <td>{{ $row->created_at->format('d-F-Y') }}</td>
                                            <td>
                                                <a href="{{ route('seller.order.show', $row->id) }}"
                                                    class="btn btn-success">{{ __('Detail') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            {{ $orders->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
   
        <div class="row gy-2 mobile_view">
            <h2>{{ __('Order History') }}</h2>
            @php
                $url = my_url();
            @endphp
            @foreach ($orders as $row)
                <div class="col-12">
                    <div class="card">
                        <div class="position-relative rounded p-2">
                            <div class="dropdown dropstart btn-pinned">
                                <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                    href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i data-feather="more-vertical" class="font-medium-4"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ route('seller.order.show', $row->id) }}">
                                            <i data-feather="eye" class="me-50"></i><span>{{ __('View') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="d-flex align-items-center flex-wrap">
                                <h4>{{ $row->order_no }}</h4>
                            </div>
                            <p>{{ amount_format($row->total) }}</p>

                        </div>
                    </div>

                </div>
            @endforeach
            {{ $orders->links('vendor.pagination.bootstrap-4') }}
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Agent ID Proff') }}</h5>
                        <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button> -->
                    </div>


                    <div class="modal-body">
                        <div>
                            <img src="{{ asset($info->preview->image_id) }}" style="max-width:450px;height:180px">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>

                    </div>

                </div>
            </div>
        </div>
    @endsection
    @section('page-script')
        <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
        <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
    @endsection
