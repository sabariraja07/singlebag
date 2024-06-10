@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Customer'])
@endsection
@section('content')
<section class="section">

    <div class="section-body">

        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        <img alt="" src="{{ get_shop_logo_url($info->id) }}" class="profile-widget-picture" height="80">
                        <div class="profile-widget-items">
                        </div>
                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">{{ $info->fullname }} </div>
                        <ul class="list-group">

                            <li class="list-group-item">{{ __('Name :') }} {{ $info->fullname }}</li>
                            <li class="list-group-item">{{ __('Email :') }} {{ $info->email }}</li>
                            <li class="list-group-item">{{ __('Mobile Number :') }} {{ $info->partner->mobile_number ?? '' }}</li>
                            <li class="list-group-item">{{ __('Threshold Value :') }} {{ $info->partner->settlement_amount ?? '' }}</li>
                            @if(isset($info->partner))
                            <li class="list-group-item">{{ __('Bank Name :') }} {{ $info->partner->bank_details['bank_name'] ?? ''  }}</li>
                            <li class="list-group-item">{{ __('Account Number :') }} {{ $info->partner->bank_details['account_no'] ?? '' }}</li>
                            <li class="list-group-item">{{ __('IFSC Code :') }} {{ $info->partner->bank_details['ifsc_code'] ?? '' }}</li>
                            <li class="list-group-item">{{ __('KYC :') }} {{ $info->partner->bank_details['kyc'] ?? '' }}</li>
                            @endif
                            <li class="list-group-item">{{ __('Joining Date:') }} {{ $info->created_at->format('d-F-Y') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                    <form method="post" id="productform" novalidate="" action="{{ route('admin.email.store') }}">
                        @csrf
                        <div class="card-header">
                            <h4>{{ __('Send Email') }} </h4>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label>{{ __('Mail To') }}</label>
                                    <input type="email" name="email" class="form-control" value="{{ $info->email }}" required="">

                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label>{{ __('Subject') }}</label>
                                    <input type="text" class="form-control" required="" name="subject">

                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{ __('Message') }}</label>
                                    <textarea class="form-control" required="" name="content" id="content"></textarea>
                                </div>
                            </div>
                            <button class="btn btn-success basicbtn" type="submit">{{ __('Send') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Shops') }}</h4>
                    </div>
                    <div class="card-body">
                        {{-- <form action="{{ route('admin.customers.destroy') }}" class="basicform" method="post">
                        @csrf --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center table-borderless">
                                <thead>
                                    <tr>


                                        <th>#</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Domain') }}</th>
                                        <th>{{ __('Will Expire') }}</th>

                                        <th>{{ __('Created at') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shops as $index => $row)
                                    <tr>
                                        {{-- <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td> --}}
                                        <td>{{ ( $shops->perPage() * ($shops->currentPage() - 1)) + ($index + 1) }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->sub_domain }}</td>
                                        <td>{{ $row->will_expire  }}</td>
                                        <td>{{ $row->created_at->format('d-F-Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            {{ $shops->links('vendor.pagination.bootstrap-4') }}
                        </div>
                        {{-- <div class="float-left mb-1">

                                <div class="input-group">
                                    <select class="form-control selectric" name="type">
                                        <option value="">{{ __('Select Action') }}</option>

                        <option value="user_delete">{{ __('Delete Permanently') }}</option>

                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-success basicbtn" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>

                </div> --}}
                {{-- </form> --}}
            </div>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Plan Purchase History') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-center table-borderless">
                            <thead>
                                <tr>

                                    <td>#</td>
                                    <th>{{ __('Invoice No') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Payment Id') }}</th>

                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('View') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $index => $row)

                                <tr>
                                    <td>{{ ( $histories->perPage() * ($histories->currentPage() - 1)) + ($index + 1) }}</td>
                                    <td><a href="{{ route('admin.order.show',$row->id) }}">{{ $row->order_no }}</a>
                                    </td>
                                    <td>{{ $row->plan_info->name }}</td>
                                    <td>{{ amount_format($row->amount) }}</td>
                                    <td>{{ $row->PaymentMethod->name ?? '' }}</td>
                                    <td>{{ $row->trx ?? '' }}</td>
                                    <td>{{ $row->created_at->format('d-F-Y') }}</td>
                                    <td><a href="{{ route('admin.order.edit',$row->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a></td>
                                </tr>

                                @endforeach
                            </tbody>

                        </table>
                        {{ $histories->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

</section>
@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
@endpush