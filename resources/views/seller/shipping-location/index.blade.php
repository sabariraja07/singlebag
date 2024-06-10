@extends('layouts.seller')
@section('title', 'Shipping Location')
@section('content')

    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Shipping Location') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Shipping Location') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Shipping Location') }}
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
                <div class="card-body">
                        <div style="margin-top: -31px !important;">
                            <form method="post" action="{{ route('seller.shipping_location.destroy') }}"
                            class="basicform_with_reload">
                            @csrf
                            <div class="d-flex flex-row justify-content-between mt-1">
                                <div class="desktop_view">
                                    <div class="input-group">
                                        <select class="form-control" name="method">
                                            <option disabled selected="">{{ __('Select Action') }}</option>
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="2">{{ __('Inactive') }}</option>
                                            <option value="0">{{ __('Delete') }}</option>
                                        </select>
                                        <button class="btn btn-primary waves-effect basicbtn"
                                            type="submit">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                                <div class="mobile_view"></div>
                                <a href="{{ route('seller.shipping_location.create') }}">
                                    <span class="btn btn-primary mb-1">+{{ __('create') }}</span>
                                </a>
                            </div>
                        </div>
                         <!-- Start: mobile view -->
                    <div class="row gy-2 mobile_view">
                        @foreach ($taxes as $row)
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
                                                            href="{{ route('seller.shipping_location.edit', $row->id) }}">
                                                            <i data-feather="edit-2"
                                                                class="me-50"></i><span>{{ __('Edit Shipping Location') }}</span>
                                                        </a>
                                                    </li>
                                                   
                                                </ul>
                                            </div>
                                            <div class="d-flex">
                                                <div class="blog-info">
                                                    <h4 class="mb-1 me-1" style="word-break: break-all;margin-right: 10px;"> {{ $row->estimated_days }}
                                                    </h4>
                                                    <div class="text-muted mb-0">
                                                        @if ($row->status == 1)
                                                            <span
                                                                class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                                        @elseif($row->status == 2)
                                                            <span
                                                                class="badge badge-light-danger mb-1">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
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
                                        <th class="am-title">{{ __('Country') }}</th>
                                        <th class="am-title">{{ __('State') }}</th>
                                        <th class="am-title">{{ __('City') }}</th>
                                        <th class="am-title">{{ __('Estimate Days') }}</th>
                                        <th class="am-date">{{ __('Status') }}</th>
                                        <th class="am-date">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($taxes as $row)
                                        <tr id="row{{ $row->id }}">
                                            <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="user-info">
                                                        @if($row->country == '*' || $row->country == '' )
                                                        <h5 class="mb-0">{{ __('All') }}
                                                        </h5>
                                                        @else 
                                                        <h5 class="mb-0">{{ $row->country ?? '' }}
                                                        </h5>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="user-info">
                                                        @if($row->state == '*' || $row->state == '' )
                                                        <h5 class="mb-0">{{ __('All') }}
                                                        </h5>
                                                        @else 
                                                        <h5 class="mb-0">{{ $row->state ?? '' }}
                                                        </h5>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="user-info">
                                                        @if($row->city == '*' || $row->city == '' )
                                                        <h5 class="mb-0">{{ __('All') }}
                                                        </h5>
                                                        @else 
                                                        <h5 class="mb-0">{{ $row->city ?? '' }}
                                                        </h5>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                               <h5 class="mb-0">{{ $row->estimated_days ?? '' }} </h5>
                                                        
                                            </td>
                                            <td>
                                                @if ($row->status == 1)
                                                    <span
                                                        class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
                                                @elseif($row->status == 2)
                                                    <span
                                                        class="badge rounded-pill badge-light-info">{{ __('Inactive') }}</span>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{{ route('seller.shipping_location.edit', $row->id) }}"
                                                    class="btn btn-icon btn-success waves-effect waves-float waves-light">
                                                    <i class="ph-pencil-bold"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- End: Desktop view -->
                        
                

                </div>
                {{-- </div> --}}
                </form>

                <div class="d-flex flex-row justify-content-end mt-2">
                    {{ $taxes->links('vendor.pagination.bootstrap-4') }}
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
