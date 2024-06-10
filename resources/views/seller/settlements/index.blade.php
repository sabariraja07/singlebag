@extends('layouts.seller')

@section('title', 'Settlements')
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
                        <h2 class="content-header-title float-start mb-0">{{ __('Settlements') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Settlements') }}
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
            <div class="row" id="basic-datatable">
                <div class="col-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <div class="alert-body">{{ session()->get('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <div class="alert-body">{{ session()->get('error') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (current_shop_type() == 'reseller')
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="cardMaster border rounded p-2">
                                    <div class="d-flex justify-content-between flex-sm-row flex-column">
                                        <div class="card-information">
                                            <h6>{{ __('Total Earn') }}</h6>
                                            <h3 class="mb-75 mt-1">
                                                <a href="javascript::void(0)">{{ amount_format($total_earn) }}</a>
                                            </h3>
                                            <span class="card-number">
                                                {{ __('Unsettled credits') }} : <strong
                                                    class="text-success">{{ amount_format($unsettlement_amount) }}</strong>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column text-start text-lg-end">
                                            <div class="d-flex justify-content-end order-sm-0 order-1 mt-1 mt-sm-0">
                                                <a href="{{ route('seller.settlements.create') }}"
                                                    class="btn btn-outline-success me-75 waves-effect">
                                                    {{ __('Request Settlement') }}
                                                </a>
                                            </div>
                                            <span class="mt-2">
                                                <span class="text-danger">*</span>
                                                {{ __('Your Threshold Value') }} :
                                                <span class="text-danger"> {{ amount_format($threshold) }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header mb-10 border-bottom">
                            <div class="head-label">
                                <h4 class="card-title" style="width: 60px !important;">{{ __('Filter') }}</h4>
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
                                    {{-- <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label"
                                                for="payment_status">{{ __('Payment Status') }}</label>
                                            <select class="form-select" name="payment_status" id="payment_status">
                                                <option value="2">{{ __('Pending') }}</option>
                                                <option value="1">{{ __('Complete') }}</option>
                                                <option value="3">{{ __('Incomplete') }}</option>
                                                <option value="cancel">{{ __('Cancel') }}</option>

                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="status">{{ __('Starting date') }}</label>
                                            <input type="text" id="start" name="start"
                                                class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                value="{{ $request->start }}" />
                                            {{-- <input type="date" name="start" class="form-control" value="{{ $request->start }}" /> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="status">{{ __('Ending date') }}</label>
                                            <input type="text" id="end" name="end"
                                                class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"
                                                value="{{ $request->end }}" />
                                            {{-- <input type="date" name="end" class="form-control" value="{{ $request->end }}" /> --}}
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="status">{{ __('Fulfillment status') }}</label>
                                            <select class="form-select" name="status" id="status">
                                                <option value="unpaid">{{ __('pending') }}</option>
                                                <option value="paid">{{ __('completed') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ url()->current() }}" class="btn btn-secondary">{{ __('Clear Filter') }}</a>
                                <button type="submit" class="btn btn-success">{{ __('Filter') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-header mb-1 border-bottom">
                        <div class="head-label">
                            <h4 class="card-title">{{ __('Settlements') }}</h4>
                        </div>
                        {{-- <div class="dt-action-buttons text-end">
							<div class="dt-buttons d-inline-flex">
                                <div class="d-flex flex-row justify-content-end mt-2">
                                    <form class="card-header-form">
                                        <div class="input-group">
                                            <input type="text" name="src" value="{{ $request->src ?? '' }}" class="form-control" required=""  placeholder="ABC-123" />
                                            <button class="btn btn-outline-primary waves-effect" type="submit">
                                                <i class="ph-magnifying-glass-bold"></i>
                                            </button>
                                        </div>
                                    </form> 
                                </div>
							</div>
						</div> --}}
                        <div class="card-body">
                            <div class="flex-row mt-2" style="margin-top: -1.5rem !important;">
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
                        </div>
                    </div>
                    <div class="card desktop_view">

                        <div class="card-body m-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">{{ __('Invoice No') }}</th>
                                            <th>{{ __('Settlement Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Created at') }}</th>
                                            <th>{{ __('Paid at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list font-size-base rowlink" data-link="row">
                                        @foreach ($posts as $key => $row)
                                            <tr>
                                                <td class="text-left">
                                                    <a
                                                        href="{{ route('seller.settlements.show', $row->id) }}">{{ $row->invoice_no }}</a>
                                                </td>
                                                <td>
                                                    @if (isset($row->settlement_date))
                                                        <a
                                                            href="{{ route('seller.settlements.show', $row->id) }}">{{ $row->settlement_date->format('d-F-Y') }}</a>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>{{ amount_format($row->amount) }}</td>
                                                <td>
                                                    @if ($row->status == 'unpaid')
                                                        <span class="badge badge-light-warning">{{ __('Pending') }}</span>
                                                    @elseif($row->status == 'paid')
                                                        <span
                                                            class="badge badge-light-success">{{ __('Complete') }}</span>
                                                    @endif
                                                </td>
                                                <th>{{ $row->created_at ? $row->created_at->format('d-F-Y') : '--' }}</th>
                                                <th>{{ $row->paid_at ? $row->paid_at->format('d-F-Y') : '--' }}</th>
                                                <!-- <td>
                                                <a href="{{ route('seller.settlements.invoice', $row->id) }}" class="btn btn-icon btn-primary waves-effect waves-float waves-light">
                                                    <i class="ph-file-pdf-bold"></i>
                                                </a>
                                            </td> -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-row justify-content-end mt-2">
                                @if (count($request->all()) > 0)
                                    {{ $posts->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                                @else
                                    {{ $posts->links('vendor.pagination.bootstrap-4') }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row gy-2 mobile_view">
                        @foreach ($posts as $key => $row)
                            <div class="col-12">
                                <div class="card">
                                    <div class="rounded p-2">
                                        <a
                                            href="{{ route('seller.settlements.show', $row->id) }}">{{ $row->invoice_no ?? '' }}</a>
                                        <br>
                                        @if ($row->status == 'unpaid')
                                            <span class="badge badge-light-warning mb-1">{{ __('Pending') }}</span>
                                        @elseif($row->status == 'paid')
                                            <span class="badge badge-light-success mb-1">{{ __('Complete') }}</span>
                                        @endif

                                        <h6 class="d-flex align-items-center fw-bolder">
                                            <span class="me-50">{{ amount_format($row->amount ?? '') }}</span>
                                        </h6>
                                        <span>{{ __('Paid at') }}
                                            {{ $row->paid_at ? $row->paid_at->format('d-F-Y') : '--' }} </span>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                        <div class="d-flex flex-row justify-content-end mt-2">
                            @if (count($request->all()) > 0)
                                {{ $posts->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
                            @else
                                {{ $posts->links('vendor.pagination.bootstrap-4') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="payment" value="{{ $request->payment_status ?? '' }}">
    <input type="hidden" id="order_status" value="{{ $request->status ?? '' }}">
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/order_index.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-pickers.js') }}"></script>
@endsection
