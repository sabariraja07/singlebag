<template>
    <article class="row align-items-center hover-up">
        <figure class="col-md-4 mb-0">
            <a :href="productUrl"><img :src="productImgUrl" alt=""></a>
        </figure>
        <div class="col-md-8 mb-0">
            <h6>
                <a :href="productUrl">{{ product.title }}</a>
            </h6>
            <star-rating :show-rating="false" :star-size="18" v-model="product.avg_rating" :read-only="true"
                :increment="0.5"></star-rating>
            <div class="product-price">
                <span>{{ $formatPrice(product.price? product.price.selling_price : 0) }}</span>
                <span class="old-price" v-if="product.price.offer_discount > 0">{{ $formatPrice(product.price?  product.price.regular_price : 0) }}</span>
            </div>
        </div>
    </article>
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
        methods: {}
    };

</script>