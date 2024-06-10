<template>
    <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 wow animate__ animate__fadeInUp animated" data-wow-delay="0" style="visibility: visible; animation-name: fadeInUp;">
        <h4 class="section-title style-1 mb-30 animated animated">{{ title }}</h4>
        <div class="product-list-small animated animated">
            <ProductListCard v-for="(product, index) in products.slice(0,5)" :key="index" :product="product"></ProductListCard>
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
