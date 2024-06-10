<template>
    <div class="product-shortcode style-3">
        <div class="simple-article size-5 product-price grey col-xs-b20">BEST PRICE: 
            <span class="color">{{ $formatPrice(product.price.selling_price) }}</span>
            <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
        </div>
        <div class="products-line col-xs-b25">
            <!-- <div class="line col-xs-b10">
                <div class="fill" style="width: 55%;"></div>
            </div> -->
            <div class="row">
                <div class="simple-article size-1" v-if="!isOutOfStock() && product.stock.stock_manage == 1">
                    AVAILABLE: <span class="grey">{{ product.stock.stock_qty }}</span>
                </div>
                <div class="col-xs-6 text-right">
                    <!-- <div class="simple-article size-1">SOLD: <span class="grey">14</span></div> -->
                </div>
            </div>
        </div>
         <div class="swiper-container" data-loop="1">
            <div class="swiper-button-prev style-1"></div>
            <div class="swiper-button-next style-1"></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide swiper-image">
                    <img :src="productImgUrl" alt="" />
                </div>
            </div>
        </div>
        <div class="empty-space col-xs-b20"></div>
        <div class="h6 animate-to-green" v-if="product.title">
            <div class="shortcode-rate-wrapper">
                <start-rating-view :rating="product.avg_rating"></start-rating-view>
            </div>
            <a :href="productUrl">{{ product.title }}</a> 
        </div>
        <div class="empty-space col-xs-b10" v-if="product.title"></div>
        <div class="description">
            <div class="simple-article size-2" v-if="product.content">{{ getproductcontent(product.content) }}</div>
        </div>
        <div class="empty-space col-xs-b20" v-if="product.content"></div>
        <div class="countdown-wrapper" v-if="product.price.ending_date">
            <div class="countdown" :data-end="product.price.ending_date"></div>
        </div>
        <div class="preview-buttons">
            <div class="buttons-wrapper">
                <a class="button size-2 style-2" :href="productUrl">
                    <span class="button-wrapper">
                        <span class="icon"><img src="frontend/electrobag/img/icon-1.png" alt=""></span>
                        <span class="text">Learn More</span>
                    </span>
                </a>
                <a class="button size-2 style-3" v-if="isOutOfStock() == true">
                    <span class="button-wrapper">
                        <span class="text"> {{ $trans('Out Of Stock') }}</span>
                    </span>
                </a>
                <a class="button size-2 style-3" :href="productUrl" v-else-if="product.required_options_count > 0">
                    <span class="button-wrapper">
                        <span class="text"> Choose Option </span>
                    </span>
                </a>
                <a class="button size-2 style-3" @click="addToCart" v-else>
                    <span class="button-wrapper">
                        <span class="icon"><img src="frontend/electrobag/img/icon-3.png" alt=""></span>
                        <span class="text">Add To Cart</span>
                    </span>
                </a>
            </div>
        </div>
        <!-- <div class="countdown" :data-end="product.price.ending_date"></div>
        <div class="empty-space col-xs-b20"></div>


        <div class="simple-article size-5 grey product-price">
            <span class="color">{{ $formatPrice(product.price.selling_price) }}</span>
            <span class="old-price"
                v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
        </div>


        <div class="simple-article size-1" v-if="isOutOfStock() != true">AVAILABLE: <span
                class="grey">{{ product.stock.stock_qty }}</span></div>

        <img :src="productImgUrl" width="290" height="290" alt="" />

        <div class="empty-space col-xs-b20"></div>
        <div class="h6 animate-to-green"><a :href="productUrl">{{ product.title }}</a></div>

        <div class="description">
            <div class="simple-article text size-2">{{getproductcontent(product.content)}} </div>
        </div>
        <div class="preview-buttons">
            <div class="buttons-wrapper">
                <a class="button size-2 style-2" :href="productUrl">
                    <span class="button-wrapper">
                        <span class="icon"><img src="frontend/electrobag/img/icon-1.png" alt=""></span>
                        <span class="text">Learn More</span>
                    </span>
                </a>
                <a class="button size-2 style-3" v-if="isOutOfStock() == true">
                    <span class="button-wrapper">
                        <span class="text"> {{ $trans('Out Of Stock') }}</span>
                    </span>
                </a>
                <a class="button size-2 style-3" :href="productUrl" v-else-if="product.required_options_count > 0">
                    <span class="button-wrapper">
                        <span class="text"> Choose Option </span>
                    </span>
                </a>
                <a class="button size-2 style-3" @click="addToCart" v-else>
                    <span class="button-wrapper">
                        <span class="icon"><img src="frontend/electrobag/img/icon-3.png" alt=""></span>
                        <span class="text">Add To Cart</span>
                    </span>
                </a>
            </div>
        </div> -->
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
        methods: {
            isOutOfStock() {
                if (this.product.stock == null) {
                    return false;
                }
                if(this.product.stock.stock_status == 0 && this.product.stock.stock_manage == 1){
                    return true;
                }
                if (this.product.stock.stock_manage == 0) {
                    return false;
                }
                if (this.product.stock.stock_manage == 1 && this.product.stock.stock_qty > 0) {
                    return false;
                }
                return false;
            },
            isRequiredOption() {
                var is_required = false
                if (this.product.options.length != 0) {
                    this.product.options.forEach(option => {
                        if (option.is_required == 1) {
                            is_required = true;
                        }
                    });
                }
                return is_required;
            },
            getproductcontent(content) {
                var desc = content ? JSON.parse(content.value) : null;
                return desc ? desc.excerpt : "";
            }
        },
        mixins: [
            ProductCardMixin,
        ],
    };

</script>

<style scoped>
.swiper-image{
  width: 100%;
  height: 230px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center; 
}
.swiper-image img {
  height: auto;
  width: 100%;
}
</style>