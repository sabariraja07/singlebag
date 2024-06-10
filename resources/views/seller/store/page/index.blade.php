@extends('layouts.seller')
@section('title', 'Pages')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="./admin/css/pages/style.css">
    <style>
        .form-group {
            margin-bottom: 25px;
        }
    </style>
@endsection
@section('content')
    @php
        $url = domain_info('full_domain');
    @endphp
    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('success') }}</li>
                    </ul>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
            @endif
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Pages') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Online store') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('Pages') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="d-flex flex-row justify-content-between mt-1">
                <div></div>
                @if ($post_limit == true)
                    <a href="javascript:void(0)" data-bs-target="#addplan" data-bs-toggle="modal">
                        <span class="btn btn-primary mb-1">+{{ __('create') }}</span>
                    </a>
                @endif
            </div>
            <!-- Start: mobile view -->
            <div class="row gy-2 mobile_view" style="--bs-gutter-y: -0.5rem !important;" id="mobile_view">
                @foreach ($posts as $row)
                    {{-- @php
                        $decode = json_decode($row->excerpt->content);
                    @endphp --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="padding: 0.5rem !important;">
                                <div class="position-relative rounded p-25">
                                    <div class="dropdown dropstart btn-pinned" style="top: 0.2rem !important;">
                                        <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                            href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i data-feather="more-vertical" class="font-medium-4"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.page.edit', $row->id) }}">
                                                    <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ $url . '/page/' . $row->slug . '/' . $row->id }}"
                                                    target="_blank">
                                                    <i data-feather="eye" class="me-50"></i><span>{{ __('Show') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="d-flex">
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <span class="text-body-heading">{{ $row->title ?? '' }}</span>

                                            </h6>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- End: mobile view -->

            <!-- Start: desktop view -->
            <div class="card mobile_view desktop_view">
                <div class="card-body">
                    <div class="table-responsive mb-1">
                        <table class="table" id="pagenate">
                            <thead>
                                <tr>

                                    {{-- <th class="am-select">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input checkAll"
                                                id="selectAll">
                                            <label class="custom-control-label checkAll" for="selectAll"></label>
                                        </div>
                                    </th> --}}
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Name') }}</th>

                                    <th>{{ __('Url') }}</th>
                                    <th>{{ __('LAST UPDATE') }}</th>

                                </tr>
                            </thead>
                            @php
                                $i = 1;
                            @endphp
                            <tbody>
                                @foreach ($posts as $row)
                                    <tr id="row{{ $row->id }}">
                                        {{-- <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="ids[]" class="custom-control-input"
                                                    id="customCheck{{ $row->id }}"
                                                    value="{{ $row->id }}">
                                                <label class="custom-control-label"
                                                    for="customCheck{{ $row->id }}"></label>
                                            </div>
                                        </td> --}}
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $row->title }}
                                            <div>
                                                <a
                                                    href="{{ route('seller.page.edit', $row->id) }}">{{ __('Edit') }}</a>
                                                | <a href="{{ $url . '/page/' . $row->slug . '/' . $row->id }}"
                                                    target="_blank">{{ __('Show') }}</a>
                                            </div>
                                        </td>
                                        <td>{{ $url . '/page/' . $row->slug . '/' . $row->id }}</td>




                                        <td class="text-right">{{ $row->updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        </form>
                    </div>


                </div>
            </div>
            <!-- End: desktop view -->

            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <div class="modal fade" id="addplan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Create Page') }}</h1>

                    </div>
                    <form method="post" action="{{ route('seller.page.store') }}" class="row gy-1 pt-75" id="productform">
                        @csrf
                        <div class="col-sm-12">

                            {{ input(['title' => 'Page Title', 'name' => 'title', 'is_required' => true]) }}

                            {{ textarea(['title' => 'Page Description', 'name' => 'short_description', 'is_required' => true]) }}

                            {{ editor(['title' => 'Page Content', 'name' => 'content', 'class' => 'content']) }}


                            <div class="col-12 text-center mt-2 pt-50">
                                <button type="submit" class="btn btn-primary me-1 basicbtn">{{ __('Save') }}</button>
                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">{{ __('Discard') }}
                                    
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
