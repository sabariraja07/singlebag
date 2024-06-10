<template>
    <div class="product-cart-wrap wow animate__animated animate__fadeIn" data-wow-delay=".1s">
        <div class="product-img-action-wrap">
            <div class="product-img product-img-zoom">
                <a :href="productUrl">
                    <img class="default-img" :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="">
                    <img class="hover-img" :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="">
                </a>
            </div>
            <div class="product-action-1">
                <a aria-label="Add To Wishlist" class="action-btn add-wishlist-btn-sm" @click="syncWishlist">
                    <span class="spinner-border spinner-border-sm" role="status" v-if="loadingWishlist"
                        aria-hidden="true"></span>
                    <i class="fa fa-heart" style="color:red;" v-else-if="inWishlist"></i>
                    <i class="fi-rs-heart" v-else></i>
                </a>
                <!-- <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a> -->
            </div>
            <!-- <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="new">New</span>
                                    </div> -->
        </div>
        <div class="product-content-wrap">
            <div class="product-category">
                <a  v-if="product.category" :href="categoryUrl">
                    {{ product.category.category.name }}
                </a>
            </div>
            <h2><a :href="productUrl">{{ product.title }}</a>
            </h2>
            <star-rating :show-rating="false" :star-size="18" v-model="product.avg_rating" :read-only="true"
                :increment="0.5"></star-rating>
            <div class="product-price mt-10">
                <span>{{ $formatPrice(product.price.selling_price) }}</span>
                <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
            </div>

            <a class="btn btn-sm w-100 hover-up mt-10" style="background-color:#f74b81;" v-if="isOutOfStock() == true">
                {{ $trans('Out Of Stock') }}
            </a>
            <a class="btn btn-sm w-100 hover-up mt-10" :href="productUrl" v-else-if="product.required_options_count > 0">
                {{ $trans('Choose Option') }}
            </a>

            
            <div v-else>
                <a class="btn btn-sm w-100 hover-up mt-10" @click="addToCart" v-if="addingToCart">
                    {{ $trans('Add To Cart') }}
                </a>
                <a class="btn btn-sm w-100 hover-up mt-10" @click="addToCart" v-else tabindex="0">
                    <i class="fi-rs-shopping-cart mr-5"></i>{{ $trans('Add To Cart') }}
                </a>
            </div>
            <!-- <div class="product-card-bottom">
               
                <div class="add-cart">
                    <a class="add add-cart-btn-sm" @click="addToCart" v-if="addingToCart">
                        <span class="spinner-border spinner-border-sm" role="status" 
                            aria-hidden="true"></span>Add
                    </a>
                    <a class="add add-cart-btn-sm" @click="addToCart" v-else>
                        <i class="fi-rs-shopping-cart mr-5"></i>Add
                    </a>
                </div>
            </div> -->
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
    .product-cart-wrap .product-img-action-wrap .product-img{
        height: 200px !important;
    }

    .product-cart-wrap{
        height: 100% !important;
    }
</style>