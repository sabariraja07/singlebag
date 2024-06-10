<template>
    <div class="product-shortcode style-4 rounded clearfix col-xs-b10">
            <a class="preview" href="javascript:void(0)">
                <img v-if="productUrl" :src="productImgUrl" alt="">
                <img v-else src="frontend/electrobag/img/product-28.jpg" alt="" />
            </a>
            <div class="description">
                <!-- <div class="simple-article color size-1 col-xs-b5">WIRELESS</div> -->
                <h6 class="h6 col-xs-b10"> <a :href="productUrl">{{ product.title }}</a></h6>
                <div class="simple-article color size-1 col-xs-b5"> 
                    <star-rating :show-rating="false" :star-size="18" v-model="product.reviews_count" :read-only="true"
                    :increment="0.5"></star-rating></div>
                <div class="simple-article dark">{{ $formatPrice(product.price?  product.price.regular_price : 0) }}</div>
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
                content: false,
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