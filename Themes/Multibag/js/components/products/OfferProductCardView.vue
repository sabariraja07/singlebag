<template>
<div>
    <div class="row align-items-center">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <div class="deal-content">
                <h2>{{ product.title }}</h2>
                <p v-if="product.content">{{ getDesc() }}</p>
                <div class="deal-rating">
                    <star-rating :show-rating="true" :star-size="18" v-model="product.avg_rating" :read-only="true" :increment="0.5"></star-rating>
                </div>
                <div class="deal-price">
                    <span>{{ $formatPrice(product.price.selling_price) }}</span>
                    <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
                </div>
                <div class="timer-1 timer-style-1" v-if="product.price.ending_date != null">
                    <div :data-countdown="product.price.ending_date"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7 col-md-7">
            <div class="deal-img wow fadeInRight">
                <a :href="productUrl">
                    <img :src="product.image" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="">
                </a>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import StarRating from 'vue-star-rating';
import ProductCardMixin from '../../mixins/ProductCardMixin';

export default {
    components: {
        StarRating
    },
    props: ['product'],
    data() {
        return {
            addingToCart: false,
            loadingWishlist: false,
        };
    },

    mixins: [
        ProductCardMixin,
    ],
    mounted() {
        $('.timer-1 [data-countdown]').each(function () {
            var $this = $(this),
                finalDate = $(this).data('countdown');
            $this.countdown(finalDate, function (event) {
                $this.html(event.strftime('<span class="cdown day"> <span>%-D </span><p>Days</p></span> <span class="cdown hour"> <span> %-H</span> <p>Hours</p></span> <span class="cdown minutes"><span>%M</span> <p>Minutes</p> </span>'));
            });
        });
    },
    methods: {
        setAltImg(event) {
            event.target.src = 'frontend/multibag/imgs/shop/product-1-1.jpg';
        },
        offerPricePercent() {
            let price = ((this.product.price.regular_price - this.product.price.selling_price) / this.product.price.regular_price) * 100;
            return parseFloat(price).toFixed(2)
        },

        getDesc() {
            var data = JSON.parse(this.product.content.value);
            return data.expect ?? "";
        }
    }
};
</script>
