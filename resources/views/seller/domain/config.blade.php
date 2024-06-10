@extends('layouts.seller')
@section('title', 'Domain Settings')
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        @media screen and (max-width: 900px) {
            .table_head {
                letter-spacing: -0.5px !important;
            }
            .table_space {
            margin-left: -37px !important;
            }
            

        }
        
    </style>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Domain Settings') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Settings') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Domain Settings') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">

        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h4>{{ __('Current Domain') }}</h4>

                            </div>
                            <div class="card-body">

                                <p>{{ url('/') }}</p>


                            </div>
                        </div>
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h4>{{ __('Requested domain') }}</h4>
                                <div class="card-header-action">

                                    <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createModal"><i
                                            class="{{ !empty($request) ? 'ph-edit-bold' : 'ph-plus-bold' }}"></i></a>


                                </div>
                            </div>
                            <div class="card-body">
                                @if (!empty($request))
                                    <a href="{{ $request->domain ?? '' }}"> {{ $request->domain ?? '' }}</a>
                                    @if ($request->status == 1)
                                        <span class="badge badge-success">{{ __('Connected') }} </span>
                                    @elseif($request->status == 2)
                                        <span class="badge badge-warning">{{ __('Pending') }} </span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Disabled') }} </span>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @if ($info['custom_domain'] == true)
        @if (empty($request->domain))
            <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pb-5 px-sm-5 pt-50">
                            <div class="text-center mb-2">
                                <h1 class="mb-1">{{ __('Add existing domain') }}</h1>


                            </div>
                            <hr />
                            <form method="POST" class="basicform_with_reload"
                                action="{{ route('seller.customdomain.store') }}">
                                @csrf

                                <div class="modal-body pb-5 px-sm-5 pt-50"">
                                    <div id="form-errors"></div>
                                    <div class="form-group">
                                        <label>{{ __('Custom domain') }}</label>
                                        <input class="form-control" autofocus="" name="domain" type="text"
                                            placeholder="example.com" required="">
                                        <small
                                            class="form-text text-muted">{{ __('Enter the domain you want to connect.') }}</small>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Configure your DNS records') }}</label>
                                        <small
                                            class="form-text text-muted">{{ $dns->dns_configure_instruction ?? '' }}</small>
                                        <table class="table table-nowrap card-table table_space">
                                            <thead>
                                                <tr>
                                                    <th class="table_head">{{ __('TYPE') }}</th>
                                                    <th class="table_head">{{ __('RECORD') }}</th>
                                                    <th class="table_head">{{ __('VALUE') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('A') }}</td>
                                                    <td>&nbsp;</td>
                                                    <td>{{ env('SERVER_IP') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('CNAME') }}</td>
                                                    <td>{{ __('www') }}</td>
                                                    <td>{{ env('CNAME_DOMAIN') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <small class="form-text text-muted">{{ $dns->support_instruction ?? '' }}</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white"
                                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="submit" class="btn btn-primary basicbtn"><span
                                            class="ladda-label">{{ __('Connect') }}</span><span
                                            class="ladda-spinner"></span></button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pb-5 px-sm-5 pt-50">
                            <div class="text-center mb-2">
                                <h1 class="mb-1">{{ __('Add existing domain') }}</h1>


                            </div>
                            <hr />
                            <form method="POST" class="basicform_with_reload" accept-charset="UTF-8"
                                action="{{ route('seller.customdomain.update', $request->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body pb-5 px-sm-5 pt-50"">
                                    <div id="form-errors"></div>
                                    <div class="form-group">
                                        <label>{{ __('Custom domain') }}</label>
                                        <input class="form-control" autofocus="" name="domain" type="text"
                                            placeholder="example.com" required=""
                                            value="{{ $request->domain ?? '' }}">
                                        <small
                                            class="form-text text-muted">{{ __('Enter the domain you want to connect.') }}</small>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Configure your DNS records') }}</label>
                                        <small
                                            class="form-text text-muted">{{ $dns->dns_configure_instruction ?? '' }}</small>
                                        <table class="table table-nowrap card-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('TYPE') }}</th>
                                                    <th>{{ __('RECORD') }}</th>
                                                    <th>{{ __('VALUE') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ __('A') }}</td>
                                                    <td>&nbsp;</td>
                                                    <td>{{ env('SERVER_IP') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('CNAME') }}</td>
                                                    <td>{{ __('www') }}</td>
                                                    <td>{{ env('CNAME_DOMAIN') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <small class="form-text text-muted">{{ $dns->support_instruction ?? '' }}</small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white"
                                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="submit" class="btn btn-primary basicbtn" data-style="expand-left"
                                        data-loading-text="Verify..."><span
                                            class="ladda-label">{{ __('Connect') }}</span><span
                                            class="ladda-spinner"></span></button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
