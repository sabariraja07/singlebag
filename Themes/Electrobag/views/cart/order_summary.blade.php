<h4 class="h4">cart totals</h4>
{{-- <div class="order-details-entry simple-article size-3 grey uppercase">
    <div class="row">
        <div class="col-xs-6">{{ __('Price Total') }}</div>
        <div class="col-xs-6 col-xs-text-right">
            <div class="color">@{{ $formatPrice(cart.priceTotal) }}</div>
        </div>
    </div>
</div> --}}
@if(domain_info('shop_type') != 'reseller')
<div class="order-details-entry simple-article size-3 grey uppercase">
    <div class="row">
        <div class="col-xs-6">{{ __('Discount') }}</div>
        <div class="col-xs-6 col-xs-text-right">
            <div class="color">@{{ $formatPrice(cart.discount) }}</div>
        </div>
    </div>
</div>
@endif
<div class="order-details-entry simple-article size-3 grey uppercase">
    <div class="row">
        <div class="col-xs-6">{{ __('Tax') }}</div>
        <div class="col-xs-6 col-xs-text-right">
            <div class="color">@{{ $formatPrice(cart.tax) }}</div>
        </div>
    </div>
</div>
<div class="order-details-entry simple-article size-3 grey uppercase">
    <div class="row">
        <div class="col-xs-6">{{ __('Subtotal') }}</div>
        <div class="col-xs-6 col-xs-text-right">
            <div class="color">@{{ $formatPrice(cart.subtotal) }}</div>
        </div>
    </div>
</div>
<div class="order-details-entry simple-article size-3 grey uppercase">
    <div class="row">
        <div class="col-xs-6">{{ __('Total') }}</div>
        <div class="col-xs-6 col-xs-text-right">
            <div class="color">@{{ $formatPrice(cart.total) }}</div>
        </div>
    </div>
</div>
               
            






               
               
               
              
    