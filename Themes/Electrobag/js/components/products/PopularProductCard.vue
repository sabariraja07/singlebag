<template>

    <div class="product-shortcode style-7">
        <div class="preview">
            <img :src="productImgUrl" style="height:125px;" alt="">
            <div class="preview-buttons valign-middle">
                <div class="valign-middle-content">
                    <a class="button size-1 style-2" :href="productUrl">
                        <span class="button-wrapper">
                            <span class="icon"><img src="frontend/electrobag/img/icon-1.png" alt=""></span>
                            <span class="text">Learn More</span>
                        </span>
                    </a>
                    <a class="button size-1 style-3" v-if="isOutOfStock() == true">
                         <span class="button-wrapper">
                       <span class="text"> {{ $trans('Out Of Stock') }}</span>
                       </span>
                    </a>    
                    <a class="button size-1 style-3" :href="productUrl" v-else-if="product.required_options_count > 0">
                         <span class="button-wrapper">
                       <span class="text"> Choose Option </span>
                       </span>
                    </a>                              

                     <a class="button size-1 style-3" @click="addToCart" v-else>
                        <span class="button-wrapper">
                            <span class="icon"><img src="frontend/electrobag/img/icon-3.png" alt=""></span>
                            <span class="text">Add To Cart</span>
                        </span>
                    </a>
                        
                   
                </div>
            </div>
        </div>
        <div class="title">
            <div class="shortcode-rate-wrapper">
                <start-rating-view :rating="product.avg_rating"></start-rating-view>
            </div> 
            <div class="h6 animate-to-green"><a :href="productUrl">{{ product.title }}</a></div>
        </div>
       
        <div class="price">
            <div class="simple-article size-4 product-price">
                <span class="color">{{ $formatPrice(product.price? product.price.selling_price : 0) }}</span>
                <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price?  product.price.regular_price : 0) }}</span>
            </div>
        </div>
        <div class="icons">
            <a class="entry" :href="productUrl"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a class="entry" @click="syncWishlist">
                <i class="fa fa-heart" aria-hidden="true" style="color:red;" v-if="inWishlist"></i>
                <i class="fa fa-heart-o" aria-hidden="true" v-else></i>
            </a>
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
        },

        mixins: [
            ProductCardMixin,
        ],
    };

</script>
