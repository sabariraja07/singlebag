<div class="border p-md-4 cart-totals ml-30" v-if="cart.count != 0">
    <div class="table-responsive">
        <table class="table no-border">
            <tbody>
                {{-- <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">{{ __('Price Total') }}</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h6 class="text-heading text-end">@{{ $formatPrice(cart.priceTotal) }}</h6>
                    </td>
                </tr> --}}
                @if(domain_info('shop_type') != 'reseller')
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">{{ __('Discount') }}</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h6 class="text-heading text-end">@{{ $formatPrice(cart.discount) }}</h6>
                    </td>
                </tr>
                @endif
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">{{ __('Tax') }}</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h6 class="text-heading text-end">@{{ $formatPrice(cart.tax) }}</h6>
                    </td>
                </tr>
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">{{ __('Subtotal') }}</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h6 class="text-heading text-end">@{{ $formatPrice(cart.subtotal) }}</h6>
                    </td>
                </tr>
                <!-- <tr>
                                <td scope="col" colspan="2">
                                    <div class="divider-2 mt-10 mb-10"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="cart_total_label">
                                    <h6 class="text-muted">Shipping</h6>
                                </td>
                                <td class="cart_total_amount">
                                    <h5 class="text-heading text-end">Free</h4< /td>
                            </tr>
                            <tr>
                                <td class="cart_total_label">
                                    <h6 class="text-muted">Estimate for</h6>
                                </td>
                                <td class="cart_total_amount">
                                    <h5 class="text-heading text-end">United Kingdom</h4< /td>
                            </tr> -->
                <tr>
                    <td scope="col" colspan="2">
                        <div class="divider-2 mt-10 mb-10"></div>
                    </td>
                </tr>
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">{{ __('Total') }}</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">@{{ $formatPrice(cart.total) }}</h4>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <a href="{{ url('/checkout') }}" :disabled="loadingOrderSummary" class="btn mb-20 w-100">{{ __('Proceed To CheckOut') }}<i class="fi-rs-sign-out ml-15"></i></a>
</div>
