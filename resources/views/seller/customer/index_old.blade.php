@extends('layouts.seller')

@section('title', 'Customers')
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Customers') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('Customers') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Customers') }}
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
            <!-- Basic table -->
            <div class="row" id="basic-datatable">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header mb-10 border-bottom">
                            <div class="head-label">
                                <h4 class="card-title">{{ __('Customers') }}</h4>
                            </div>
                            @if (current_shop_type() != 'supplier')
                                <div class="dt-action-buttons text-end">
                                    <div class="dt-buttons d-inline-flex">
                                        <a href="{{ route('seller.customer.create') }}" type="button"
                                            class="dt-button create-new btn btn-primary">
                                            <span>
                                                <i class="ph-plus-bold"></i>
                                                {{ __('Create Customer') }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div><br>
                        <div class="card-body">
                            <div>
                                <form>
                                    <div class="input-group mb-2">

                                        <input type="text" id="src" class="form-control" placeholder="Search..."
                                            required="" name="src" autocomplete="off" value="{{ $src ?? '' }}">
                                        <select class="form-control selectric" name="type" id="type">
                                            <option value="first_name">{{ __('Search By First Name') }}</option>
                                            <option value="last_name">{{ __('Search By Last Name') }}</option>
                                            <option value="mobile">{{ __('Search By Mobile Number') }}</option>
                                            <option value="email">{{ __('Search By Email') }}</option>
                                            <option value="id">{{ __('Search By Id') }}</option>
                                        </select>
                                        <button class="btn btn-outline-primary waves-effect" type="submit">
                                            <i class="ph-magnifying-glass-bold"></i>
                                        </button>
                                        {{-- <div class="input-group-append">                                            
											<button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
										</div> --}}
                                    </div>
                                </form>
                            </div>

                        </div>
                        @if(current_shop_type() == 'seller')
                        <div class="d-flex flex-row justify-content-between mt-1"
                            style="padding-left: 22px !important;margin-top: 0rem !important;">
                            <div>
                                <form method="post" action="{{ route('seller.customers.destroy') }}" class="basicform">
                                    @csrf
                                    <div class="input-group">
                                        <select class="form-control" name="method">
                                            <option selected="">{{ __('Select Action') }}</option>
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
                                        </select>
                                        <button class="btn btn-primary waves-effect basicbtn"
                                            type="submit">{{ __('Submit') }}</button>
                                    </div>
                                    {{-- </form> --}}
                            </div>
                        </div>
                        @endif
                        <div class="card-body">
                            <div class="table-responsive mb-1">
                                <table class="table" id="pagenate">
                                    <thead>
                                        <tr>
                                            @if(current_shop_type() == 'seller')
                                            <th><input type="checkbox" class="checkAll"></th>
                                            @endif
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Mobile') }}</th>
                                            <th>{{ __('Total Orders') }}</th>
                                            @if(current_shop_type() == 'seller')
                                            <th>{{ __('Status') }}</th>
                                            @endif
                                            <th>{{ __('Registered At') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $plan = user_limit();
                                            $plan = filter_var($plan['customer_panel']);
                                            
                                        @endphp
                                        @foreach ($posts as $row)
                                            <tr id="row{{ $row->id }}">
                                                @if(current_shop_type() == 'seller')
                                                <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                                                @endif
                                                <td>
                                                    <div class="d-flex flex-row">
                                                        <div class="avatar me-1">
                                                            <img src="admin/images/man-2-circle.svg"
                                                                alt="{{ $row->fullname }}" width="42" height="42">
                                                        </div>
                                                        <div class="user-info">
                                                            <h5 class="mb-0">{{ $row->fullname }} (#{{ $row->id }})
                                                            </h5>
                                                            <small class="text-muted">{{ $row->email }}</small>
                                                            {{-- <a href="{{ route('seller.customer.edit',$row->id) }}">{{ __('Edit') }}</a> --}}
                                                        </div>
                                                    </div>
                                                </td>

                                                <td><a
                                                        href="{{ route('seller.customer.show', $row->id) }}">{{ $row->email }}</a>
                                                </td>

                                                <td>{{ $row->mobile }}</td>

                                                <td>{{ number_format($row->orders_count) }}</td>
                                                @if(current_shop_type() == 'seller')
                                                <td>
                                                    @if ($row->status == 1)
                                                        <span class="badge badge-light-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge badge-light-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                                @endif
                                                <td>{{ $row->updated_at->diffForHumans() }}</td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                            class="btn btn-primary waves-effect waves-float waves-light">{{ __('Action') }}</button>
                                                        <button type="button"
                                                            class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="visually-hidden">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @if (current_shop_type() != 'supplier')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('seller.customer.edit', $row->id) }}"><i
                                                                        class="ph-edit-bold"></i>
                                                                    {{ __('Edit Account') }}</a>
                                                            @endif
                                                            <a class="dropdown-item"
                                                                href="{{ route('seller.customer.show', $row->id) }}"><i
                                                                    class="ph-search-bold"></i> {{ __('View User') }}</a>
                                                            @if (current_shop_type() != 'supplier')
                                                                <a class="dropdown-item"
                                                                    @if ($plan == true) href="{{ route('seller.customer.login', $row->id) }}" @endif>
                                                                    @if ($plan !== true)
                                                                        <i class="ph-lock-bold text-danger"></i>
                                                                    @else
                                                                        <i class="ph-key-bold"></i>
                                                                    @endif {{ __('Login ') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <div class="d-flex flex-row justify-content-end mt-2">
                                {{ $posts->links('vendor.pagination.bootstrap-4') }}
                            </div>
                            <span>{{ __('Note') }}: <b
                                    class="text-danger">{{ __('For Better Performance Remove Unusual Users') }}</b></span>
                        </div>
                        <div class="modal fade" id="addcustomer" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                                <div class="modal-content">
                                    <div class="modal-header bg-transparent">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pb-5 px-sm-5 pt-50">
                                        <div class="text-center mb-2">
                                            <h1 class="mb-1">{{ __('Add New Customer') }}</h1>

                                        </div>
                                        <form method="post" action="{{ route('seller.customer.store') }}"
                                            class="row gy-1 pt-75">
                                            @csrf

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">{{ __('First Name') }}</label>
                                                <input type="text" class="form-control" required=""
                                                    name="first_name">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">{{ __('Last Name') }}</label>
                                                <input type="text" class="form-control" required=""
                                                    name="last_name">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">{{ __('Email') }}</label>
                                                <input type="email" class="form-control" required=""
                                                    name="email">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">{{ __('Password') }}</label>
                                                <input type="password" class="form-control" required=""
                                                    name="password">
                                            </div>
                                            <div class="col-12 text-center mt-2 pt-50">
                                                <button type="submit" id="submit"
                                                    class="btn btn-primary me-1 basicbtn">Save</button>
                                                <button type="reset" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    Discard
                                                </button>
                                            </div>
                                        @if(current_shop_type() == 'seller')
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/plugins/forms/pickers/form-pickadate.css') }}">
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/success.js') }}"></script>
    <script src="{{ asset('./admin/js/scripts/forms/pickers/form-pickers.js') }}"></script>
@endsection
