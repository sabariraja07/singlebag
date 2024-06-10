@extends('layouts.seller')
@section('title', 'Transactions')
@section('content')
    <!-- BEGIN: Content-->

    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Transactions') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Transactions') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="d-flex flex-row justify-content-end mt-2" id="transaction_mobile">
                <form class="card-header-form">
                    <div class="input-group">
                        <input type="text" name="src" value="{{ $request->src ?? '' }}"
                            class="form-control" required="" placeholder="transactions id..." />
                        <button class="btn btn-outline-primary waves-effect" type="submit">
                            <i class="ph-magnifying-glass-bold"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="row gy-2 mobile_view mt-1">
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
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('seller.order.show', $row->id) }}">
                                                <i class="ph-eye-bold"></i><span>{{ __('View Order') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item d-flex align-items-center edit" data-bs-toggle="modal"
                                            data-bs-target="#editModal" data-oid="{{ $row->id }}"
                                            data-td="{{ $row->id }}" data-mode="{{ $row->gateway->id ?? '' }}"
                                            data-transaction="{{ $row->transaction_id }}">
                                            <i class="ph-pencil-bold"></i><span>{{ __('Update Transaction') }}</span></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="d-flex flex-column flex-wrap mb-25">
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

                                    <div class="mt-25"><b>{{ amount_format($row->total) }}</b></div>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap mt-1 mb-25">
                                    <h6 class="d-flex align-items-center fw-bolder">
                                        <a href="{{ route('seller.order.show', $row->id) }}">{{ $row->order_no }}</a>
                                    </h6>

                                    @if ($row->payment_status == 2)
                                        <span class="badge badge-light-warning mb-1">{{ __('Pending') }}</span>
                                    @elseif($row->payment_status == 1)
                                        <span class="badge badge-light-success mb-1">{{ __('Complete') }}</span>
                                    @elseif($row->payment_status == 0)
                                        <span class="badge badge-light-danger mb-1">{{ __('Cancel') }}</span>
                                    @elseif($row->payment_status == 3)
                                        <span class="badge badge-light-danger mb-1">{{ __('Incomplete') }}</span>
                                    @endif
                                </div>

                                <span>Updated at {{ $row->updated_at->diffForHumans() ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div><br>
            <div class="card mobile_view desktop_view">
                <div class="card-body">
                    <table class="datatables-basic table mb-1" id="pagenate">
                        <thead>
                            <tr>
                                <th class="text-left">{{ __('ORDER NO') }}</th>
                                <th class="text-left">{{ __('Transaction Id') }}</th>
                                <th>{{ __('LAST UPDATE') }}</th>
                                <th>{{ __('CUSTOMER') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('PAYMENT') }}</th>
                                <th>{{ __('Method') }}</th>
                            </tr>
                        </thead>
                        <tbody class="list font-size-base rowlink" data-link="row">
                            @foreach ($orders as $key => $row)
                                <tr>
                                    <td class="text-left">
                                        <a href="{{ route('seller.order.show', $row->id) }}">{{ $row->order_no }}</a>
                                    </td>
                                    <td class="text-left">
                                        @if($row->payment_method == 'cod')
                                        <a href="#" class="edit" data-bs-toggle="modal"
                                            data-bs-target="#editModal" data-oid="{{ $row->id }}"
                                            data-td="{{ $row->id }}" data-mode="{{ $row->gateway->id ?? '' }}"
                                            data-transaction="{{ $row->transaction_id }}">{{ $row->transaction_id }}</a>
                                        @else
                                        {{ $row->transaction_id }}
                                        @endif
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('seller.order.show', $row->id) }}">{{ $row->updated_at->format('d-F-Y') }}</a>
                                        <br>
                                        <small>{{ $row->updated_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if ($row->customer_id != null)
                                            <a
                                                href="{{ route('seller.customer.show', $row->customer_id) }}">{{ $row->customer->fullname }}</a>
                                        @else
                                            {{ __('Guest Transaction') }}
                                        @endif
                                    </td>
                                    <td>{{ amount_format($row->total) }}</td>
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
                                    <td>{{ $row->payment_method ?? '' }}</td>
    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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


    <div class="modal fade" id="editModal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Edit') }}</h1>
                        <p></p>
                    </div>
                    <form method="post" action="{{ route('seller.transaction.store') }}" class="basicform">
                        @csrf
                        <input type="hidden" name="o_id" id="o_id" value="">
                        <input type="hidden" name="t_id" id="t_id" value="">
                        <div class="col-12 mb-1">
                            <label class="form-label" for="modalEditPaymentMethod">{{ __('Payment Method') }}</label>
                            <select id="modalEditPaymentMethod" name="method" class="form-select"
                                aria-label="Select Payment Method">
                                {{-- @foreach ($gateways as $row)
                                    <option value="{{ $row->method->id }}">{{ $row->method->name }}</option>
                                @endforeach --}}
                                <option value="cod">cod</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalEdittransaction_id">{{ __('Transaction Id') }}</label>
                            <input type="text" name="transaction_id" class="form-control" required=""
                                id="modalEdittransaction_id" placeholder="{{ __('Transaction Id') }}">
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit"
                                class="btn btn-primary me-1 waves-effect waves-float waves-light basicbtn">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal"
                                aria-label="Close">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/seller/transaction/index.js') }}"></script>
@endsection
