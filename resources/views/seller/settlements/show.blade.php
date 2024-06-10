@extends('layouts.seller')
@section('title', 'Settlement Details')
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Settlements') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ url('/seller/settlements') }}">{{ __('Settlements') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Settlement No : ' . $info->invoice_no) }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none"></div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('Settlement Details') }}</h4>
                </div>
                <div class="card-body my-2 py-25">
                    <div class="row">
                        {{-- <div class="col-md-6">
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Username:</span>
                                        <span>violet.dev</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Billing Email:</span>
                                        <span>vafgot@vultukir.org</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Status:</span>
                                        <span class="badge bg-light-success">Active</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Role:</span>
                                        <span>Author</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Tax ID:</span>
                                        <span>Tax-8965</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Contact:</span>
                                        <span>+1 (609) 933-44-22</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Language:</span>
                                        <span>English</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Country:</span>
                                        <span>Wake Island</span>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                        <div class="col-xl-6 col-md-7 col-12">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 fw-bolder mb-1">{{ __('Settlement No') }}:</dt>
                                <dd class="col-sm-8 mb-1">{{ $info->invoice_no }}</dd>

                                <dt class="col-sm-4 fw-bolder mb-1">{{ __('Total Amount') }}:</dt>
                                <dd class="col-sm-8 mb-1">{{ amount_format($info->total_amount) }}</dd>
                                <dt class="col-sm-4 fw-bolder mb-1">{{ __('Commission') }}:</dt>
                                <dd class="col-sm-8 mb-1">{{ amount_format($info->charge) }}</dd>
                                <dt class="col-sm-4 fw-bolder mb-1">{{ __('Tax') }}:</dt>
                                <dd class="col-sm-8 mb-1">{{ amount_format($info->tax) }}</dd>
                                <dt class="col-sm-4 fw-bolder mb-1">{{ __('Earned Amount') }}:</dt>
                                <dd class="col-sm-8 mb-1">{{ amount_format($info->amount) }}</dd>
                                <dt class="col-sm-4 fw-bolder mb-1">{{ __('Paid At') }}:</dt>
                                <dd class="col-sm-8 mb-1">{{ $info->paid_at ? $info->paid_at->format('d-F-Y') : '--' }}
                                </dd>

                                <dt class="col-sm-4 fw-bolder mb-1">{{ __('Status') }}:</dt>
                                <dd class="col-sm-8 mb-1">
                                    @if ($info->status == 'paid')
                                        <span class="badge badge-light-success">{{ __('Paid') }}</span>
                                    @elseif($info->status == 'unpaid')
                                        <span class="badge badge-light-danger">{{ __('Unpaid') }}</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="mb-2 pb-50">
                                <h5>{{ __('Settlement No') }}</h5>
                                <span>
                                    <strong>{{  $info->invoice_no }}</strong>
                                </span>
                            </div>
                            <div class="mb-2 pb-50">
                                <h5>{{ __('Amount') }}</h5>
                                <span>{{ amount_format($info->amount) }}</span>
                            </div>
                            <div class="mb-1">
                                <h5>{{ __('Status') }}</h5>
                                @if ($info->status == 'paid')
                                <span class="badge badge-light-success ms-50">Popular</span>
                                @elseif($info->status == 'unpaid')
                                <span class="badge badge-light-success ms-50">Popular</span>
                                @endif
                            </div>
                        </div> --}}
                        <div class="col-xl-6 col-md-5 col-12 col-md-6">
                            <div class="mb-2 pb-50">
                                <h5>Bank Account Details <strong>:</strong></h5>
                                <span>{{ $accountInfo['account_holder_name'] ?? '--' }}</span>
                                <p>{{ $accountInfo['ifsc_code'] ?? '--' }}</p>
                                <p>{{ $accountInfo['account_no'] ?? '--' }}</p>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="alert alert-warning mb-2" role="alert">
                                <h6 class="alert-heading">We need your attention!</h6>
                                <div class="alert-body fw-normal">your plan requires update</div>
                            </div>
                            <div class="plan-statistics pt-1">
                                <div class="d-flex justify-content-between">
                                    <h5 class="fw-bolder">Days</h5>
                                    <h5 class="fw-bolder">4 of 30 Days</h5>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-50">4 days remaining until your plan requires update</p>
                            </div>
                        </div> --}}
                        <!-- <div class="col-12">
                                    <button class="btn btn-primary me-1 mt-1 waves-effect waves-float waves-light">
                                        Download Invoive
                                    </button>
                                    {{-- <button class="btn btn-outline-danger cancel-subscription mt-1 waves-effect">Cancel Subscription</button> --}}
                                </div> -->
                    </div>
                </div>
            </div>
            <div class="card desktop_view">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{ __('Orders') }}</h4>
                </div>
                <div class="card-body mt-2">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ __('Order') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    @if (current_shop_type() != 'reseller')
                                        <th>{{ __('Customer') }}</th>
                                    @endif
                                    <th class="text-right">{{ __('Order total') }}</th>
                                    @if (current_shop_type() == 'reseller')
                                        <th>{{ __('Margin Earned') }}</th>
                                    @endif
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Fulfillment') }}</th>
                                    <th class="text-right">{{ __('Item(s)') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list font-size-base rowlink" data-link="row">
                                @foreach ($orders as $key => $row)
                                    <tr>
                                        <td class="text-left">
                                            <a href="{{ route('seller.order.show', $row->id) }}">{{ $row->order_no }}</a>
                                        </td>
                                        <td><a
                                                href="{{ route('seller.order.show', $row->id) }}">{{ $row->created_at->format('d-F-Y') }}</a>
                                        </td>
                                        @if (current_shop_type() != 'reseller')
                                            <td>
                                                @if ($row->customer_id !== null)
                                                    @if (current_shop_type() != 'supplier')
                                                        <a
                                                            href="{{ route('seller.customer.show', $row->customer_id) }}">{{ $row->customer->fullname }}</a>
                                                    @else
                                                        {{ $row->customer->fullname }}
                                                    @endif
                                                @else
                                                    {{ __('Guest User') }}
                                                @endif
                                            </td>
                                        @endif
                                        <td>{{ amount_format($row->total) }}</td>
                                        @if (current_shop_type() == 'reseller')
                                            <td>{{ amount_format($row->reseller_settlement->total ?? 0) }}</td>
                                        @endif
                                        <td>
                                            @if ($row->payment_status == 2)
                                                <span class="badge badge-light-warning">{{ __('Pending') }}</span>
                                            @elseif($row->payment_status == 1)
                                                <span class="badge badge-light-success">{{ __('Complete') }}</span>
                                            @elseif($row->payment_status == 0)
                                                <span class="badge badge-light-danger">{{ __('Cancel') }}</span>
                                            @elseif($row->payment_status == 3)
                                                <span class="badge badge-light-danger">{{ __('Incomplete') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->status == 'pending')
                                                <span
                                                    class="badge badge-light-warning">{{ __('Awaiting processing') }}</span>
                                            @elseif($row->status == 'processing')
                                                <span class="badge badge-light-primary">{{ __('Processing') }}</span>
                                            @elseif($row->status == 'ready-for-pickup')
                                                <span class="badge badge-light-info">{{ __('Ready for pickup') }}</span>
                                            @elseif($row->status == 'delivered')
                                                <span class="badge badge-light-info">{{ __('Delivered') }}</span>
                                            @elseif($row->status == 'picked_up')
                                                <span class="badge badge-light-info">{{ __('Picked_Up') }}</span>
                                            @elseif($row->status == 'completed')
                                                <span class="badge badge-light-success">{{ __('Completed') }}</span>
                                            @elseif($row->status == 'archived')
                                                <span class="badge badge-light-warning">{{ __('Archived') }}</span>
                                            @elseif($row->status == 'canceled')
                                                <span class="badge badge-light-danger">{{ __('Cancelled') }}</span>
                                            @else
                                                <span class="badge badge-light-info">{{ $row->status }}</span>
                                            @endif
                                        </td>

                                        <td>{{ $row->order_items_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-row justify-content-end mt-2">
                        @if (count($request->all()) > 0)
                            {{ $orders->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                        @else
                            {{ $orders->links('vendor.pagination.bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row gy-2 mobile_view">
                <h2>{{ __('Orders') }}</h2>
                @foreach ($orders as $key => $row)
                    <div class="col-12">
                        <div class="card">
                            <div class="rounded p-2">
                                <a href="{{ route('seller.order.show', $row->id) }}">{{ $row->order_no ?? '' }}</a>
                                <br>
                                @if ($row->payment_status == 2)
                                    <span class="badge badge-light-warning mb-1">{{ __('Pending') }}</span>
                                @elseif($row->payment_status == 1)
                                    <span class="badge badge-light-success mb-1">{{ __('Complete') }}</span>
                                @elseif($row->payment_status == 0)
                                    <span class="badge badge-light-danger mb-1">{{ __('Cancel') }}</span>
                                @elseif($row->payment_status == 3)
                                    <span class="badge badge-light-danger mb-1">{{ __('Incomplete') }}</span>
                                @endif

                                <h6 class="d-flex align-items-center fw-bolder">
                                    <span class="me-50">{{ amount_format($row->total ?? '') }}</span>
                                </h6>
                            </div>
                        </div>

                    </div>
                @endforeach
                <div class="d-flex flex-row justify-content-end mt-2">
                    @if (count($request->all()) > 0)
                        {{ $orders->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                    @else
                        {{ $orders->links('vendor.pagination.bootstrap-4') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
