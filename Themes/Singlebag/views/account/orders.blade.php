@extends('singlebag::account.layout.app')
@section('account_breadcrumb')
    <span></span> {{ __('You Orders') }}
@endsection
@section('user_content')
    <div class="tab-pane fade active show" id="orders" role="tabpanel" aria-labelledby="orders-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Your Orders</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-hover table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ __('Order Id') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Payment Mode') }}</th>
                                <th>{{ __('Shipping Method') }}</th>
                                <th>{{ __('Payment Status') }}</th>
                                <th>{{ __('Order Status') }}</th>
                                <th>{{ __('View') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $row)
                                <tr>
                                    <td><a href="{{ url('/user/order/view', $row->id) }}">{{ $row->order_no }}</a></td>
                                    <td>{{ amount_format($row->total) }}</td>
                                    <td>{{ $row->gateway->name ?? 'COD' }}</td>
                                    @php
                                        $shipping_method = $row->shipping_info ? $row->shipping_info->method->title : '';
                                        $estimate_date = $row->shipping_info ? $row->shipping_info->method->estimated_delivery : '';
                                    @endphp
                                    <td>
                                        {{ $shipping_method. '(' . $estimate_date . ')' }}
                                    </td>
                                    <td>
                                        @if ($row->payment_status == 2)
                                            <span class="badge rounded-pill bg-warning">{{ __('Pending') }}</span>
                                        @elseif($row->payment_status == 1)
                                            <span class="badge rounded-pill bg-success">{{ __('Complete') }}</span>
                                        @elseif($row->payment_status == 0)
                                            <span class="badge rounded-pill bg-danger">{{ __('Cancel') }}</span>
                                        @elseif($row->payment_status == 3)
                                            <span class="badge rounded-pill bg-danger">{{ __('Incomplete') }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($row->status == 'pending')
                                            <span class="badge rounded-pill bg-warning">{{ __('Pending') }}</span>
                                        @elseif($row->status == 'processing')
                                            <span class="badge rounded-pill bg-primary">{{ __('Processing') }}</span>
                                        @elseif($row->status == 'ready-for-pickup')
                                            <span class="badge rounded-pill bg-info">{{ __('Ready for pickup') }}</span>
                                        @elseif($row->status == 'completed')
                                            <span class="badge rounded-pill bg-success">{{ __('Completed') }}</span>
                                        @elseif($row->status == 'archived')
                                            <span class="badge rounded-pill bg-warning">{{ __('Archived') }}</span>
                                        @elseif($row->status == 'canceled')
                                            <span class="badge rounded-pill bg-danger">{{ __('Canceled') }}</span>
                                        @else
                                            <span class="badge rounded-pill bg-info">{{ $row->status }}</span>
                                        @endif

                                    </td>
                                    <td><a href="{{ url('/user/order/view', $row->id) }}"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{ $orders->links('vendor.pagination.bootstrap-4') }}
@endsection
