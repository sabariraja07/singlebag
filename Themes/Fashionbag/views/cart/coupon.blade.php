<div class="apply-coupon-wrapper">
    <form action="#" @submit.prevent="applyCoupon()" class=" d-block d-md-flex">
        <input type="text" class="coupon" required="" placeholder="{{ __('Enter Your Coupon') }}" name="coupon" v-model="coupon" required />
        <button type="submit" class="btn btn-fb btn-hover-primary rounded-0">{{ __('Apply Coupon') }}</button>
    </form>
</div>