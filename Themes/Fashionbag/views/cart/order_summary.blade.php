<div class="cart-calculate-items">
    <h3 class="title">{{ __('Price Total') }}</h3>
    <div class="table-responsive">
        <table class="table">
            {{-- <tr>
                <td>{{ __('Price Total') }}</td>
                <td>@{{ $formatPrice(cart.priceTotal) }}</td>
            </tr> --}}
            @if(domain_info('shop_type') != 'reseller')
            <tr>
                <td>{{ __('Discount') }}</td>
                <td>@{{ $formatPrice(cart.discount) }}</td>
            </tr>
            @endif
            <tr>
                <td>{{ __('Tax') }}</td>
                <td>@{{ $formatPrice(cart.tax) }}</td>
            </tr>
            <tr>
                <td>{{ __('Subtotal') }}</td>
                <td>@{{ $formatPrice(cart.subtotal) }}</td>
            </tr>
            <tr class="total">
                <td>{{ __('Total') }}</td>
                <td class="total-amount">@{{ $formatPrice(cart.total) }}</td>
            </tr>
        </table>
    </div>
</div>