<template>
    <div class="col-lg-1-5 col-md-4 col-12 col-sm-6 mb-30">
        <ProductCard :product="product"/>
    </div>
</template>
<script>
    import ProductCard from './../ProductCard.vue';

    export default {
        components: { ProductCard },

        props: ['product'],
    };
</script>