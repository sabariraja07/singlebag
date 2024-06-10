<template>
<div class="product-wrap mb-40">
    <div class="product-img product-img-zoom mb-25">
        <a :href="productUrl">
            <img :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="">
        </a>
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
                <a class="default-btn btn-md" :href="productUrl" style="background-color:#f74b81;">
                    {{ $trans('Out Of Stock') }}
                </a>
            </div>
            <div class="product-action-cart" v-else-if="product.required_options_count > 0">
                <a class="btn-md btn-choose" :href="productUrl">
                    {{ $trans('Choose Option') }}
                </a>
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
</template>

<script>
import StarRating from 'vue-star-rating';
import ProductCardMixin from '../../mixins/ProductCardMixin';

export default {
    components: {
        StarRating
    },
    data() {
        return {
            addingToCart: false,
            loadingWishlist: false,
        };
    },

    mixins: [
        ProductCardMixin,
    ],

    props: ['product'],
    computed: {},
    methods: {

    }
};
</script>

<style scoped>
.product-cart-wrap .product-img-action-wrap .product-img {
    height: 200px !important;
}

.product-cart-wrap {
    height: 100% !important;
}
</style>
