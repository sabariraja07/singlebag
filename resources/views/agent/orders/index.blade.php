@extends('layouts.agent')
@section('title', 'Orders')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
@endsection
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Orders') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/user/agent/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Orders') }}
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
            <div class="row gy-2 mt-1 mobile_view">
                @foreach ($orders as $key => $row)
                    <div class="col-12">
                        <div class="card">
                            <div class="rounded p-2">
                                {{-- <div class="dropdown dropstart btn-pinned">
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
                                </div> --}}
                                <div class="mb-25">
                                    @if ($row->customer_id !== null)
                                        <span class="text-black"> {{ $row->customer->fullname }}</span>
                                
                                    @else
                                    <span class="text-black">{{ __('Guest User') }}</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-start flex-column mb-25">
                                    <span class="text-black"> {{ $row->order_no }}</span>
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
            <!-- Basic table -->
            <div class="row  mobile_view desktop_view" id="basic-datatable">
                <div class="col-12">
                    {{-- <div class="card">
                        <div class="card-header mb-10 border-bottom">
                            <div class="head-label">
                                <h4 class="card-title">{{ __('Filter') }}</h4>
                            </div>
                            <div class="dt-action-buttons text-end">
                                <div class="dt-buttons d-inline-flex">
                                    <a class="btn btn-icon btn-primary waves-effect waves-float waves-light"
                                        data-bs-toggle="collapse" href="#collapseOrderFilter" role="button"
                                        aria-expanded="false" aria-controls="collapseOrderFilter">
                                        <i class="ph-funnel-bold font-medium-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body collapse" id="collapseOrderFilter">
                            <form>
                                <div class="row">
                                   
                                    <div class="col-md-6 col-12" style="width: 100% !important;">
                                        <div class="mb-1">
                                            <label class="form-label" for="status">{{ __('Fulfillment status') }}</label>
                                            <select class="form-select" name="status" id="status">
                                                <option value="ready-for-pickup">{{ __('ready-for-pickup') }}</option>
                                                <option value="picked_up">{{ __('picked_up') }}</option>
                                                <option value="delivered">{{ __('delivered') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                  
                                   
                                </div>
                                <a href="{{ url()->current() }}" class="btn btn-secondary">{{ __('Clear Filter') }}</a>
                                <button type="submit" class="btn btn-success">{{ __('Filter') }}</button>
                            </form>
                            
                        </div>
                    </div> --}}
                    <div class="card">
                        <div class="card-header mb-10 border-bottom">
                        <div class="head-label">

                            @if (!empty($search_order_type))
                                @if ($search_order_type == 'pending')
                                    <h4 class="card-title">{{ __('Pending Orders') }}</h4>
                                @endif
                                @if ($search_order_type == 'processing')
                                    <h4 class="card-title">{{ __('Processing Orders') }}</h4>
                                @endif
                                @if ($search_order_type == 'ready-for-pickup')
                                    <h4 class="card-title">{{ __('Ready For Pick Up Orders') }}</h4>
                                @endif
                                @if ($search_order_type == 'picked_up')
                                    <h4 class="card-title">{{ __('Picked Up Orders') }}</h4>
                                @endif
                                @if ($search_order_type == 'delivered')
                                    <h4 class="card-title">{{ __('Delivered Orders') }}</h4>
                                @endif
                              
                            @endif

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            {{-- <th class="text-left">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input checkAll"
                                                        id="selectAll">
                                                    <label class="custom-control-label checkAll" for="selectAll"></label>
                                                </div>
                                            </th> --}}
                                            <th class="text-left">{{ __('Orders') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('CUSTOMER') }}</th>
                                            <th>{{ __('Delivery Agent') }}</th>
                                            <th class="text-right">{{ __('ORDER TOTAL') }}</th>
                                            <th>{{ __('PAYMENT') }}</th>
                                            <th>{{ __('FULFILLMENT') }}</th>
                                            <th class="text-right">{{ __('ITEM(S)') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list font-size-base rowlink" data-link="row">
                                        @foreach ($orders as $key => $row)
                                            <tr>
                                                {{-- <td class="text-left">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="ids[]"
                                                            class="custom-control-input"
                                                            id="customCheck{{ $row->id }}"
                                                            value="{{ $row->id }}">
                                                        <label class="custom-control-label"
                                                            for="customCheck{{ $row->id }}"></label>
                                                    </div>
                                                </td> --}}
                                                <td class="text-left">
                                                    <a
                                                        href="javascript::void(0);">{{ $row->order_no ?? '' }}</a>
                                                </td>
                                                <td><a
                                                        href="javascript::void(0);">{{ $row->created_at->format('d-F-Y') ?? ''}}</a>
                                                </td>
                                                    <td>
                                                        @if ($row->customer_id !== null)
                                                                
                                                                {{ $row->customer->fullname ?? '' }}
                                                          
                                                        @else
                                                            {{ __('Guest User') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->agent_details->first_name ?? '' }}</td>
                                                
                                                <td>{{ amount_format($row->total) ?? ''}}</td>
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
                                                        <span class="badge badge-light-info">{{ __('Picked-up') }}</span>
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
                                               
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-row justify-content-end mt-2">
                                {{-- @if (count($request->all()) > 0)
                                    {{ $orders->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                                @else --}}
                                    {{ $orders->links('vendor.pagination.bootstrap-4') }}
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    {{-- <input type="hidden" id="payment" value="{{ $request->payment_status ?? '' }}">
    <input type="hidden" id="order_status" value="{{ $request->status ?? '' }}"> --}}
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/order_index.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
@endsection
