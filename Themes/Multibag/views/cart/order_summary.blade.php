<div class="payment-details mb-40" v-if="cart.count != 0">
    <h4 class="checkout-title">{{ __('Price Total') }}</h4>
    <ul>
        {{-- <li>{{ __('Price Total') }} <span>@{{ $formatPrice(cart.priceTotal) }}</span></li> --}}
        @if (domain_info('shop_type') != 'reseller')
            <li>{{ __('Discount') }} <span>@{{ $formatPrice(cart.discount) }}</span></li>
        @endif
        <li>{{ __('Tax') }} <span>@{{ $formatPrice(cart.tax) }}</span></li>
        <li>{{ __('Subtotal') }} <span>@{{ $formatPrice(cart.subtotal) }}</span></li>
    </ul>
    <div class="total-order">
        <ul>
            <li>{{ __('Total') }} <span>@{{ $formatPrice(cart.total) }}</span></li>
        </ul>
    </div>
</div>
<form action="#">
    <div class="proceed-btn">
        <a href="{{ url('/checkout') }}" :disabled="loadingOrderSummary">{{ __('Proceed To CheckOut') }}</a>
    </div>
</form>
