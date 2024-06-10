<div class="discount-code-wrapper discount-tax-wrap">
    <h4>{{ __('Apply Coupon') }}</h4>
    <div class="discount-code">
        <p>{{ __('Using A Promo Code?') }}</p>
        <form action="#" @submit.prevent="applyCoupon()">
            <input type="text" class="coupon" required="" placeholder="{{ __('Enter Your Coupon') }}" name="coupon" v-model="coupon">
            <button type="submit">{{ __('Apply Coupon') }}</button>
        </form>
    </div>
</div>