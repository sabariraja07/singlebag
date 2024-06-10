@extends('layouts.seller')
@section('title', 'Shipping Location')

@section('content')
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
                <div class="alert alert-danger">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
                @endif
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('Shipping Location') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Settings') }}</a>
                            </li>   
                            <li class="breadcrumb-item active">{{ __('Shipping Location') }}
                            </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" data-bs-target="#addshippinglocation" data-bs-toggle="modal">
                                <span class="btn btn-primary mb-1">{{ __('Create') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card content-body">
                <!-- Basic table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body card-company-table" style="padding: 15px;">                              
                                <table class="datatables-basic table" id="pagenate">
                                    <thead>                                        
                                        <tr>                        
                                            <th class="am-title">{{ __('Name') }}</th>
                                            <th class="am-title">{{ __('Email') }}</th>
                                            <th class="am-title">{{ __('Mobile') }}</th>
                                            <th class="am-date">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                    $i = 1;
                                    @endphp
                                    @foreach($posts as $row)
						                        <tr id="row{{  $row->id }}">

                                        <td>{{ $i++ }}</td>

                                        <td>{{ $row->name  }}</td>
                  
                                        <td>{{ $row->created_at->diffforHumans()  }}</td>

                                        <td>
                                            <div class="dropdown">
                                                <button type="button"
                                                    class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                    data-bs-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('seller.location.edit',$row->id) }}">
                                                        <i data-feather="edit" class="me-50"></i>
                                                        <span>{{ __('Edit') }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
						                      @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex flex-row justify-content-end mt-2">
							                      {{ $posts->links('vendor.pagination.bootstrap-4') }}
						                    </div>
                                <div class="modal fade" id="addshippinglocation" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                                        <div class="modal-content">
                                            <div class="modal-header bg-transparent">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pb-5 px-sm-5 pt-50">
                                                <div class="text-center mb-2">
                                                    <h1 class="mb-1">{{ __('Add New Shipping Location') }}</h1>

                                                </div>
                                                <form method="post" action="{{ route('seller.location.store') }}"
                                                    class="row gy-1 pt-75">
                                                    @csrf

                                                    <div class="col-12 col-md-12">
                                                        <label class="form-label">{{ __('Title') }}</label>
                                                        <input type="text" class="form-control" required="" name="title">
                                                    </div>
                                                    <div class="col-12 text-center mt-2 pt-50">
                                                        <button type="submit" id="submit"
                                                            class="btn btn-primary me-1 basicbtn">Save</button>
                                                        <button type="reset" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            Discard
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('page-script')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/js/success.js') }}"></script>
@endsection
