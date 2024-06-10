@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Settlement Details'])
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
                            <li class="list-group-item">{{ __('Paid at :') }} {{ $info->paid_at ? $info->paid_at->format('d-M-Y') : "--" }}</li>
                            <li class="list-group-item">{{ __('Created at :') }} {{ $info->created_at->format('d-M-Y') }}</li>
                            <li class="list-group-item">{{ __('Total Amount :') }} {{ amount_format($info->total_amount ?? 0) }}</li>
                            <li class="list-group-item">{{ __('Tax :') }} {{ amount_format($info->tax ?? 0) }}</li>
                            <li class="list-group-item">
                                {{ __('Admin Commission :') }}
                                <strong>{{ amount_format($info->charge ?? '0') }}</strong>
                            </li>
                            <li class="list-group-item">
                                {{ __('Settlement Rate :') }}
                                <strong>{{ $info->settlement_rate ?? '--' }} %</strong>
                            </li>
                            <li class="list-group-item">
                                {{ __('Payable Amount :') }}
                                <strong>{{ amount_format($info->amount ?? '0') }}</strong>
                            </li>
                        </ul>

                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">{{ __('Shop Details') }}</div>
                        <ul class="list-group">
                            <li class="list-group-item">{{ __('Name :') }} {{ $info->shop->name }}</li>
                            <li class="list-group-item">{{ __('Email :') }} {{ $info->shop->email }}</li>
                            <li class="list-group-item">{{ __('Total Earns :') }} {{ amount_format($amount ?? 0) }}</li>
                            <li class="list-group-item">{{ __('Total Unpaid Settlement :') }} {{ amount_format($unpaid_amount ?? 0) }}</li>
                            <li class="list-group-item">{{ __('Joining Date:') }} {{ $info->shop->created_at->format('d-M-Y') }}</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <form method="POST" action="{{ route('admin.shop.settlement.update',$info->id) }}" accept-charset="UTF-8" class="d-inline">
                                @csrf
                                @method('PUT')
                                <div class="btn-group">
                                    <select class="form-control" name="status">
                                        <option disabled=""><b>{{ __('Update Status') }}</b></option>
                                        <option value="paid" @if($info->status=='paid') selected="" @endif>{{ __('Paid') }}</option>
                                        <option value="unpaid" @if($info->status=='unpaid') selected="" @endif>{{ __('Unpaid') }}</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success float-right mt-2 ml-2 basicbtn">{{ __('Update Changes') }}</button>
                            </form>
                        </div>
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
                        <h4>{{ __('Order History') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center table-borderless">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ __('Order') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Shop') }}</th>
                                        <th>{{ __('Reseller') }}</th>
                                        <th class="text-right">{{ __('Amount') }}</th>
                                        <th class="text-right">{{ __('Tax') }}</th>
                                        <th class="text-right">{{ __('Commission') }}</th>
                                        <th class="text-right">{{ __('Settlement Amount') }}</th>
                                        <th>{{ __('Method') }}</th>
                                        <th>{{ __('Payment Status') }}</th>
                                        <th>{{ __('Fulfillment') }}</th>
                                        <!-- <th>{{ __('View') }}</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($histories as $key => $row)
                                    <tr>
                                        <td class="text-left"><a href="{{ route('admin.orders.shows',[$row->shop->id,$row->id]) }}">{{ $row->order_no }}</a></td>
                                        <td>{{ $row->created_at->format('d-F-Y') }}</td>
                                        <td>
                                            @if(isset($row->shop))
                                            <a href="{{ route('admin.shop.show',$row->shop->id) }}">{{ $row->shop->name }}</a>
                                            @else
                                            {{ "--" }}
                                            @endif
                                        </td>
                                        @php
                                        $group_order = \App\Models\Order::where('id', $row->group_order_id)->first();
                                        $get_shop = \App\Models\Shop::where('id', $group_order->shop_id)->first();
                                        @endphp
                                        <td>{{ $get_shop->name ?? ''}}</td>
                                        <td>{{ amount_format($row->total) }}</td>
                                        <td>{{ amount_format($row->tax) }}</td>
                                        <td>{{ amount_format($row->commission) }}</td>
                                        <td>{{ amount_format($row->order_settlement_sum_total) }}</td>
                                        <td>{{ $row->PaymentMethod->name ?? 'FREE TRIAL' }}</td>
                                        <td>
                                            @if($row->payment_status==1)
                                            <span class="badge badge-success">{{ __('Paid') }}</span>
                                            @elseif($row->payment_status == 2)
                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                            @else
                                            <span class="badge badge-danger">{{ __('Fail') }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge badge-success">{{ ucfirst($row->status) }}</span>

                                        </td>
                                        <!-- <td> <div class="dropdown d-inline">
											<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												{{ __('Action') }}
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item has-icon" href="{{ route('admin.order.edit',$row->id) }}"><i class="far fa-edit"></i> {{ __('Edit') }}</a>
												<a class="dropdown-item has-icon" href="{{ route('admin.order.show',$row->id) }}"><i class="far fa-eye"></i> {{ __('View') }}</a>
												<a class="dropdown-item has-icon" href="{{ route('admin.order.invoice',$row->id) }}"><i class="fa fa-file-invoice"></i> {{ __('Download Invoice') }}</a>

											</div>
										</div></td> -->
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