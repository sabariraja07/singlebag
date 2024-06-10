@extends('layouts.seller')
@section('title', 'Customers')
@section('content')

    <!-- BEGIN: Content-->

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
                                <li class="breadcrumb-item">{{ __('Customers') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Customers') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="d-flex flex-row flex-wrap justify-content-end mt-1">
                <div class="flex-row mb-1">
                    <form class="card-header-form">
                        <div class="input-group">
                            <input type="text" id="src" class="form-control" placeholder="Search..."
                            name="src" autocomplete="off" value="{{ $src ?? '' }}">
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
                        </div>
                    </form>
                </div>
                @if (current_shop_type() != 'supplier')
                    <a href="{{ route('seller.customer.create') }}" class="mb-1 ms-1">
                        <span class="btn btn-primary">{{ __('create') }}</span>
                    </a>
                @endif
            </div>
            <!-- Mobile View Start-->
            <div class="row gy-2 mt-1 mobile_view">
                @php
                    $plan = user_limit();
                    $plan = filter_var($plan['customer_panel']);
                @endphp
                @foreach ($posts as $row)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title d-flex align-items-center">
                                    <div class="avatar me-50">
                                        <a href="javascript:void(0)">
                                            <img class="avatar" src="admin/images/man-2-circle.svg"
                                                alt="{{ $row->fullname ?? '' }}" width="38" height="38" />
                                        </a>
                                    </div>
                                    <div class="more-info">
                                        <h6 class="mb-0">{{ $row->fullname }}</h6>
                                        <p class="mb-0">#{{ $row->id }}</p>
                                    </div>
                                </div>

                                <div class="dropdown chart-dropdown card-dropdown">
                                    <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ route('seller.customer.show', $row->id) }}">
                                            <i data-feather="eye" class="me-50"></i><span>{{ __('View User') }}</span>
                                        </a>
                                        @if (current_shop_type() != 'seller')
                                            <a class="dropdown-item d-flex align-items-center"
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
                            </div>
                            <div class="card-body">
                                <div class="mb-25">{{ $row->email }}</div>
                                <div class="mb-25">{{ $row->mobile }}</div>
                                <div class="mb-25">{{  __('Total Orders') }} : <b>{{ number_format($row->orders_count) }}</b></div>
                                @if (current_shop_type() == 'seller')
                                    @if ($row->status == 1)
                                        <span
                                            class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                    @else
                                        <span
                                            class="badge badge-light-danger mb-1">{{ __('Inactive') }}</span>
                                    @endif
                                @endif
                                <div>
                                <small>{{ $row->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!--Mobile View End -->

            <!-- Desktop View Start -->
            <form method="post" action="{{ route('seller.customers.destroy') }}" class="basicform">
                @csrf

                @if (current_shop_type() == 'seller')
                    <div class="d-flex flex-row justify-content-between mt-1">
                        <div class="desktop_view">
                            <div class="input-group">
                                <select class="form-control" name="method">
                                    <option selected="">{{ __('Select Action') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                                <button class="btn btn-primary waves-effect basicbtn"
                                    type="submit">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card mt-1 desktop_view">
                    <div class="card-body">
                        <div class="table-responsive mb-1">
                            <table class="table" id="pagenate">
                                <thead>
                                    <tr>
                                        @if (current_shop_type() == 'seller')
                                            <th><input type="checkbox" class="checkAll"></th>
                                        @endif
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('MOBILE') }}</th>
                                        <th>{{ __('Total Orders') }}</th>
                                        @if (current_shop_type() == 'seller')
                                            <th>{{ __('Status') }}</th>
                                        @endif
                                        <th>{{ __('REGISTERED AT') }}</th>
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
                                            @if (current_shop_type() == 'seller')
                                                <td><input type="checkbox" name="ids[]"
                                                        value="{{ $row->id }}">
                                                </td>
                                            @endif
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-50">
                                                        <a href="javascript:void(0)">
                                                            <img class="avatar" src="admin/images/man-2-circle.svg"
                                                                alt="{{ $row->fullname ?? '' }}" width="38" height="38" />
                                                        </a>
                                                    </div>
                                                    <div class="more-info">
                                                        <h6 class="mb-0">{{ $row->fullname }}</h6>
                                                        <p class="mb-0">#{{ $row->id }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><a
                                                    href="{{ route('seller.customer.show', $row->id) }}">{{ $row->email }}</a>
                                            </td>

                                            <td>{{ $row->mobile }}</td>

                                            <td>{{ number_format($row->orders_count) }}</td>
                                            @if (current_shop_type() == 'seller')
                                                <td>
                                                    @if ($row->status == 1)
                                                        <span
                                                            class="badge badge-light-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $row->updated_at->diffForHumans() }}</td>
                                           
                                            <td>
                                                @if (current_shop_type() == 'supplier')
                                                <a href="{{ route('seller.customer.show', $row->id) }}"
                                                    class="btn btn-icon btn-primary waves-effect waves-float waves-light">
                                                    <i class="ph-eye-bold"></i>
                                                </a>
                                                @else
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                        data-bs-toggle="dropdown" aria-expanded="false">{{ __('Action') }}
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @if (current_shop_type() != 'supplier')
                                                            <a class="dropdown-item"
                                                                href="{{ route('seller.customer.edit', $row->id) }}"><i
                                                                    class="ph-edit-bold"></i>
                                                                {{ __('Edit account') }}</a>
                                                        @endif
                                                        <a class="dropdown-item"
                                                            href="{{ route('seller.customer.show', $row->id) }}"><i
                                                                class="ph-search-bold"></i> {{ __('View User') }}</a>
                                                        @if (current_shop_type() == 'reseller')
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
                                                    
                                                @endif
                                            </td>
                                          
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!--Desktop View End -->

                <span>
                    {{ __('Note') }}: 
                    <b class="text-danger">{{ __('For Better Performance Remove Unusual Users') }}</b>
                </span>
            </form>

            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>

        </div>
    </div>
    <div class="modal fade" id="addcustomer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Add New Customer') }}</h1>

                    </div>
                    <form method="post" action="{{ route('seller.customer.store') }}" class="row gy-1 pt-75">
                        @csrf

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('First Name') }}</label>
                            <input type="text" class="form-control" required="" name="first_name">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Last Name') }}</label>
                            <input type="text" class="form-control" required="" name="last_name">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control" required="" name="email">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" required="" name="password">
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" id="submit"
                                class="btn btn-primary me-1 basicbtn">{{ __('Save') }}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">{{ __('Discard') }}

                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- END: Content-->

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
