@extends('layouts.seller')
@section('title', 'Orders')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
@endsection
@section('content')
    <!-- BEGIN: Content-->

    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        {{-- <h2 class="content-header-title float-start mb-0">{{ __('Orders') }}</h2> --}}
                        @if (!empty($search_order_type))
                        <h2 class="content-header-title float-start mb-0">
                        @if ($search_order_type == 'pending')
                            {{ __('Pending Orders') }}
                        @endif
                        @if ($search_order_type == 'processing')
                            {{ __('Processing Orders') }}
                        @endif
                        @if ($search_order_type == 'ready-for-pickup')
                            {{ __('Ready For Pick Up Orders') }}
                        @endif
                        @if ($search_order_type == 'picked_up')
                            {{ __('Picked Up Orders') }}
                        @endif
                        @if ($search_order_type == 'delivered')
                            {{ __('Delivered Orders') }}
                        @endif
                        @if ($search_order_type == 'completed')
                            {{ __('Completed Orders') }}
                        @endif
                        @if ($search_order_type == 'archived')
                            {{ __('Archived Orders') }}
                        @endif
                        @if ($search_order_type == 'canceled')
                            {{ __('Order Cancelled') }}
                        @endif
                        </h2>
                    @endif

                    @if (empty($search_order_type))
                    <h2 class="content-header-title float-start mb-0">
                        @if ($type == 'canceled')
                            {{ __('Order Cancelled') }}
                        @endif
                        @if ($type == 'all')
                            {{ __('Orders') }}
                        @endif
                    </h2>
                    @endif
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Orders') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header mb-10 border-bottom">
                    <div class="head-label">
                        <h4>{{ __('Filter') }}</h4>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons d-inline-flex">
                            <a class="btn btn-icon btn-primary waves-effect waves-float waves-light"
                                data-bs-toggle="collapse" href="#collapseOrderFilter" role="button" aria-expanded="false"
                                aria-controls="collapseOrderFilter">
                                <i class="ph-funnel-bold font-medium-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body collapse" id="collapseOrderFilter">
                    <form>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="payment_status">{{ __('Payment Status') }}</label>
                                    <select class="form-select" name="payment_status" id="payment_status">
                                        <option value="2">{{ __('Pending') }}</option>
                                        <option value="1">{{ __('Complete') }}</option>
                                        <option value="3">{{ __('Incomplete') }}</option>
                                        <option value="cancel">{{ __('Cancel') }}</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="status">{{ __('FULFILLMENT') }}</label>
                                    <select class="form-select" name="status" id="status">
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="processing">{{ __('Processing') }}</option>
                                        <option value="ready-for-pickup">{{ __('Ready for pickup') }}</option>
                                        <option value="picked_up">{{ __('picked_up') }}</option>
                                        <option value="delivered">{{ __('delivered') }}</option>
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="archived">{{ __('Archived') }}</option>
                                        <option value="canceled">{{ __('Cancelled') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="status">{{ __('Starting date') }}</label>
                                    <input type="text" id="start" name="start" class="form-control flatpickr-basic"
                                        placeholder="YYYY-MM-DD" value="{{ $request->start }}" />
                                    {{-- <input type="date" name="start" class="form-control" value="{{ $request->start }}" /> --}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="status">{{ __('Ending date') }}</label>
                                    <input type="text" id="end" name="end" class="form-control flatpickr-basic"
                                        placeholder="YYYY-MM-DD" value="{{ $request->end }}" />
                                    {{-- <input type="date" name="end" class="form-control" value="{{ $request->end }}" /> --}}
                                </div>
                            </div>
                        </div>
                        <a href="{{ url()->current() }}" class="btn btn-secondary">{{ __('Clear Filter') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('Filter') }}</button>
                    </form>
                    {{-- <div class="col-sm-10">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link @if (url()->current() == route('seller.orders.status', 'all')) active @endif" href="{{ route('seller.orders.status','all') }}">{{ __('All') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($type == 'pending') active @endif" href="{{ route('seller.orders.status','pending') }}">{{ __('Awaiting processing') }} <span class="badge badge-light-secondary">{{ $pendings }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  @if ($type == 'processing') active @endif" href="{{ route('seller.orders.status','processing') }}">{{ __('Processing') }} <span class="badge badge-light-secondary">{{ $processing }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($type == 'ready-for-pickup') active @endif" href="{{ route('seller.orders.status','ready-for-pickup') }}">{{ __('Ready for pickup') }} <span class="badge badge-light-secondary">{{ $pickup }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($type == 'picked_up') active @endif" href="{{ route('seller.orders.status','picked_up') }}">{{ __('Picked_Up') }} <span class="badge badge-light-secondary">{{ $da_pickup }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($type == 'delivered') active @endif" href="{{ route('seller.orders.status','delivered') }}">{{ __('Delivered') }} <span class="badge badge-light-secondary">{{ $da_delivery }}</span></a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link @if ($type == 'completed') active @endif" href="{{ route('seller.orders.status','completed') }}">{{ __('Completed') }} <span class="badge badge-light-secondary">{{ $completed }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($type == 'canceled') active @endif" href="{{ route('seller.orders.status','canceled') }}">{{ __('Cancelled') }} <span class="badge badge-light-secondary">{{ $canceled }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($type == 'archived') active @endif" href="{{ route('seller.orders.status','archived') }}">{{ __('Archived') }} <span class="badge badge-light-secondary">{{ $archived }}</span></a>
                        </li>
                    </ul>
                </div> --}}
                </div>
            </div>

                <!-- api key list -->
            <div class="d-flex flex-row flex-wrap justify-content-end mt-1">
                <div class="flex-row mb-1">
                    <form class="card-header-form">
                        <div class="input-group">
                            <input type="text" name="src" value="{{ $request->src ?? '' }}"
                                class="form-control" required="" placeholder="ABC-123" />
                            <button class="btn btn-outline-primary waves-effect" type="submit">
                                <i class="ph-magnifying-glass-bold"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @if ($type == 'all')
                    @if (current_shop_type() == 'seller')
                        @if (user_plan_access('pos') == true)
                            <a href="{{ route('seller.order.create') }}" class="mb-1 ms-1">
                                <span class="btn btn-primary">{{ __('Create order') }}</span>
                            </a>
                        @endif
                    @endif
                @endif
            </div>
            <div class="row gy-2 mt-1 mobile_view">
                @foreach ($orders as $key => $row)
                    <div class="col-12">
                        <div class="card">
                            <div class="rounded p-2">
                                <div class="dropdown dropstart btn-pinned">
                                    <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                        href="javascript:void(0)" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical" class="font-medium-4"></i>
                                    </a>
                                    @if (current_shop_type() != 'reseller')
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.invoice', $row->id) }}">
                                                    <i class="ph-file-pdf-bold"></i><span>Download Invoice</span>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                                <div class="mb-25">
                                    @if ($row->customer_id !== null)
                                        @if (current_shop_type() != 'supplier')
                                            <a href="{{ route('seller.customer.show', $row->customer_id) }}" class="text-black">
                                                {{ $row->customer->fullname }}
                                            </a>
                                        @else
                                        <span class="text-black"> {{ $row->customer->fullname }}</span>
                                        @endif
                                    @else
                                    <span class="text-black">{{ __('Guest User') }}</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-start flex-column mb-25">
                                    <a href="{{ route('seller.order.show', $row->id) }}">{{ $row->order_no }}</a>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap mb-25">
                                    <b>{{ amount_format($row->total) }}</b>
                                    @if ($row->status == 'pending')
                                    <span
                                        class="badge badge-light-warning mb-1">{{ __('Awaiting processing') }}</span>
                                    @elseif($row->status == 'processing')
                                        <span class="badge badge-light-primary mb-1">{{ __('Processing') }}</span>
                                    @elseif($row->status == 'ready-for-pickup')
                                        <span class="badge badge-light-info mb-1">{{ __('Ready for pickup') }}</span>
                                    @elseif($row->status == 'delivered')
                                        <span class="badge badge-light-info mb-1">{{ __('Delivered') }}</span>
                                    @elseif($row->status == 'picked_up')
                                        <span class="badge badge-light-info mb-1">{{ __('Picked_Up') }}</span>
                                    @elseif($row->status == 'completed')
                                        <span class="badge badge-light-success mb-1">{{ __('Completed') }}</span>
                                    @elseif($row->status == 'archived')
                                        <span class="badge badge-light-warning mb-1">{{ __('Archived') }}</span>
                                    @elseif($row->status == 'canceled')
                                        <span class="badge badge-light-danger mb-1">{{ __('Cancelled') }}</span>
                                    @else
                                        <span class="badge badge-light-info mb-1">{{ $row->status }}</span>
                                    @endif
                                </div>
                                <span>Created at : <span class="text-black-50">{{ $row->created_at->format('d-F-Y') ?? '' }}</span></span>
                                {{-- <h6 class="d-flex align-items-center fw-bolder">
                                        <span class="me-50">{{ amount_format($row->total) ?? '' }}</span>
                                    </h6>
                                    <h6 class="d-flex align-items-center fw-bolder">
                                        <span>{{ __('Item(s)') }}</span>
                                        <span class="badge badge-light-info" style="margin-left: 6px !important;">
                                            {{ $row->order_items_count ?? '' }}</span>
                                    </h6> --}}
                                {{-- @if (current_shop_type() != 'supplier') 
                                    <h6 class="d-flex align-items-center fw-bolder">
                                        @if ($row->featured == 1)
                                            <span>Featured</span><span class="badge badge-light-success"
                                                style="margin-left: 6px !important;"> {{ __('Yes') }}</span>
                                        @else
                                            <span>Featured</span><span class="badge badge-light-warning"
                                                style="margin-left: 6px !important;"> {{ __('No') }}</span>
                                        @endif
                                    </h6>
                                    @endif  --}}
                                {{-- <span>Created at {{ $row->created_at->format('d-F-Y') ?? '' }}</span> --}}
                            </div>
                        </div>

                    </div>
                @endforeach
            </div><br>
            <div class="card mobile_view desktop_view">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ __('Orders') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    @if (current_shop_type() != 'reseller')
                                        <th>{{ __('CUSTOMER') }}</th>
                                        <th>{{ __('Delivery Agent') }}</th>
                                    @endif
                                    <th class="text-right">{{ __('ORDER TOTAL') }}</th>
                                    @if (current_shop_type() == 'reseller')
                                        <th>{{ __('Margin Earned') }}</th>
                                    @endif
                                    @if (current_shop_type() == 'supplier')
                                        <th>{{ __('Your Share') }}</th>
                                    @endif
                                    <th>{{ __('PAYMENT') }}</th>
                                    <th>{{ __('FULFILLMENT') }}</th>
                                    <th class="text-right">{{ __('ITEM(S)') }}</th>
                                    @if (current_shop_type() != 'reseller')
                                        <th class="text-right">{{ __('INVOICE') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="list font-size-base rowlink" data-link="row">
                                @foreach ($orders as $key => $row)
                                    <tr>
                                        <td class="text-left">
                                            <a
                                                href="{{ route('seller.order.show', $row->id) }}">{{ $row->order_no }}</a>
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
                                            <td>{{ $row->agent_details->first_name ?? '' }}</td>
                                        @endif
                                        <td>{{ amount_format($row->total) }}</td>
                                        @if (current_shop_type() == 'reseller')
                                            <td>{{ amount_format($row->reseller_settlement->total ?? 0) }}</td>
                                        @elseif (current_shop_type() == 'supplier')
                                            <td>{{ amount_format($row->supplier_settlement->total ?? 0) }}</td>
                                        @endif
                                        <td>
                                            @if ($row->payment_status == 2)
                                                <span class="badge badge-light-warning">{{ __('Pending') }}</span>
                                            @elseif($row->payment_status == 1)
                                                <span
                                                    class="badge badge-light-success">{{ __('Complete') }}</span>
                                            @elseif($row->payment_status == 0)
                                                <span class="badge badge-light-danger">{{ __('Cancel') }}</span>
                                            @elseif($row->payment_status == 3)
                                                <span
                                                    class="badge badge-light-danger">{{ __('Incomplete') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->status == 'pending')
                                                <span
                                                    class="badge badge-light-warning">{{ __('Awaiting processing') }}</span>
                                            @elseif($row->status == 'processing')
                                                <span
                                                    class="badge badge-light-primary">{{ __('Processing') }}</span>
                                            @elseif($row->status == 'ready-for-pickup')
                                                <span
                                                    class="badge badge-light-info">{{ __('Ready for pickup') }}</span>
                                            @elseif($row->status == 'delivered')
                                                <span class="badge badge-light-info">{{ __('Delivered') }}</span>
                                            @elseif($row->status == 'picked_up')
                                                <span class="badge badge-light-info">{{ __('Picked_Up') }}</span>
                                            @elseif($row->status == 'completed')
                                                <span
                                                    class="badge badge-light-success">{{ __('Completed') }}</span>
                                            @elseif($row->status == 'archived')
                                                <span
                                                    class="badge badge-light-warning">{{ __('Archived') }}</span>
                                            @elseif($row->status == 'canceled')
                                                <span
                                                    class="badge badge-light-danger">{{ __('Cancelled') }}</span>
                                            @else
                                                <span class="badge badge-light-info">{{ $row->status }}</span>
                                            @endif
                                        </td>

                                        <td>{{ $row->order_items_count }}</td>
                                        @if (current_shop_type() != 'reseller')
                                            <td>
                                                <a href="{{ route('seller.invoice', $row->id) }}"
                                                    class="btn btn-icon btn-primary waves-effect waves-float waves-light">
                                                    <i class="ph-file-pdf-bold"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="d-flex flex-row justify-content-end mt-2">
                            @if (count($request->all()) > 0)
                                {{ $orders->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                            @else
                                {{ $orders->links('vendor.pagination.bootstrap-4') }}
                            @endif
                        </div> --}}
                </div>
            </div>
            </form>

            <div class="d-flex flex-row justify-content-end mt-2">
                @if (count($request->all()) > 0)
                    {{ $orders->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                @else
                    {{ $orders->links('vendor.pagination.bootstrap-4') }}
                @endif
            </div>

        </div>
    </div>

    <!-- END: Content-->


    <input type="hidden" id="payment" value="{{ $request->payment_status ?? '' }}">
    <input type="hidden" id="order_status" value="{{ $request->status ?? '' }}">
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/order_index.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
@endsection
