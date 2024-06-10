@php
$cart = get_cart();
@endphp
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Cart Items') }}</h4>
    </div>
    <div class="card-body">
        <div class="card-transaction">
            @foreach ($cart['items'] as $row)
                <div class="transaction-item">
                    <div style="display: flex;">
                        <div class="avatar bg-light-primary rounded float-start">
                            <div class="avatar-content" style="height: 63px !important;">
                                <img src="{{ asset($row->attributes ? $row->attributes->image : '') }}" alt="#" width="32" height="32">
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">{{ $row->name }} </h6>
                            <small> {{ $row->quantity }} X {{ $row->price }}</small><br>
                            <div class="mb-1"></div>
                            {{-- @foreach ($row->options->attribute as $attribute)
                            <span class="badge rounded-pill bg-info" style="margin-top: 8px;margin-bottom: 4px;">{{ $attribute->attribute->name }} - {{ $attribute->variation->name }} </span><br>
                            @endforeach --}}
                            @foreach ($row->attributes->options as $op)
                            <span class="badge badge-light-secondary rounded-pill">{{ $op->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="fw-bolder text-default">{{ amount_format($row->subTotal) }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="price-details">
            <h6 class="price-title mb-1">{{ __('Price Details') }}</h6>
            <hr>
            <ul class="list-unstyled">
                <li class="price-detail mb-1">
                    <div class="detail-title">{{ __('Subtotal') }}</div>
                    <div class="detail-amt">{{ amount_format($cart['subtotal']) }}</div>
                </li>
                <li class="price-detail mb-1">
                    <div class="detail-title">{{ __('Tax') }}</div>
                    <div class="detail-amt">{{ amount_format($cart['tax']) }}</div>
                </li>

                @if ($cart['weight'] > 0)
                    <li class="price-detail mb-1">
                        <div class="detail-title">{{ __('Weight') }}</div>
                        <div>{{ $cart['weight'] }}</div>
                    </li>
                @endif
                @if ($cart['discount'] > 0)
                    <li class="price-detail mb-1">
                        <div class="detail-title">{{ __('Discount') }}</div>
                        <div class="detail-amt discount-amt text-danger">-{{ amount_format($cart['discount']) }}
                        </div>
                    </li>
                @endif
                <li class="price-detail mb-1">
                    <div class="detail-title">{{ __('Shipping Cost') }}</div>
                    <div class="detail-amt amount shipping_cost" id="total_shipping_amount">{{ amount_format(0) }}
                    </div>
                </li>
            </ul>
            <hr>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <h5 class="detail-title detail-total">{{ __('Total') }}</h5>
                    <input type="hidden" name="total" id="cart-total" value="{{ $cart['total'] }}">
                    <div class="detail-amt fw-bolder cart_total">{{ amount_format($cart['total']) }}</div>
                </li>
            </ul>
        </div>
    </div>
</div>