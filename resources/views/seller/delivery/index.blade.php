@extends('layouts.seller')
@section('title', 'Delivery Agent')
@section('content')

    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Delivery Agent') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Delivery Agent') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Delivery Agent') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="content-body">
            <section>

                <!-- api key list -->
                {{-- <div class="d-flex flex-row flex-wrap justify-content-end mt-1" style="box-shadow: none !important;background-color: transparent !important;">
                    <div class="card-header" style="padding: 0px !important;">
                        {{-- <h4 class="card-title">{{ __('Delivery Agent') }}</h4> --}}
                        {{-- <a href="{{ route('seller.delivery.create') }}">
                            <span class="btn btn-primary mt-1" style=" margin-right: 20px; ">+{{ __('create') }}</span>
                        </a>
                    </div>
                </div>  --}}
                <div class="d-flex flex-row flex-wrap justify-content-end mt-1">
                    <div class="flex-row mb-1">
                        <form class="card-header-form">
                            <div class="input-group mb-2">

                                <input type="text" id="src" class="form-control" placeholder="Search..."
                                    name="src" autocomplete="off" value="{{ $src ?? '' }}">
                                <select class="form-control selectric" name="type" id="type">
                                    <option value="first_name">{{ __('Search By First Name') }}</option>
                                    <option value="last_name">{{ __('Search By Last Name') }}</option>
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
                
                <div class="card-body">
                        <div style="margin-top: -31px !important;">
                            <form method="post" action="{{ route('seller.agent.destroy') }}"
                            class="basicform_with_reload">
                            @csrf
                            <div class="d-flex flex-row justify-content-between mt-1">
                                <div class="desktop_view">
                                    <div class="input-group">
                                        <select class="form-control" name="method">
                                            <option disabled selected="">{{ __('Select Action') }}</option>
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
                                            <option value="5">{{ __('Delete') }}</option>
                                        </select>
                                        <button class="btn btn-primary waves-effect basicbtn"
                                            type="submit">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                                <div class="mobile_view"></div>
                                <a href="{{ route('seller.delivery.create') }}">
                                    <span class="btn btn-primary mb-1">+{{ __('create') }}</span>
                                </a>
                            </div>
                        </div>
                        
                    <!-- Start: mobile view -->
                    <div class="row gy-2 mobile_view">
                        @php
                            $plan = user_limit();
                            $plan = filter_var($plan['customer_panel']);
                            $i = 1;
                        @endphp
                        @foreach ($posts as $row)
                            <div class="col-12">
                                <div class="card" style="margin-top: 33px;">
                                    <div class="card-body" style="padding: 0.5rem !important;">
                                        <div class="position-relative rounded p-2">
                                            <div class="dropdown dropstart btn-pinned">
                                                <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                                    href="javascript:void(0)" id="dropdownMenuButton1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i data-feather="more-vertical" class="font-medium-4"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.delivery.edit', $row->id) }}">
                                                            <i data-feather="edit-2"
                                                                class="me-50"></i><span>{{ __('Edit Agent') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.agent.view', $row->id) }}">
                                                            <i data-feather="eye"
                                                                class="me-50"></i><span>{{ __('View Agent') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.agent.login', $row->id) }}">
                                                            <i data-feather="key"
                                                                class="me-50"></i><span>{{ __('Login') }}</span>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                            <div class="d-flex">
                                                <a href="javascript:void(0)" class="me-2">
                                                    <img class="rounded img" src="{{ asset($row->preview->avathar) }}" />
                                                </a>
                                                <div class="blog-info">
                                                    <h4 class="mb-1 me-1" style="word-break: break-all;margin-right: 10px;"> {{ $row->fullname }} <br>(#{{ $row->id }})
                                                    </h4>
                                                    <div class="text-muted mb-0">
                                                        @if ($row->preview->status == 1)
                                                            <span
                                                                class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                                        @elseif($row->preview->status == 0)
                                                            <span
                                                                class="badge badge-light-danger mb-1">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="d-flex align-items-center flex-wrap">
                                                <h4 class="mb-1 me-1"> {{ $row->fullname }} (#{{ $row->id }})</h4>
                                                <td>
                                                    @if ($row->preview->status == 1)
                                                        <span class="badge badge-light-primary mb-1">Active</span>
                                                    @elseif($row->preview->status == 0)
                                                        <span class="badge badge-light-danger mb-1">Inactive</span>
                                                    @endif
                                                </td>
    
                                            </div>
                                            <h6 class="d-flex align-items-center fw-bolder">
                                                <span class="me-50">{{ $row->email ?? '' }}</span>
                                            </h6>
                                            <span>Registred at {{ $row->updated_at->diffForHumans() ?? '' }}</span> --}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <!-- End: mobile view -->

                    <!-- Start: Desktop view -->
                    <div class="card  mobile_view desktop_view">
                        <div class="card-body card-company-table">
                            <table class="datatables-basic table mb-1" id="pagenate">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        <th class="am-title">{{ __('Name') }}</th>
                                        <th class="am-title">{{ __('Status') }}</th>
                                        <th class="am-date">{{ __('REGISTERED AT') }}</th>
                                        <th class="am-date">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $plan = user_limit();
                                        $plan = filter_var($plan['customer_panel']);
                                        $i = 1;
                                    @endphp

                                    @foreach ($posts as $row)
                                        <tr id="row{{ $row->id }}">
                                            <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="avatar me-1">
                                                        <img src="{{ asset($row->preview->avathar) }}" width="42"
                                                            height="42">
                                                    </div>
                                                    <div class="user-info">
                                                        <h5 class="mb-0">{{ $row->fullname }} (#{{ $row->id }})
                                                        </h5>
                                                        <small class="text-muted">{{ $row->email }}</small>
                                                        {{-- <a href="{{ route('seller.customer.edit',$row->id) }}">{{ __('Edit') }}</a> --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($row->preview->status == 1)
                                                    <span
                                                        class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
                                                @elseif($row->preview->status == 0)
                                                    <span
                                                        class="badge rounded-pill badge-light-info">{{ __('Inactive') }}</span>
                                                @endif

                                            </td>

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
                                                        <a class="dropdown-item"
                                                            href="{{ route('seller.delivery.edit', $row->id) }}"><i
                                                                class="ph-edit-bold"></i> {{ __('Edit Agent') }}</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('seller.agent.view', $row->id) }}"><i
                                                                class="ph-search-bold"></i> {{ __('View Agent') }}</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('seller.agent.login', $row->id) }}"><i
                                                                class="ph-key-bold"></i> {{ __('Login') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- End: mobile view -->

                    <span>{{ __('Note') }}: <b
                            class="text-danger">{{ __('For Better Performance Remove Unusual Users') }}</b></span>
                </div>
                {{-- </div> --}}
                </form>

                <div class="d-flex flex-row justify-content-end mt-2">
                    {{ $posts->links('vendor.pagination.bootstrap-4') }}
                </div>
            </section>

        </div>
    </div>


    <!-- END: Content-->

@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/success.js') }}"></script>
    
@endsection
