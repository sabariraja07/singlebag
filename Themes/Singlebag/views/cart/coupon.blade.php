<div class="p-40">
    <h4 class="mb-10">{{ __('Apply Coupon') }}</h4>
    <p class="mb-30"><span class="font-lg text-muted">{{ __('Using A Promo Code?') }}</p>
    <form action="#" @submit.prevent="applyCoupon()">
        <div class="d-flex justify-content-between">
            <input class="font-medium mr-15 coupon"  name="coupon" v-model="coupon" placeholder="{{ __('Enter Your Coupon') }}">
            <button class="btn update-coupon-btn"><i class="fi-rs-label mr-10"></i>{{ __('Apply') }}</button>
        </div>
    </form>
</div>