@extends('layouts.seller')
@section('title', 'Shipping Method')

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
                            <h2 class="content-header-title float-start mb-0">{{ __('Shipping Method') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Settings') }}</a>
                            </li>   
                            <li class="breadcrumb-item active">{{ __('Shipping Method') }}
                            </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" data-bs-target="#addshippingmethod" data-bs-toggle="modal">
                                <span class="btn btn-primary mb-1">{{ __('Create') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="padding: 15px;">                              
                                <table class="datatables-basic table" id="pagenate">
                                    <thead>                                        
                                        <tr>                        
                                            <th>{{ __('S.No') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Estimated Delivery Day') }}</th>
                                            <th>{{ __('Delivery Charges') }}</th>                
                                            <th>{{ __('Last Update') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                    $i =1;
                                    @endphp
                                    @foreach($posts as $row)
						            <tr id="row{{  $row->id }}">

                                        <td>{{ $i++ }}</td>
                                        <td>{{ $row->name  }}</td>
                                        <td>{{ $row->content  }}</td>
                                        <td>{{ $row->slug  }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->updated_at)->diffForHumans() }}</td>

                                        <td>
                                            <div class="dropdown">
                                                <button type="button"
                                                    class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                    data-bs-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('seller.shipping.edit',$row->id) }}">
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
                                <div class="modal fade" id="addshippingmethod" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                                        <div class="modal-content">
                                            <div class="modal-header bg-transparent">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pb-5 px-sm-5 pt-50">
                                                <div class="text-center mb-2">
                                                    <h1 class="mb-1">{{ __('Add New Shipping Method') }}</h1>

                                                </div>
                                                <form method="post" action="{{ route('seller.shipping.store') }}"
                                                    class="row gy-1 pt-75">
                                                    @csrf

                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">{{ __('Title') }}</label>
                                                        <input type="text" class="form-control" required="" name="title">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">{{ __('Estimated Delivery Day') }}</label>
                                                        <input type="text" step="any" class="form-control" required="" name="delivery_time" placeholder="1-2 days">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">{{ __('Delivery Charges') }}</label>
                                                        <input type="number" step="any" class="form-control" required="" name="price">
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">{{ __('Locations') }}</label>
                                                        <select multiple class="form-control select2" name="locations[]" required="">
                                                            {{ ConfigCategoryMulti('city') }}
                                                        </select>
                                                    </div>
                                                    <div class="col-12 text-center mt-2 pt-50">
                                                        <button type="submit" id="submit"
                                                            class="btn btn-primary me-1 basicbtn">{{ __('Save') }}</button>
                                                        <button type="reset" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            {{ __('Discard') }}
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
@section('vendor-script')
<script src="./admin/js/scripts/forms/form-select2.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
@endsection