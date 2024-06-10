<template>
    <div class="product-cart-wrap style-2 wow animate__animated animate__fadeInUp" data-wow-delay="0">
        <div class="product-img-action-wrap" style="width: 334px;height: 246px">
            <div class="product-img" style="width: 339px;height: 250px;">
                <a  :href="productUrl">
                    <img v-if="productImgUrl != null" :src="productImgUrl" @error="setAltImg" alt="" />
                    <img v-else :src="'frontend/singlebag/imgs/banner/banner-6.png'" alt="" />
                </a>
            </div>
        </div>
        <div class="product-content-wrap">
            <div class="deals-countdown-wrap" style="top:-110px;" v-if="product.price.ending_date != null">
                <div class="deals-countdown" :data-countdown="product.price.ending_date"></div>
            </div>
            <div class="deals-content">
                <h2><a  :href="productUrl">{{ product.title }}</a></h2>
                <star-rating :show-rating="false" :star-size="18" v-model="product.avg_rating" :read-only="true"
                    :increment="0.5"></star-rating>
                <!-- <div>
                                    <span class="font-small text-muted">By <a
                                            href="vendor-details-1.html">NestFood</a></span>
                                </div> -->
                <!-- <div class="product-card-bottom"> -->
                    <div class="product-price">
                        <span>{{ $formatPrice(product.price.selling_price) }}</span>
                        <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
                    </div>
                    <!-- <div class="add-cart">
                        <a class="add add-cart-btn-sm" @click="addToCart" v-if="addingToCart">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ $trans('Add') }}
                        </a>
                        <a class="add add-cart-btn-sm" @click="addToCart" v-else>
                            <i class="fi-rs-shopping-cart mr-5"></i>{{ $trans('Add') }}
                        </a>
                    </div> -->
                    <a class="btn btn-sm w-100 hover-up mt-10" style="background-color:#f74b81;" v-if="isOutOfStock() == true">
                        {{ $trans('Out Of Stock') }}
                    </a>
                    <a class="btn btn-sm w-100 hover-up mt-10" :href="productUrl" v-else-if="product.required_options_count > 0">
                        Choose Option
                    </a>

                    
                    <div v-else>
                        <a class="btn btn-sm w-100 hover-up mt-10"  @click="addToCart" v-if="addingToCart">
                            {{ $trans('Add') }}
                        </a>
                        <a class="btn btn-sm w-100 hover-up mt-10"   @click="addToCart" v-else tabindex="0">
                            <i class="fi-rs-shopping-cart mr-5"></i>{{ $trans('Add') }}
                        </a>
                    </div>
                <!-- </div> -->
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
                event.target.src = 'frontend/singlebag/imgs/shop/product-1-1.jpg'; 
            } 
        }
    };

</script>
