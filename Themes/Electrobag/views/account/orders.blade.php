@extends('electrobag::account.layout.app')
@section('account_breadcrumb')
    <a href="{{ url('/user/orders') }}">{{ __('Orders') }}</a>
@endsection

@section('account_title', __('Orders'))
@section('user_content')
    <table class="cart-table">
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
                    <td><span class="color">{{ amount_format($row->total) }}</span></td>
                    <td>{{ $row->gateway->name ?? 'COD' }}</td>
                    @php
                        $shipping_method = $row->shipping_info ? $row->shipping_info->method->title : '';
                        $estimate_date = $row->shipping_info ? $row->shipping_info->method->estimated_delivery : '';
                    @endphp
                    <td>
                        {{ $shipping_method . '(' . $estimate_date . ')' }}
                    </td>
                    <td>
                        @if ($row->payment_status == 2)
                            <div class="product-label red">{{ __('Pending') }}</div>
                        @elseif($row->payment_status == 1)
                            <div class="product-label green">{{ __('Complete') }}</div>
                        @elseif($row->payment_status == 0)
                            <div class="product-label red">{{ __('Cancel') }}</div>
                        @elseif($row->payment_status == 3)
                            <div class="product-label red">{{ __('Incomplete') }}</div>
                        @endif
                    </td>
                    <td>
                        @if ($row->status == 'pending')
                            <div class="product-label red">{{ __('Pending') }}</div>
                        @elseif($row->status == 'processing')
                            <div class="product-label green">{{ __('Processing') }}</div>
                        @elseif($row->status == 'ready-for-pickup')
                            <div class="product-label green">{{ __('Ready for pickup') }}</div>
                        @elseif($row->status == 'completed')
                            <div class="product-label green">{{ __('Completed') }}</div>
                        @elseif($row->status == 'archived')
                            <div class="product-label red">{{ __('Archived') }}</div>
                        @elseif($row->status == 'canceled')
                            <div class="product-label red">{{ __('Canceled') }}</div>
                        @else
                            <div class="product-label green">{{ $row->status }}</div>
                        @endif

                    </td>
                    <td>
                        <a class="entry" href="{{ url('/user/order/view', $row->id) }}"><i class="fa fa-eye"
                                aria-hidden="true"></i></a>
                    </td>
                </tr>
                @if (isset($orders) && count($orders) <= 0)
                    <tr>
                        <td colspan="6" style="text-align: center;padding:30px;">
                            <h6 style="color:red;">{{ __('No orders found') }}</h6>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <div class="empty-space col-xs-b30 col-sm-b50"></div>
    <div class="empty-space col-xs-b35 col-md-b70"></div>
    {{ $orders->links('vendor.pagination.electobag') }}
    <div class="empty-space col-xs-b30 col-sm-b50"></div>
    <div class="empty-space col-xs-b35 col-md-b70"></div>
@endsection
