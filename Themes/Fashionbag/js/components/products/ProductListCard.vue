<template>
    <div class="single-product-list product-hover mb-6">

        <div class="thumb">
            <a :href="productUrl" class="image">
                <img class="first-image" :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="Product" />
                <img class="second-image" :src="productImgUrl" @error="$event.target.src='/assets/img/empty/450x600.jpg'" alt="Product" />
            </a>
        </div>
        <div class="content" style="position: relative !important;overflow: hidden !important;">
            <h5 class="title"><a :href="productUrl">{{ product.title }}</a></h5>
            <span class="price">
                    <!-- <span class="new">$38.00</span>
            <span class="old">$42.50</span> -->
            <span>{{ $formatPrice(product.price? product.price.selling_price : 0) }}</span>
            <span class="old" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price?  product.price.regular_price : 0) }}</span>
            </span>
            <span class="ratings">
                    <!-- <span class="rating-wrap">
                        <span class="star" style="width: 100%"></span>
            </span>
            <span class="rating-num">(4)</span> -->
            <star-rating :show-rating="true" :star-size="18" v-model="product.avg_rating" :read-only="true"
            :increment="0.5"></star-rating>
            </span>
        </div>
    </div>
</template>
<style scoped>
.thumb img{
    max-height: 100px;
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
        methods: {}
    };

</script>