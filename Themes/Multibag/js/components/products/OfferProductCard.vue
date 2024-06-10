<template>
<div class="product-wrap-plr-1">
    <div class="product-wrap">
        <div class="product-img product-img-zoom mb-25">
            <a :href="productUrl">
                <img :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="">
            </a>
            <div class="timer-2 timer-style-1 product-timer timer-style-1-center automobile-timer" v-if="product.price.ending_date != null">
                <div :data-countdown="product.price.ending_date"></div>
            </div>
        </div>
        <div class="product-content">
            <h4><a :href="productUrl">{{ product.title }}</a></h4>
            <div class="product-price">
                <span>{{ $formatPrice(product.price.selling_price) }}</span>
                <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
            </div>
        </div>
        <div class="product-action-position-1 text-center">
            <div class="product-content">
                <h4><a :href="productUrl">{{ product.title }}</a></h4>
                <div class="product-price">
                    <span>{{ $formatPrice(product.price.selling_price) }}</span>
                    <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
                </div>
            </div>
            <div class="product-action-wrap">
                <div class="product-action-cart" v-if="isOutOfStock() == true">
                    <button class="padding-dec" :href="productUrl" style="background-color:#f74b81;">
                        {{ $trans('Out Of Stock') }}
                    </button>
                </div>
                <div class="product-action-cart" v-else-if="product.required_options_count > 0">
                    <button class="padding-dec btn-choose" :href="productUrl">
                        {{ $trans('Choose Option') }}
                    </button>
                </div>
                <div class="product-action-cart" v-else>
                    <button class="padding-dec" @click="addToCart" v-if="addingToCart">
                        {{ $trans('Add To Cart') }}
                    </button>
                    <button class="padding-dec" title="Add to Cart" @click="addToCart" v-else>{{ $trans('Add To Cart') }}</button>
                </div>
                <!-- <button data-toggle="modal" data-target="#exampleModal"><i class="icon-zoom"></i></button>
                <button title="Add to Compare"><i class="icon-compare"></i></button> -->
                <!-- <a aria-label="Add To Wishlist" class="action-btn add-wishlist-btn-sm" @click="syncWishlist">
                <span class="spinner-border spinner-border-sm" role="status" v-if="loadingWishlist"
                    aria-hidden="true"></span>
                <i class="fa fa-heart" style="color:red;" v-else-if="inWishlist"></i>
                <i class="fi-rs-heart" v-else></i>
            </a> -->
                <button title="Add to Wishlist" @click="syncWishlist">
                    <span class="spinner-border spinner-border-sm" role="status" v-if="loadingWishlist" aria-hidden="true"></span>
                    <i class="icon-heart" style="color:red;" v-else-if="inWishlist"></i>
                    <i class="icon-heart-empty" v-else></i>
                </button>
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
        $('.timer-2 [data-countdown]').each(function() {
            var $this = $(this),
                finalDate = $(this).data('countdown');
            $this.countdown(finalDate, function(event) {
                $this.html(event.strftime('<span class="cdown day"> <span>%-D </span><p>Day</p></span> <span class="cdown hour"> <span> %-H</span> <p>Hour</p></span> <span class="cdown minutes"><span>%M</span> <p>Minute</p> </span> <span class="cdown second"><span>%S</span> <p>Second</p> </span>'));
            });
        });
    },
    methods: {
        setAltImg(event) {
            event.target.src = 'frontend/multibag/imgs/shop/product-1-1.jpg';
        }
    }
};
</script>
