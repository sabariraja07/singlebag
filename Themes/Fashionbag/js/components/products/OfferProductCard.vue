<template>
<!-- Single Product Deal Start -->
<div class="product single-deal-product product-border-left" style="padding-left: 340px !important;">
    <!-- <div class="thumb">
        <a href="single-product-sale.html" class="image">
            <img src="assets/images/products/medium-size/1.jpg" alt="Product" />
        </a>
        <span class="badges">
            <span class="sale">-30%</span>
        </span>
    </div> -->
    <div class="thumb">
        <a :href="productUrl" class="image">
            <img class="first-image" :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="Product" />
            <img class="second-image" :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="Product" />
        </a>
        <span class="badges" v-if="isOutOfStock() == true">
            <span class="sale">Sold</span>
        </span>
        <div class="actions">
            <!-- <a href="javascript::void(0)" class="action wishlist" @click="syncWishlist">
                <span class="spinner-border spinner-border-sm" role="status" v-if="loadingWishlist" aria-hidden="true"></span>
                <i class="fa fa-heart" style="color:green;" v-else-if="inWishlist"></i>
                <i class="fa fa-heart" v-else></i>
            </a> -->
            <!-- <a href="javascript::void(0)" class="action quickview" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="pe-7s-search"></i></a>
            <a href="javascript::void(0)" class="action compare"><i class="pe-7s-shuffle"></i></a> -->
        </div>
    </div>
    <div class="content">
        <p class="inner-desc">Hurry Up! Offer Ends In:</p>
        <div class="countdown-area">
            <div class="countdown-wrapper d-flex" :data-countdown="product.price.ending_date"></div>
        </div>
        <h4 class="sub-title">
            <a  v-if="product.category" :href="categoryUrl">
                {{ product.category.category.name }}
            </a>
        </h4>
        <h5 class="title"><a :href="productUrl">{{ product.title }}</a></h5>
       
        <span class="ratings">
            <!-- <span class="rating-wrap">
                <span class="star" style="width: 80%"></span>
            </span> -->

                 <star-rating :show-rating="true" :star-size="16" v-model="product.avg_rating" :read-only="true"
                :increment="0.5"></star-rating>
            <!-- <span class="rating-num">({{ product.avg_rating }})</span> -->
        </span>
        <span class="price">
            <span class="new">{{ $formatPrice(product.price.selling_price) }}</span>
            <span class="old" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
        </span>
        <button class="btn btn-sm btn-outofstock" v-if="isOutOfStock() == true">
            {{ $trans('Out Of Stock') }}
        </button>
        <a class="btn btn-sm btn-choose btn-hover-dark" :href="productUrl" v-else-if="product.required_options_count > 0">
            {{ $trans('Choose Option') }}
        </a>
        <div v-else>
            <a class="btn btn-sm btn-fb btn-hover-dark" @click="addToCart" v-if="addingToCart">
                <i class="fa fa-spinner fa-spin mr-5"></i> {{ $trans('Add To Cart') }}
            </a>
            <a class="btn btn-sm btn-fb btn-hover-dark" @click="addToCart" v-else tabindex="0">
                {{ $trans('Add To Cart') }}
            </a>
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
    methods: {
        setAltImg(event) {
            event.target.src = 'frontend/fashionbag/imgs/shop/product-1-1.jpg';
        }
    }
};
</script>
