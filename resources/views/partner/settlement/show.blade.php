@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=> trans('Settlement Details')])
@endsection
@section('content')
<section class="section">

    <div class="section-body">

        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        <img alt="" src="{{ asset('assets/img/man-2-circle.svg') }}" class="profile-widget-picture" height="80">
                        <div class="profile-widget-items">

                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">{{ __('Invoice No') }}</div>
                                <div class="profile-widget-item-value">{{ $info->invoice_no ?? '' }}</div>
                            </div>

                            <li class="list-group-item">
                                {{ __('Status :') }}
                                @if($info->status== 'paid') <span class="badge badge-success">{{ __('Paid') }}</span>
                                @elseif($info->status== 'unpaid') <span class="badge badge-danger">{{ __('Unpaid') }}</span>
                                @endif
                            </li>
                        </div>
                    </div>
                    <div class="profile-widget-description">
                        <ul class="list-group">
                            <li class="list-group-item">{{ __('Start Date :') }} {{ $info->start_date->format('d-M-Y') }}</li>
                            <li class="list-group-item">{{ __('End Date :') }} {{ $info->end_date->format('d-M-Y') }}</li>
                            <li class="list-group-item">{{ __('Paid at :') }} {{ $info->paid_at ? $info->paid_at->format('d-M-Y') : "" }}</li>
                            <li class="list-group-item">{{ __('Created at :') }} {{ $info->created_at->format('d-M-Y') }}</li>
                            <li class="list-group-item">{{ __('Earned Amount :') }} {{ amount_format($info->amount ?? 0) }}</li>
                            <li class="list-group-item">{{ __('Tax :') }} {{ amount_format($info->tax ?? 0) }}</li>
                            <li class="list-group-item">{{ __('Payable Commission :') }}
                                <strong>{{ amount_format($info->amount ?? 0) }}</strong>
                            </li>
                        </ul>

                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">{{ __('Partner Details') }}</div>
                        <ul class="list-group">
                            <li class="list-group-item">{{ __('Name :') }} {{ $info->user->fullname }}</li>
                            <li class="list-group-item">{{ __('Email :') }} {{ $info->user->email }}</li>
                            <li class="list-group-item">{{ __('Total Earns :') }} {{ amount_format($amount ?? 0) }}</li>
                            <li class="list-group-item">{{ __('Total Unpaid Settlement :') }} {{ amount_format($unpaid_amount ?? 0) }}</li>
                            <li class="list-group-item">{{ __('Joining Date:') }} {{ $info->user->created_at->format('d-M-Y') }}</li>

                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                    <form method="post" id="productform" novalidate="" action="{{ route('partner.email.store') }}">
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
                        <h4>{{ __('Plan Purchase History') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center table-borderless">
                                <thead>
                                    <tr>


                                        <th>{{ __('Invoice No') }}</th>
                                        <th>{{ __('Plan Name') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Commission') }}</th>
                                        <th>{{ __('Payment Method') }}</th>
                                        <th>{{ __('Payment Id') }}</th>

                                        <th>{{ __('Created at') }}</th>
                                        <th>{{ __('View') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($histories as $row)

                                    <tr>
                                        <td><a href="{{ route('partner.order.show',$row->id) }}">{{ $row->order_no }}</a></td>
                                        <td>{{ $row->plan->name }}</td>
                                        <td>{{ amount_format($row->amount) }}</td>
                                        <td>{{ amount_format($row->commission) }}</td>
                                        <td>{{ $row->PaymentMethod->name ?? '' }}</td>
                                        <td>{{ $row->trx ?? '' }}</td>
                                        <td>{{ $row->created_at->format('d-M-Y') }}</td>
                                        <td><a href="{{ route('partner.order.show',$row->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a></td>
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