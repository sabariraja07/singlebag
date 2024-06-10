<form action="#" @submit.prevent="applyCoupon()">
    <div class="single-line-form">
        <input class="simple-input" type="text" name="coupon" v-model="coupon" placeholder="{{ __('Enter Your Coupon') }}" />
        <div class="button size-2 style-3">
            <span class="button-wrapper">
                <span class="icon"><img src="frontend/electrobag/img/icon-4.png" alt=""></span>
                <span class="text">{{ __('Apply') }}</span>
            </span>
            <input type="submit" value="">
        </div>
    </div>
</form>