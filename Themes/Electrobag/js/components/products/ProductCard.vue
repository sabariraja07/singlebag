<template>
<div class="col-sm-4">  
    <div class="product-shortcode style-1">
        <div class="title">
            <div class="simple-article size-1 color col-xs-b5"  v-if="product.category">
                <a :href="categoryUrl"> {{ product.category.category.name }}</a>
            </div>
            <div class="h6 animate-to-green">
                <a :href="productUrl">{{ product.title }}</a>
            </div>
            <div class="shortcode-rate-wrapper">
                <start-rating-view :rating="product.avg_rating"></start-rating-view>
            </div> 
        </div>

   
        <div class="preview">
            <img  :src="productImgUrl" width="200" height="200" alt="">
            <div class="preview-buttons valign-middle">
                <div class="valign-middle-content">
                    <a class="button size-2 style-2" :href="productUrl">
                        <span class="button-wrapper">
                            <span class="icon"><img src="frontend/electrobag/img/icon-1.png" alt=""></span>
                            <span class="text">Learn More</span>
                        </span>
                    </a>
                    <a class="button size-2 style-3" v-if="isOutOfStock() == true">
                       <span class="text"> {{ $trans('Out Of Stock') }}</span>
                    </a>
                    <a class="button size-2 style-3" :href="productUrl" v-else-if="product.required_options_count > 0">
                       <span class="text"> Choose Option</span>
                    </a>                    
                    <div v-else>
                        <a class="button size-2 style-3" @click="addToCart" v-if="addingToCart">
                            <span class="icon"><img src="frontend/electrobag/img/icon-3.png" alt=""></span>
                            <span class="text">{{ $trans('Add To Cart') }}</span>
                        </a>
                        <a class="button size-2 style-3" @click="addToCart" v-else tabindex="0">
                            <span class="icon"><img src="frontend/electrobag/img/icon-3.png" alt=""></span>
                            <span class="text">{{ $trans('Add To Cart') }}</span>
                        </a>
                    </div>                    
                </div>
            </div>
        </div>

        <div class="price">
            <div class="simple-article size-4 product-price">
                <span class="color">{{ $formatPrice(product.price.selling_price) }}</span>
                <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price.regular_price) }}</span>
            </div>
        </div>
        <div class="description">
            <div class="simple-article text size-2">{{getproductcontent(product.content)}}</div>
            <div class="simple-article text size-2">{{ product.stock.sku }}</div>
            <div class="icons">
                <a class="entry" :href="productUrl"><i class="fa fa-eye" aria-hidden="true"></i></a>
                <a class="entry" @click="syncWishlist">
                    <i class="fa fa-heart" aria-hidden="true" style="color:red;" v-if="inWishlist"></i>
                    <i class="fa fa-heart-o" aria-hidden="true" v-else></i>
                </a>
            </div>
        </div>    
    </div>
</div>    
    
</template>   
<style scoped>
.product-cart-wrap{
    height: 100% !important;
}
</style>
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
            isOutOfStock(){
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
            isRequiredOption(){
                var is_required = false
                if(this.product.options.length != 0){
                    this.product.options.forEach(option => {
                        if(option.is_required == 1){
                            is_required = true;
                        }
                    });
                }
                return is_required;
            },
            getproductcontent(content) {
                
                var desc= content? JSON.parse(content.value): null;
                return desc? desc.excerpt: "";
            }
            
        }
    };

</script> 