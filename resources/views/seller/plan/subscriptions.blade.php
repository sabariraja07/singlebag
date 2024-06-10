@extends('layouts.seller')
@section('title', 'Subscription History')
@section('content')

    <!-- BEGIN: Content-->

    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('Subscription History') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">{{ __('Settings') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('Subscription History') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="content-body">
            <section>

                <!-- api key list -->
                {{-- <div class="card"> --}}
                <div class="card-body">

                    <!-- Mobile View Start-->
                    <div class="row gy-2 mobile_view">
                        @foreach ($posts as $row)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body" style="padding: 0.5rem !important;">
                                        <div class="position-relative rounded p-25">
                                            <div class="dropdown dropstart btn-pinned" style="top: 0.2rem !important;">
                                                <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                                    href="javascript:void(0)" id="dropdownMenuButton1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i data-feather="more-vertical" class="font-medium-4"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.plan.show', $row->id) }}">
                                                            <i data-feather="eye"
                                                                class="me-50"></i><span>{{ __('View') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.order.invoice', $row->id) }}">
                                                            <i data-feather="edit-2"
                                                                class="me-50"></i><span>{{ __('Download Invoice') }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="d-flex">
                                                <h3>{{ $row->order_no }} </h3>
                                                <div class="blog-info" style="margin-left: 10px;">
                                                    @if ($row->payment_status == 1)
                                                        <span
                                                            class="badge badge-light-primary mb-1">{{ __('Paid') }}</span>
                                                    @elseif($row->payment_status == 2)
                                                        <span
                                                            class="badge badge-light-warning mb-1">{{ __('Pending') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-danger mb-1">{{ __('Fail') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!--Mobile View End -->

                    <!-- Desktop View Start -->
                    <div class="card mobile_view desktop_view">
                        <div class="card-body">
                            <table class="datatables-basic table" id="pagenate">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ __('ORDER') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Purchase Date') }}</th>
                                        <th>{{ __('Expiry date') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <!-- <th>{{ __('Tax') }}</th> -->
                                        <th>{{ __('Payment Method') }}</th>
                                        <th>{{ __('Payment Status') }}</th>
                                        <!-- <th>{{ __('Fulfillment') }}</th> -->
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $row)
                                        <tr>
                                            <td class="text-left">{{ $row->order_no }}</td>
                                            <td>{{ $row->plan_info->name ?? '' }}</td>
                                            <td>{{ $row->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $row->will_expire }}</td>
                                            <td>{{ amount_admin_format($row->amount, 2) }}</td>
                                            <!-- <td>{{ amount_admin_format($row->tax, 2) }}</td> -->
                                            <td>
                                                @if($row->amount != '0.00' || $row->amount != '0')
                                                    {{ $row->PaymentMethod->name ?? '' }}
                                                @else
                                                    -  
                                                @endif

                                            </td>
                                            <td>
                                                @if ($row->payment_status == 1)
                                                    <span
                                                        class="badge rounded-pill badge-light-success">{{ __('Paid') }}</span>
                                                @elseif($row->payment_status == 2)
                                                    <span
                                                        class="badge rounded-pill badge-light-warning">{{ __('Pending') }}</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill badge-light-danger">{{ __('Fail') }}</span>
                                                @endif

                                            </td>

                                                                                        <!-- <td>
                                                                                                @if ($row->status == 1)
                                                <span class="badge rounded-pill badge-light-success">Approved</span>
                                            @elseif($row->status == 2)
                                                <span class="badge rounded-pill badge-light-warning">{{ __('Pending') }}</span>
                                            @elseif($row->status == 3)
                                                <span class="badge rounded-pill badge-light-danger">{{ __('Expired') }}</span>
                                            @else
                                                <span class="badge rounded-pill badge-light-danger">{{ __('Cancelled') }}</span>
                                                @endif
                                                                
                                                                                            </td> -->

                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                        data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item"
                                                            href="{{ route('seller.plan.show', $row->id) }}">
                                                            <i data-feather="eye" class="me-50"></i>
                                                            <span>{{ __('View') }}</span>
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('seller.order.invoice', $row->id) }}">
                                                            <i data-feather="edit" class="me-50"></i>
                                                            <span>{{ __('Download Invoice') }}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--Desktop View End -->

                </div>
                {{-- </div> --}}

            </section>

        </div>
    </div>
@endsection
