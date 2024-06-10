<template>
    <div class="product-cart-wrap style-2 wow animate__animated animate__fadeInUp" data-wow-delay="0">
        <div class="product-img-action-wrap">
            <div class="product-img">
                <a :href="productUrl">
                    <img v-if="productImgUrl != null" :src="productImgUrl" @error="setAltImg" alt="" />
                    <img v-else :src="'frontend/singlebag/imgs/banner/banner-6.png'" alt="" />
                </a>
            </div>
        </div>
        <div class="product-content-wrap">
            <div class="deals-countdown-wrap" v-if="product.ending_date != null">
                <div class="deals-countdown" :data-countdown="product.ending_date"></div>
            </div>
            <div class="deals-content">
                <h2><a :href="productUrl">{{ product.title }}</a></h2>
                <star-rating :show-rating="false" :star-size="18" v-model="product.avg_rating" :read-only="true"
                    :increment="0.5"></star-rating>
                <div v-if="product.category">
                    <span class="font-small text-muted">By <a :href="categoryUrl">{{ product.category.category.name }}</a></span>
                </div>
                <div class="product-card-bottom">
                    <div class="product-price">
                        <span>{{ $formatPrice(product.price.selling_price) }}</span>
                        <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
                    </div>
                    <div class="add-cart">
                        <a class="add" @click="addToCart" v-if="addingToCart">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ $trans('Add') }}
                        </a>
                        <a class="add" @click="addToCart" v-else>
                            <i class="fi-rs-shopping-cart mr-5"></i>{{ $trans('Add') }}
                        </a>
                    </div>
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
        methods: {
            setAltImg(event) { 
                event.target.src = 'frontend/singlebag/imgs/shop/product-1-1.jpg'; 
            } 
        }
    };

</script>