<template>    
    <div class="slider-wrapper side-borders">
        <div class="swiper-button-prev hidden-xs hidden-sm"></div>
        <div class="swiper-button-next hidden-xs hidden-sm"></div>
        <div class="swiper-container" data-breakpoints="1" data-xs-slides="1" data-sm-slides="4" data-md-slides="4" data-lt-slides="4"  data-slides-per-view="4">
            <div class="swiper-wrapper">
            <ProductListCard v-for="(product, index) in products.slice(0,10)" :key="index" :product="product"></ProductListCard>
            </div>
            <div class="swiper-pagination relative-pagination visible-xs visible-sm"></div>
        </div>
    </div>
</template>
<script>
    import ProductListCard from '../products/ProductListCard.vue';

    export default {
        components: { ProductListCard },

        props: ['products' , 'title'],

        methods: {
        },
    };
</script>
